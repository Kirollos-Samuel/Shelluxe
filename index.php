<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelluxe - Luxury Bracelets</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <h1 class="h4 mb-0">Shelluxe</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex mx-auto" style="width: 50%;">
                    <input class="form-control me-2" type="search" placeholder="Search for bracelets...">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="wishlist.php">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                    <?php if(isset($_SESSION['customer_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="orders.php">Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section py-5 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Discover Your Perfect Bracelet</h1>
                    <p class="lead">Handcrafted luxury bracelets for every occasion</p>
                    <a href="#featured-products" class="btn btn-primary btn-lg">Shop Now</a>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/hero-bracelet.jpg" alt="Luxury Bracelet" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="categories-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Shop by Category</h2>
            <div class="row g-4">
                <?php
                $db = new Database();
                $conn = $db->getConnection();
                $stmt = $conn->query("SELECT * FROM Category LIMIT 5");
                while($category = $stmt->fetch()) {
                    echo '<div class="col-md-4 col-lg-2">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">' . htmlspecialchars($category['category_name']) . '</h5>
                                <p class="card-text">' . htmlspecialchars($category['description']) . '</p>
                                <a href="category.php?id=' . $category['category_ID'] . '" class="btn btn-outline-primary">View All</a>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured-products" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row g-4">
                <?php
                $stmt = $conn->query("
                    SELECT p.*, sp.price, s.name as seller_name 
                    FROM Products p 
                    JOIN Seller_Products sp ON p.product_ID = sp.product_ID 
                    JOIN Seller s ON sp.seller_ID = s.seller_ID 
                    LIMIT 6
                ");
                while($product = $stmt->fetch()) {
                    echo '<div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="position-relative">
                                <img src="' . htmlspecialchars($product['image_url']) . '" 
                                     class="card-img-top" 
                                     alt="' . htmlspecialchars($product['product_name']) . '"
                                     style="height: 200px; object-fit: cover;">
                                <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle wishlist-btn" 
                                        data-product-id="' . $product['product_ID'] . '"
                                        title="Add to Wishlist">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>
                                <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                                <p class="text-muted">By ' . htmlspecialchars($product['seller_name']) . '</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">$' . number_format($product['price'], 2) . '</span>
                                    <div class="btn-group">
                                        <a href="product.php?id=' . $product['product_ID'] . '" class="btn btn-outline-primary">View</a>
                                        <button class="btn btn-primary add-to-cart" data-product-id="' . $product['product_ID'] . '">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Shelluxe</h5>
                    <p>Your destination for luxury handcrafted bracelets. We bring you the finest quality jewelry from trusted sellers.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="contact.php" class="text-light">Contact</a></li>
                        <li><a href="shipping.php" class="text-light">Shipping Policy</a></li>
                        <li><a href="returns.php" class="text-light">Returns & Refunds</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                        <a href="#" class="text-light me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 Shelluxe. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <img src="assets/images/payment-methods.png" alt="Payment Methods" class="img-fluid" style="max-height: 30px;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <script>
        // Wishlist button functionality
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                fetch('api/wishlist/add.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.add('text-danger');
                        showToast('Product added to wishlist', 'success');
                        if (data.wishlist_count !== undefined) updateWishlistCount(data.wishlist_count);
                    } else {
                        showToast(data.message || 'Error adding to wishlist', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error adding to wishlist', 'error');
                });
            });
        });
    </script>
</body>
</html> 