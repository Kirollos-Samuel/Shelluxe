<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Fetch wishlist items with product and seller details
$stmt = $conn->prepare("
    SELECT w.*, p.product_name, p.image_url, p.description, sp.price, s.name as seller_name
    FROM Wishlist w
    JOIN Products p ON w.product_ID = p.product_ID
    JOIN Seller_Products sp ON p.product_ID = sp.product_ID
    JOIN Seller s ON sp.seller_ID = s.seller_ID
    WHERE w.customer_ID = ?
    ORDER BY w.date_added DESC
");
$stmt->execute([$_SESSION['customer_id']]);
$wishlist_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Shelluxe</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5 mt-5">
        <h1 class="mb-4">My Wishlist</h1>

        <?php if (empty($wishlist_items)): ?>
            <div class="text-center py-5">
                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                <h3>Your wishlist is empty</h3>
                <p class="text-muted">Save items you love to your wishlist for later.</p>
                <a href="index.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="product.php?id=<?php echo $item['product_ID']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">By <?php echo htmlspecialchars($item['seller_name']); ?></p>
                                <p class="card-text"><?php echo htmlspecialchars(substr($item['description'], 0, 100)) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">$<?php echo number_format($item['price'], 2); ?></span>
                                    <div class="btn-group">
                                        <button class="btn btn-primary add-to-cart" data-product-id="<?php echo $item['product_ID']; ?>">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                        <button class="btn btn-outline-danger remove-from-wishlist" data-wishlist-id="<?php echo $item['wishlist_ID']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                Added on <?php echo date('F j, Y', strtotime($item['date_added'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <script>
        // Remove from wishlist
        document.querySelectorAll('.remove-from-wishlist').forEach(button => {
            button.addEventListener('click', function() {
                const wishlistId = this.dataset.wishlistId;
                if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                    fetch('api/wishlist/remove.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            wishlist_id: wishlistId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const card = this.closest('.col-md-6');
                            card.remove();
                            showToast('Item removed from wishlist', 'success');
                            if (data.wishlist_count !== undefined) updateWishlistCount(data.wishlist_count);
                            // If wishlist is empty, reload page to show empty wishlist message
                            if (data.wishlist_count === 0) {
                                location.reload();
                            }
                        } else {
                            showToast(data.message || 'Error removing item from wishlist', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error removing item from wishlist', 'error');
                    });
                }
            });
        });

        // Add to cart from wishlist
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                fetch('api/cart/add.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Product added to cart', 'success');
                        if (data.cart_count !== undefined) updateCartCount(data.cart_count);
                    } else {
                        showToast(data.message || 'Error adding product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error adding product to cart', 'error');
                });
            });
        });
    </script>
</body>
</html> 