<link href="public/css/.css" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
$page_title = "connexion";
$page_description = "page connexion";
require_once("views/common/header.php");
?>

<div class="container">
    <h2 class="mb-4">Formulaire de Connexion</h2>
    <form action="path-to-your-form-handler" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control border-dark" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control border-dark" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
        <button type="submit" class="btn btn-secondary">Mot de passe oublié</button>
    </form>
</div>

<style>
   body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #F5F5F5;
    /* Blanc pour le fond de la page */
} 
</style>

<?php
require_once("views/common/footer.php");
?>
