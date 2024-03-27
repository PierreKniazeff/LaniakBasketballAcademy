<?php
$page_title = 'password_recovery'; // Définition de la variable pour menu.php
// require_once(__DIR__ . '/../views/common/header.php');
require_once(__DIR__ . '/../views/common/menu.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier du contrôleur
require_once '../controllers/PasswordRecoveryController.php';

// Instancier le contrôleur
$controller = new PasswordRecoveryController();

// Vérifier s'il y a une action à effectuer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de l'action depuis le formulaire
    $action = $_POST['action'] ?? null;

    // Exécuter l'action appropriée en fonction du formulaire soumis
    switch ($action) {
        case 'forgot_password':
            // Demande de réinitialisation de mot de passe
            $email = $_POST['email'];
            $controller->demandeMotDePasseOublie($email);
            break;
        default:
            // Action non reconnue
            echo "Action non valide.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Récupération de mot de passe</title>

    <!-- Styles CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5; /* Blanc pour le fond de la page */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4">Demande de réinitialisation de votre mot de passe</h2>
        
        <!-- Afficher les messages de succès et d'erreur -->
        <?php $successMessage = $controller->getSuccessMessage(); ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        
        <?php $errorMessage = $controller->getErrorMessage(); ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        
        <form action="password_recovery.view.php" method="POST">
            <input type="hidden" name="action" value="forgot_password">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email :</label>
                <input type="email" id="email" name="email" class="form-control border-dark" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
        </form>
    </div>
</body>

</html>

<?php
require_once(__DIR__ . '/../views/common/footer.php');
?>
