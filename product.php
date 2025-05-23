<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$db = new Database();
$conn = $db->getConnection();

// Fetch product details
$stmt = $conn->prepare("
    SELECT p.*, c.category_name, s.name as seller_name, sp.price, sp.stock_quantity
    FROM Products p
    JOIN Category c ON p.category_ID = c.category_ID
    JOIN Seller_Products sp ON p.product_ID = sp.product_ID
    JOIN Seller s ON sp.seller_ID = s.seller_ID
    WHERE p.product_ID = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit();
}

// Fetch product reviews
$stmt = $conn->prepare("
    SELECT pr.*, c.customer_name
    FROM Product_Reviews pr
    JOIN Customer c ON pr.customer_ID = c.customer_ID
    WHERE pr.product_ID = ?
    ORDER BY pr.date_posted DESC
");
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll();

// Calculate average rating
$avg_rating = 0;
if (count($reviews) > 0) {
    $avg_rating = array_sum(array_column($reviews, 'rating')) / count($reviews);
}

// Check if product is in wishlist
$in_wishlist = false;
if (isset($_SESSION['customer_id'])) {
    $stmt = $conn->prepare("SELECT wishlist_ID FROM Wishlist WHERE customer_ID = ? AND product_ID = ?");
    $stmt->execute([$_SESSION['customer_id'], $product_id]);
    $in_wishlist = $stmt->fetch() ? true : false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Shelluxe</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Product Details -->
    <div class="container py-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        </div>
                        <!-- Add more carousel items if you have multiple images -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h2 mb-0"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    <button class="btn btn-outline-danger wishlist-btn" data-product-id="<?php echo $product['product_ID']; ?>">
                        <i class="fas fa-heart"></i> Add to Wishlist
                    </button>
                </div>
                
                <div class="mb-3">
                    <span class="h3 text-primary">$<?php echo number_format($product['price'], 2); ?></span>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center">
                        <div class="text-warning me-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?php echo $i <= round($avg_rating) ? '' : '-o'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-muted">(<?php echo count($reviews); ?> reviews)</span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-muted">By <?php echo htmlspecialchars($product['seller_name']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="input-group" style="width: 150px;">
                            <button class="btn btn-outline-secondary" type="button" id="decrement">-</button>
                            <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                            <button class="btn btn-outline-secondary" type="button" id="increment">+</button>
                        </div>
                        <span class="ms-3 text-muted">
                            <?php echo $product['stock_quantity']; ?> items available
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg add-to-cart" data-product-id="<?php echo $product_id; ?>">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        <button class="btn btn-outline-primary btn-lg add-to-wishlist <?php echo $in_wishlist ? 'active' : ''; ?>" data-product-id="<?php echo $product_id; ?>">
                            <i class="fas fa-heart"></i> <?php echo $in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Product Details</h5>
                    <ul class="list-unstyled">
                        <li><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></li>
                        <li><strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></li>
                        <li><strong>Availability:</strong> <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h3>Customer Reviews</h3>
                <div class="card">
                    <div class="card-body">
                        <?php if (count($reviews) > 0): ?>
                            <?php 
                            $reviewCount = count($reviews);
                            $i = 0;
                            foreach ($reviews as $review): 
                                $i++;
                            ?>
                                <div class="review mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($review['customer_name']); ?></h5>
                                            <div class="text-warning">
                                                <?php for (
                                                    $star = 1; $star <= 5; $star++): ?>
                                                    <i class="fas fa-star<?php echo $star <= $review['rating'] ? '' : '-o'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo date('F j, Y', strtotime($review['date_posted'])); ?>
                                        </small>
                                    </div>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                </div>
                                <?php if ($i < $reviewCount): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['customer_id'])): ?>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                Write a Review
                            </button>
                        <?php else: ?>
                            <p class="text-muted">Please <a href="login.php">login</a> to write a review.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                                    <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reviewText" class="form-label">Your Review</label>
                            <textarea class="form-control" id="reviewText" rows="4" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitReview">Submit Review</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <script>
        // Quantity increment/decrement
        document.getElementById('decrement').addEventListener('click', function() {
            const input = document.getElementById('quantity');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });

        document.getElementById('increment').addEventListener('click', function() {
            const input = document.getElementById('quantity');
            if (input.value < <?php echo $product['stock_quantity']; ?>) {
                input.value = parseInt(input.value) + 1;
            }
        });

        // Submit review
        document.getElementById('submitReview').addEventListener('click', function() {
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const reviewText = document.getElementById('reviewText').value;

            if (!rating || !reviewText) {
                alert('Please fill in all fields');
                return;
            }

            fetch('api/reviews/add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: <?php echo $product_id; ?>,
                    rating: rating,
                    review_text: reviewText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error submitting review');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting review');
            });
        });

        // Wishlist button functionality
        document.querySelector('.wishlist-btn').addEventListener('click', function() {
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
                    this.classList.remove('btn-outline-danger');
                    this.classList.add('btn-danger');
                    this.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
                    showToast('Product added to wishlist', 'success');
                } else {
                    showToast(data.message || 'Error adding to wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to wishlist', 'error');
            });
        });
    </script>
</body>
</html> 