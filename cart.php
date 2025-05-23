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

// Fetch cart items with product and seller details
$stmt = $conn->prepare("
    SELECT c.*, p.product_name, p.image_url, sp.price, s.name as seller_name
    FROM Cart c
    JOIN Products p ON c.product_ID = p.product_ID
    JOIN Seller_Products sp ON p.product_ID = sp.product_ID
    JOIN Seller s ON sp.seller_ID = s.seller_ID
    WHERE c.customer_ID = ?
    ORDER BY c.date_added DESC
");
$stmt->execute([$_SESSION['customer_id']]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Shelluxe</title>
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
        <h1 class="mb-4">Shopping Cart</h1>

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <?php 
                                $itemCount = count($cart_items); 
                                $i = 0; 
                                foreach ($cart_items as $item): 
                                    $i++; 
                            ?>
                                <div class="cart-item mb-4" data-cart-id="<?php echo $item['cart_ID']; ?>">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                                 class="img-fluid rounded">
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-1">
                                                <a href="product.php?id=<?php echo $item['product_ID']; ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                                </a>
                                            </h5>
                                            <p class="text-muted mb-0">By <?php echo htmlspecialchars($item['seller_name']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <button class="btn btn-outline-secondary decrement" type="button">-</button>
                                                <input type="number" class="form-control text-center quantity-input" 
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       min="1" 
                                                       data-cart-id="<?php echo $item['cart_ID']; ?>">
                                                <button class="btn btn-outline-secondary increment" type="button">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <span class="h6 mb-0">$<?php echo number_format($item['price'], 2); ?></span>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button class="btn btn-link text-danger remove-item" data-cart-id="<?php echo $item['cart_ID']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($i < $itemCount): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>Calculated at checkout</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h5">Total</span>
                                <span class="h5">$<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                                <a href="index.php" class="btn btn-outline-primary">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
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
        // Quantity increment/decrement
        document.querySelectorAll('.increment').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                input.value = parseInt(input.value) + 1;
                updateCartItem(input.dataset.cartId, input.value);
            });
        });

        document.querySelectorAll('.decrement').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.nextElementSibling;
                if (input.value > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateCartItem(input.dataset.cartId, input.value);
                }
            });
        });

        // Remove item from cart
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.dataset.cartId;
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    fetch('api/cart/remove.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            cart_id: cartId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                            cartItem.remove();
                            updateCartTotal(data.total);
                            showToast('Item removed from cart', 'success');
                            if (data.cart_count !== undefined) updateCartCount(data.cart_count);
                            // If cart is empty, reload page to show empty cart message
                            if (data.cart_count === 0) {
                                location.reload();
                            }
                        } else {
                            showToast(data.message || 'Error removing item from cart', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error removing item from cart', 'error');
                    });
                }
            });
        });

        // Update cart item quantity
        function updateCartItem(cartId, quantity) {
            fetch('api/cart/update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartTotal(data.total);
                    showToast('Cart updated successfully', 'success');
                } else {
                    showToast(data.message || 'Error updating cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating cart', 'error');
            });
        }

        // Update cart total
        function updateCartTotal(total) {
            const totalElements = document.querySelectorAll('.h5:last-child');
            totalElements.forEach(element => {
                element.textContent = `$${parseFloat(total).toFixed(2)}`;
            });
        }
    </script>
</body>
</html> 