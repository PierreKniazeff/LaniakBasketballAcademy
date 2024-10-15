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

// Vérifiez la valeur du token et de l'email reçu
$token = $_REQUEST['token']; // Utilisez $_REQUEST pour récupérer le token
$email = $_REQUEST['email']; // Récupération de l'email passé dans l'URL

// Vérifiez si le token et l'email sont présents dans l'URL
if (isset($token) && !empty($token) && isset($email) && !empty($email)) {
    // Confirmer l'utilisateur en utilisant le token
    $result = $db->confirmUserByToken($token, $email); // Passez également l'email
    // Vérifiez le résultat
    if ($result['success']) {
        echo "<div class='success'>Félicitations ! Votre inscription est confirmée.</div>";
        echo "<div class='success'>Votre profil joueur a bien été soumis à LaniakBasketballAcademy.</div>";
    } else {
        echo "<div class='error'>{$result['message']}</div>";
    }
} else {
    echo "<div class='error'>Aucun token valide fourni.</div>";
}

?>
</div>
<?php require_once __DIR__ . '/../views/common/footer.php'; ?>
