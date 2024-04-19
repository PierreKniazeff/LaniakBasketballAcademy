<?php
$page_title = 'confirmation'; // Définition de la variable pour menu.php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/../views/common/header.php';
require_once __DIR__ . '/../views/common/menu.php' ;
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../controllers/crud.php';
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation inscription</title>

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
<div>
<?php
// Initialisation de l'objet CRUD
$db = new CRUD();

// Vérifiez la valeur du token reçu
$token = $_REQUEST['token']; // Utilisez $_REQUEST à la place de $_GET

// Vérifiez si le token est présent dans l'URL
if (isset($token) && !empty($token)) {
    // Confirmer l'utilisateur en utilisant le token
    $result = $db->confirmUserByToken($token);

    if ($result['success']) {
        echo "<div class='success'>Félicitations ! Votre inscription est confirmée.</div>";
        echo "<div class='success'>Votre profil joueur a bien été soumis à LaniakBasketballAcademy.</div>";
    } else {
        echo "<div class='error'>Le lien de confirmation est invalide ou a expiré.</div>";
    }
} else {
    echo "<div class='error'>Aucun token valide fourni.</div>";
}
?>
</div>
<?php require_once __DIR__ . '/../views/common/footer.php'; ?>
