<?php
// Sécurité accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}

// Si ce fichier reste une "vue de confirmation", la logique du token devrait se faire côté contrôleur,
// mais on fait l'inclusion ici pour garder compatibilité avec ton code actuel :
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../controllers/crud.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation inscription</title>
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
// Initialisation CRUD
$db = new CRUD();
$token = $_REQUEST['token'] ?? null;
$email = $_REQUEST['email'] ?? null;

// Vérification des paramètres
if (!empty($token) && !empty($email)) {
    $result = $db->confirmUserByToken($token, $email);
    if ($result['success']) {
        echo "<div class='success'>Félicitations&nbsp;! Votre inscription est confirmée.</div>";
        echo "<div class='success'>Votre profil joueur a bien été soumis à LaniakBasketballAcademy.</div>";
    } else {
        echo "<div class='error'>" . htmlspecialchars($result['message']) . "</div>";
    }
} else {
    echo "<div class='error'>Aucun token valide fourni.</div>";
}
?>
<footer>
    <div class="container-fluid">
        <?php include_once __DIR__ . '/common/footer.php'; ?>
    </div>
</footer>
</body>
</html>
