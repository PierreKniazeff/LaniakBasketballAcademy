<?php
session_start(); // Démarre la session au tout début du script
require_once("views/common/header.php");

// Définition des messages de succès et d'erreur
$successMessage = isset($_GET['success']) && $_GET['success'] == 1 ? "Connexion réussie!" : "";
$errorMessage = isset($_GET['error']) && $_GET['error'] == 1 ? "Identifiants incorrects. Veuillez réessayer." : "";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>

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
    <h2 class="mb-4">Formulaire de Connexion</h2>
    
    <!-- Afficher le message de succès -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= $successMessage ?></div>
    <?php endif; ?>
    
    <!-- Afficher le message d'erreur -->
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php endif; ?>
    
    <form action="controllers/login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control border-dark" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control border-dark" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
        <a href="password_recovery.php" class="btn btn-secondary">Mot de passe oublié</a>
    </form>
</div>

<?php require_once("views/common/footer.php"); ?>

</body>
</html>
