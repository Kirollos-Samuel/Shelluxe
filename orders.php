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

<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare('SELECT order_ID, order_date, status, total_amount FROM Orders WHERE customer_ID = ? ORDER BY order_date DESC');
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll();

include 'includes/header.php';
?>
<div class="hero-section bg-primary text-white py-5 mb-4">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-2">My Orders</h1>
        <p class="lead">View your order history and status</p>
    </div>
</div>
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 animate-fade-in">
                <div class="card-body p-5">
                    <h2 class="h4 mb-4">Order History</h2>
                    <?php if (empty($orders)): ?>
                        <p class="text-muted">You have not placed any orders yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['order_ID']; ?></td>
                                            <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                                            <td><span class="badge bg-<?php echo $order['status'] === 'Completed' ? 'success' : ($order['status'] === 'Pending' ? 'warning' : 'secondary'); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td><a href="order-details.php?id=<?php echo $order['order_ID']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?> 