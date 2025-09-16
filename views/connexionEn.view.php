<?php
// Optional: Block direct access to the view (remove if not needed)
// if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
//     header("Location: /");
//     exit;
// }

// Success/error messages
$successMessage = isset($_GET['success']) && $_GET['success'] == 1 ? "Login successful!" : "";
$errorMessage = isset($_GET['error']) && $_GET['error'] == 1 ? "Incorrect credentials. Please try again." : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Login Form</h2>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <form action="controllers/loginEn.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control border-dark" required>
            </div>
            <button type="submit" class="btn btn-primary">Log in</button>
            <a href="index.php?page=password_recoveryEn" class="btn btn-secondary">Forgot Password</a>
        </form>
    </div>

    <?php require_once __DIR__ . '/../views/common/footer.php'; ?>
</body>
</html>
