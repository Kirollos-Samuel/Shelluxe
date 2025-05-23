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
$stmt = $conn->prepare('SELECT customer_name, email, contact_add FROM Customer WHERE customer_ID = ?');
$stmt->execute([$_SESSION['customer_id']]);
$user = $stmt->fetch();

include 'includes/header.php';
?>
<div class="hero-section bg-primary text-white py-5 mb-4">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-2">My Profile</h1>
        <p class="lead">Manage your account information</p>
    </div>
</div>
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 animate-fade-in">
                <div class="card-body p-5">
                    <h2 class="h4 mb-4">Profile Details</h2>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($user['customer_name']); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                        <li class="list-group-item"><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact_add']); ?></li>
                    </ul>
                    <a href="edit-profile.php" class="btn btn-outline-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?> 