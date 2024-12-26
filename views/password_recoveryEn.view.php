<?php
$page_title = 'password_recovery'; // Define the variable for menu.php
// require_once(__DIR__ . '/../views/common/header.php');
require_once __DIR__ . '/../views/common/menu.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the controller file
require_once '../controllers/PasswordRecoveryControllerEn.php';

// Instantiate the controller
$controller = new PasswordRecoveryController();

// Check if there is an action to perform
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the action from the form
    $action = $_POST['action'] ?? null;

    // Execute the appropriate action based on the submitted form
    switch ($action) {
        case 'forgot_password':
            // Password reset request
            $email = $_POST['email'];
            $controller->demandeMotDePasseOublie($email);
            break;
        default:
            // Unrecognized action
            echo "Invalid action.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Recovery</title>

    <!-- CSS Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5; /* Light gray for the page background */
        }
    </style>
</head>

<body>
    <div class="container">
    <br><br><h2 class="mb-4">Request to Reset Your Password</h2>
        
        <!-- Display success and error messages -->
        <?php $successMessage = $controller->getSuccessMessage(); ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        
        <?php $errorMessage = $controller->getErrorMessage(); ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        
        <form action="password_recoveryEn.view.php" method="POST">
            <input type="hidden" name="action" value="forgot_password">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" id="email" name="email" class="form-control border-dark" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
    </div>
</body>

</html>

<?php
require_once __DIR__ . '/../views/common/footer.php';
?>
