<?php
// Sécurité : empêcher l’accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
$page_title = 'password_recovery';
require_once __DIR__ . '/../views/common/menu.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../controllers/PasswordRecoveryController.php';

// Instancier le contrôleur
$controller = new PasswordRecoveryController();

// Traiter l'action si POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? null;
    switch ($action) {
        case 'forgot_password':
            $email = $_POST['email'];
            $controller->demandeMotDePasseOublie($email);
            break;
        default:
            echo "<div class='alert alert-danger'>Action non valide.</div>";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Récupération de mot de passe</title>
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
        <br><br><h2 class="mb-4">Demande de réinitialisation de votre mot de passe</h2>
        <!-- Afficher les messages de succès et d'erreur -->
        <?php $successMessage = $controller->getSuccessMessage(); ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php $errorMessage = $controller->getErrorMessage(); ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        <form action="index.php?page=password_recovery" method="POST">
            <input type="hidden" name="action" value="forgot_password">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email :</label>
                <input type="email" id="email" name="email" class="form-control border-dark" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
        </form>
    </div>
    <footer>
        <div class="container-fluid">
            <?php include_once __DIR__ . '/common/footer.php'; ?>
        </div>
    </footer>
</body>
</html>
