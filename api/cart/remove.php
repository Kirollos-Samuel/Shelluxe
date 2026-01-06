<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from cart']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'] ?? null;

if (!$cart_id) {
    echo json_encode(['success' => false, 'message' => 'Cart ID is required']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Check if cart item exists and belongs to user
    $stmt = $conn->prepare("
        SELECT cart_ID 
        FROM Cart 
        WHERE cart_ID = ? AND customer_ID = ?
    ");
    $stmt->execute([$cart_id, $_SESSION['customer_id']]);
    $cart_item = $stmt->fetch();

    if (!$cart_item) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }

    // Remove item from cart
    $stmt = $conn->prepare("
        DELETE FROM Cart 
        WHERE cart_ID = ?
    ");
    $stmt->execute([$cart_id]);

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
        'message' => 'Item removed from cart',
        'total' => $total,
        'cart_count' => $cart_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} 