<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from wishlist']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$wishlist_id = $data['wishlist_id'] ?? null;

if (!$wishlist_id) {
    echo json_encode(['success' => false, 'message' => 'Wishlist ID is required']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Check if wishlist item exists and belongs to user
    $stmt = $conn->prepare("
        SELECT wishlist_ID 
        FROM Wishlist 
        WHERE wishlist_ID = ? AND customer_ID = ?
    ");
    $stmt->execute([$wishlist_id, $_SESSION['customer_id']]);
    $wishlist_item = $stmt->fetch();

    if (!$wishlist_item) {
        echo json_encode(['success' => false, 'message' => 'Wishlist item not found']);
        exit();
    }

    // Remove item from wishlist
    $stmt = $conn->prepare("
        DELETE FROM Wishlist 
        WHERE wishlist_ID = ?
    ");
    $stmt->execute([$wishlist_id]);

    // Get updated wishlist count
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM Wishlist 
        WHERE customer_ID = ?
    ");
    $stmt->execute([$_SESSION['customer_id']]);
    $wishlist_count = $stmt->fetch()['count'];

    echo json_encode([
        'success' => true,
        'message' => 'Item removed from wishlist',
        'wishlist_count' => $wishlist_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} 