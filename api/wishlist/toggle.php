<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to update wishlist']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Check if product exists
    $stmt = $conn->prepare('SELECT product_ID FROM Products WHERE product_ID = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    // Check if item is already in wishlist
    $stmt = $conn->prepare('SELECT wishlist_ID FROM Wishlist WHERE customer_ID = ? AND product_ID = ?');
    $stmt->execute([$_SESSION['customer_id'], $product_id]);
    $wishlist_item = $stmt->fetch();

    if ($wishlist_item) {
        // Remove from wishlist
        $stmt = $conn->prepare('DELETE FROM Wishlist WHERE wishlist_ID = ?');
        $stmt->execute([$wishlist_item['wishlist_ID']]);
        $in_wishlist = false;
    } else {
        // Add to wishlist
        $stmt = $conn->prepare('INSERT INTO Wishlist (customer_ID, product_ID, date_added) VALUES (?, ?, NOW())');
        $stmt->execute([$_SESSION['customer_id'], $product_id]);
        $in_wishlist = true;
    }

    // Get updated wishlist count
    $stmt = $conn->prepare('SELECT COUNT(*) as count FROM Wishlist WHERE customer_ID = ?');
    $stmt->execute([$_SESSION['customer_id']]);
    $wishlist_count = $stmt->fetch()['count'];

    echo json_encode([
        'success' => true,
        'in_wishlist' => $in_wishlist,
        'wishlist_count' => $wishlist_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} 