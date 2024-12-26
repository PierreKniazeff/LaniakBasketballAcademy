<?php
$page_title = 'password_modification'; // Define the variable for menu.php
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
        case 'reset_password':
            // Password reset
            $resetToken = $_POST['token'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            $controller->resetPassword($resetToken, $newPassword, $confirmPassword);
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="public/css/.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Modification</title>

    <!-- CSS Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5;
        }

        .invalid {
            color: red;
        }

        .valid {
            color: green;
        }
    </style>
</head>

<body>
    <div class="container">
        <br><br><h2 class="mb-4">Modify Your Password</h2>

        <!-- Display success and error messages -->
        <?php $successMessage = $controller->getSuccessMessage(); ?>
        <?php if ($successMessage) : ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php $errorMessage = $controller->getErrorMessage(); ?>
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <form action="password_modificationEn.view.php" method="POST">
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="form-control border-dark" required>
                <span toggle="#new_password" class="fa fa-fw fa-eye field-icon"></span><br>
                <!-- Password criteria instructions added here -->
                <div id="passwordCriteria" style="margin-top: 10px;">
                    Your password must contain:
                    <ul>
                        <li id="length" class="invalid">At least 8 characters</li>
                        <li id="uppercase" class="invalid">One uppercase letter</li>
                        <li id="number" class="invalid">One digit</li>
                        <li id="special" class="invalid">One special character (e.g., !, @, #)</li>
                    </ul>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control border-dark" required>
                <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon"></span>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <!-- JavaScript Script -->
    <script>
        document.getElementById('new_password').addEventListener('input', function() {
            const criteria = {
                length: document.getElementById('length'),
                uppercase: document.getElementById('uppercase'),
                number: document.getElementById('number'),
                special: document.getElementById('special')
            };

            const val = this.value;
            criteria.length.className = val.length >= 8 ? 'valid' : 'invalid';
            criteria.uppercase.className = /[A-Z]/.test(val) ? 'valid' : 'invalid';
            criteria.number.className = /[0-9]/.test(val) ? 'valid' : 'invalid';
            criteria.special.className = /[^\w]/.test(val) ? 'valid' : 'invalid';
        });

        $(document).ready(function() {
            $('.field-icon').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                var $input = $($this.attr('toggle'));

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $this.removeClass('fa-eye');
                    $this.addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $this.removeClass('fa-eye-slash');
                    $this.addClass('fa-eye');
                }
            });
        });
    </script>
</body>

</html>

<?php
require_once __DIR__ . '/../views/common/footer.php';
?>

