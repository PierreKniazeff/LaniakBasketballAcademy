<?php
// Sécurité : protection contre l'accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}

// Initialisation CRUD anglais (modifier selon le nom réel du contrôleur anglais)
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../controllers/crudEn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Confirmation</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0; padding: 0;
            background-color: #F5F5F5;
        }
        .success, .error {
            padding: 12px;
            margin: 30px auto;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
            max-width: 450px;
        }
        .success {
            background-color: #ccffcc;
            color: #006600;
            border: 1px solid #b6e6b6;
        }
        .error {
            background-color: #ffcccc;
            color: #cc0000;
            border: 1px solid #e6b6b6;
        }
    </style>
</head>
<body>
<?php
$db = new CRUD();
$token = $_REQUEST['token'] ?? null;
$email = $_REQUEST['email'] ?? null;
if (!empty($token) && !empty($email)) {
    $result = $db->confirmUserByToken($token, $email);
    if ($result['success']) {
        echo "<div class='success'>Congratulations! Your registration is confirmed.</div>";
        echo "<div class='success'>Your player profile has been successfully submitted to LaniakBasketballAcademy.</div>";
    } else {
        echo "<div class='error'>" . htmlspecialchars($result['message']) . "</div>";
    }
} else {
    echo "<div class='error'>No valid token provided.</div>";
}
?>
<footer>
    <div class="container-fluid">
        <?php include_once __DIR__ . '/common/footer.php'; ?>
    </div>
</footer>
</body>
</html>
