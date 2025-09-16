<?php
// Sécurité : empêcher l’accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}

// Inclusion BDD robuste
require_once __DIR__ . '/../controllers/db.php';

// Initialiser DB
$database = new db();
$database->connect();

function validateInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$messageSent = false;
$errorMessage = "";

if (isset($_POST['pierre'])) {
    $nom = validateInput($_POST['nom']);
    $email = validateInput($_POST['email']);
    $sujet = validateInput($_POST['sujet']);
    $message = validateInput($_POST['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Email invalide";
    } else {
        $query = "INSERT INTO `contact form` (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
        $params = [':nom' => $nom, ':email' => $email, ':sujet' => $sujet, ':message' => $message];

        try {
            $database->executeQuery($query, $params);
            $messageSent = true;
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de l'envoi: " . $e->getMessage();
        }
    }
}

if ($messageSent) {
    $nom = validateInput($nom);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sujet = validateInput($sujet);
    $message = validateInput($message);

    $emailContent = "Nouveau message via le formulaire de contact :\n\n";
    $emailContent .= "Nom: " . $nom . "\n";
    $emailContent .= "Email: " . $email . "\n";
    $emailContent .= "Sujet: " . $sujet . "\n";
    $emailContent .= "Message: " . $message;
    $to = "laniak@levelnext.fr";
    $subject = "Nouveau Message de Contact Form Laniakbballacemy";
    $headers = "From: laniak@levelnext.fr";

    if (mail($to, $subject, $emailContent, $headers)) {
        $mailSuccess = true;
    } else {
        $mailSuccess = false;
        $mailError = "Erreur lors de l'envoi de l'E-mail. Veuillez réessayer ultérieurement.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link href="public/css/.css" rel="stylesheet"> <!-- Adapter le nom réel si besoin -->
     <style>
        .container { display: flex; flex-wrap: wrap; justify-content: center; margin: 20px auto; max-width: 960px; }
        .contact-form {
            background-color: #CFD8DC;
            flex: 1; margin: 20px; padding: 20px;
            border: 2px solid black; border-radius: 10px;
        }
        .contact-info { flex: 1; margin: 20px; }
        input[type="text"], input[type="email"], textarea, button[type="submit"] {
            width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 20px; border-radius: 5px;
            border: 1px solid #ccc; box-sizing: border-box;
        }
        button[type="submit"] { background-color: #007bff; color: #fff; border: none; cursor: pointer; }
        button[type="submit"]:hover { background-color: #0056b3; }
        .message-success { color: green; font-weight: bold; }
        .message-error { color: #cc0000; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-form">
            <h1>CONTACT FORM</h1><br><br>
            <?php if ($messageSent): ?>
                <?php if (isset($mailSuccess) && $mailSuccess): ?>
                    <p class="message-success"><strong>Message envoyé avec succès!</strong></p>
                <?php else: ?>
                    <p class="message-error"><?= $mailError ?? 'Erreur inattendue lors de l\'envoi.' ?></p>
                <?php endif; ?>
            <?php else: ?>
                <?php if (!empty($errorMessage)): ?>
                    <p class="message-error"><?= $errorMessage; ?></p>
                <?php endif; ?>

                <form action="" method="post">
                    <p>
                        <label for="nom">Nom:</label>
                        <input type="text" name="nom" id="nom" placeholder="Votre nom ici" required>
                    </p>
                    <p>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="Votre Email ici" required>
                    </p>
                    <p>
                        <label for="sujet">Sujet:</label>
                        <input type="text" name="sujet" id="sujet" placeholder="Votre sujet ici">
                    </p>
                    <p>
                        <label for="message"></label>
                        <textarea name="message" id="message" cols="30" rows="10" placeholder="Votre message ici"></textarea>
                    </p>
                    <button type="submit" name="pierre">Envoyer</button>
                </form><br><br>
                <strong>Les données fournies sont utilisées uniquement pour personnaliser les programmes d'entraînement et améliorer votre expérience, conformément à l'article 6 du RGPD
                (<a href="index.php?page=MentionsLegales">mention légale ici</a>).</strong>
            <?php endif; ?>
        </div>
        <div class="contact-info">
            <h1>CONTACT</h1><br>
            <p class="roboto-font text-justify" style="max-width: 100%;">
                <br><strong>MODIBO NIAKATE</strong><br><br>
                Email: <a href="mailto:laniakbasketballacademy@gmail.com">laniakbasketballacademy@gmail.com</a><br><br>
            </p>
        </div>
    </div>
    <footer>
        <?php include_once __DIR__ . '/common/footer.php'; ?>
    </footer>
</body>
</html>
