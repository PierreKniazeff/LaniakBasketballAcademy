<?php

header('Content-Type: text/html; charset=UTF-8');
require_once("views/common/header.php");
require_once('models/User.class.php');
require_once('controllers/crud.php');

// Initialisation de l'objet CRUD
$db = new CRUD();

// Vérifiez la valeur du nouveau token reçu
$token = $_REQUEST['token']; // Utilisez $_REQUEST à la place de $_GET

var_dump($token); // Affichez la valeur du token pour vérification

// Vérifiez si le token est présent dans l'URL
if (isset($token) && !empty($token)) {

    // Confirmer l'utilisateur en utilisant le token
    // $result = $db->confirmUserByToken($token);

    if ($result['success']) {
        echo "<div class='success'>Félicitations ! Votre inscription est confirmée.</div>";
    } else {
        echo "<div class='error'>Le lien de confirmation est invalide ou a expiré.</div>";
    }
} else {
    echo "<div class='error'>Aucun token valide fourni.</div>";
}

?>
<?php require_once("views/common/footer.php"); ?>
