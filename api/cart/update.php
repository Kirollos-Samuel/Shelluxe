<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to update cart']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'] ?? null;
$quantity = $data['quantity'] ?? null;

if (!$cart_id || !$quantity) {
    echo json_encode(['success' => false, 'message' => 'Cart ID and quantity are required']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Check if cart item exists and belongs to user
    $stmt = $conn->prepare("
        SELECT c.*, sp.stock_quantity 
        FROM Cart c
        JOIN Products p ON c.product_ID = p.product_ID
        JOIN Seller_Products sp ON p.product_ID = sp.product_ID
        WHERE c.cart_ID = ? AND c.customer_ID = ?
    ");
    $stmt->execute([$cart_id, $_SESSION['customer_id']]);
    $cart_item = $stmt->fetch();

    if (!$cart_item) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }

    if ($quantity > $cart_item['stock_quantity']) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit();
    }

    // Update quantity
    $stmt = $conn->prepare("
        UPDATE Cart 
        SET quantity = ? 
        WHERE cart_ID = ?
    ");
    $stmt->execute([$quantity, $cart_id]);

    // Calculate new total
    $stmt = $conn->prepare("
        SELECT SUM(sp.price * c.quantity) as total
        FROM Cart c
        JOIN Products p ON c.product_ID = p.product_ID
        JOIN Seller_Products sp ON p.product_ID = sp.product_ID
        WHERE c.customer_ID = ?
    ");
    $stmt->execute([$_SESSION['customer_id']]);
    $total = $stmt->fetch()['total'] ?? 0;

    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully',
        'total' => $total
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} 