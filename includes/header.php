<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;
$wishlist_count = 0;
if (isset($_SESSION['customer_id'])) {
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('SELECT COUNT(*) FROM Cart WHERE customer_ID = ?');
    $stmt->execute([$_SESSION['customer_id']]);
    $cart_count = $stmt->fetchColumn();
    $stmt = $conn->prepare('SELECT COUNT(*) FROM Wishlist WHERE customer_ID = ?');
    $stmt->execute([$_SESSION['customer_id']]);
    $wishlist_count = $stmt->fetchColumn();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <h1 class="h4 mb-0">Shelluxe</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex mx-auto" style="width: 50%;" action="search.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search for bracelets..." aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="search-suggestions position-absolute bg-white shadow-sm rounded mt-1" style="display: none; width: 50%; z-index: 1000;"></div>
            </form>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="wishlist.php">
                        <i class="fas fa-heart"></i> Wishlist
                        <?php if(isset($_SESSION['customer_id'])): ?>
                            <span class="badge bg-primary wishlist-count" id="wishlist-count"><?php echo $wishlist_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php if(isset($_SESSION['customer_id'])): ?>
                            <span class="badge bg-primary cart-count" id="cart-count"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if(isset($_SESSION['customer_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="orders.php">Orders</a></li>
                            <li><a class="dropdown-item" href="addresses.php">Addresses</a></li>
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

<!-- Categories Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#categoryNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="categoryNav">
            <ul class="navbar-nav">
                <?php
                require_once 'config/database.php';
                $db = new Database();
                $conn = $db->getConnection();
                $stmt = $conn->query("SELECT * FROM Category ORDER BY category_name");
                while($category = $stmt->fetch()) {
                    echo '<li class="nav-item">
                        <a class="nav-link" href="category.php?id=' . $category['category_ID'] . '">' . 
                        htmlspecialchars($category['category_name']) . '</a>
                    </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3"></div> 