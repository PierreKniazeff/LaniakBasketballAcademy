<?php
// Security: Prevent direct access to the view
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
$page_title = 'password_recoveryEn';
require_once __DIR__ . '/../views/common/menu.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../controllers/PasswordRecoveryControllerEn.php';

// Instantiate the controller
$controller = new PasswordRecoveryController();

// Handle action if POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? null;
    switch ($action) {
        case 'forgot_password':
            $email = $_POST['email'];
            $controller->demandeMotDePasseOublie($email);
            break;
        default:
            echo "<div class='alert alert-danger'>Invalid action.</div>";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Recovery</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0; padding: 0;
            background-color: #F5F5F5;
        }
    </style>
</head>
<body>
    <div class="container">
        <br><br><h2 class="mb-4">Password Reset Request</h2>
        <!-- Display success and error messages -->
        <?php $successMessage = $controller->getSuccessMessage(); ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php $errorMessage = $controller->getErrorMessage(); ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        <form action="index.php?page=password_recoveryEn" method="POST">
            <input type="hidden" name="action" value="forgot_password">
            <div class="mb-3">
                <label for="email" class="form-label">Email address:</label>
                <input type="email" id="email" name="email" class="form-control border-dark" required>
            </div>
            <button type="submit" class="btn btn-primary">Send password reset link</button>
        </form>
    </div>
    <footer>
        <div class="container-fluid">
            <?php include_once __DIR__ . '/common/footer.php'; ?>
        </div>
    </footer>
</body>
</html>
