<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Check if product exists and is in stock
    $stmt = $conn->prepare("
        SELECT sp.stock_quantity 
        FROM Seller_Products sp 
        WHERE sp.product_ID = ?
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    if ($product['stock_quantity'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit();
    }

    // Check if item already exists in cart
    $stmt = $conn->prepare("
        SELECT cart_ID, quantity 
        FROM Cart 
        WHERE customer_ID = ? AND product_ID = ?
    ");
    $stmt->execute([$_SESSION['customer_id'], $product_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        // Update quantity if item exists
        $new_quantity = $cart_item['quantity'] + $quantity;
        if ($new_quantity > $product['stock_quantity']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            exit();
        }

        $stmt = $conn->prepare("
            UPDATE Cart 
            SET quantity = ? 
            WHERE cart_ID = ?
        ");
        $stmt->execute([$new_quantity, $cart_item['cart_ID']]);
    } else {
        // Add new item to cart
        $stmt = $conn->prepare("
            INSERT INTO Cart (customer_ID, product_ID, quantity) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$_SESSION['customer_id'], $product_id, $quantity]);
    }

    // Get updated cart count
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM Cart 
        WHERE customer_ID = ?
    ");
    $stmt->execute([$_SESSION['customer_id']]);
    $cart_count = $stmt->fetch()['count'];

    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart',
        'cart_count' => $cart_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} 