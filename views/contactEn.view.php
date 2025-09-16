<?php
// Sécurité : empêcher l’accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}

// Charger la base de données avec chemin robuste
require_once __DIR__ . '/../controllers/db.php';

// Créer l'instance DB
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
        $errorMessage = "Invalid email";
    } else {
        $query = "INSERT INTO `contact form` (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
        $params = [':nom' => $nom, ':email' => $email, ':sujet' => $sujet, ':message' => $message];

        try {
            $database->executeQuery($query, $params);
            $messageSent = true;
        } catch (PDOException $e) {
            $errorMessage = "Error during sending: " . $e->getMessage();
        }
    }
}

if ($messageSent) {
    $nom = validateInput($nom);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sujet = validateInput($sujet);
    $message = validateInput($message);

    $emailContent = "New message via the contact form:\n\n";
    $emailContent .= "Name: " . $nom . "\n";
    $emailContent .= "Email: " . $email . "\n";
    $emailContent .= "Subject: " . $sujet . "\n";
    $emailContent .= "Message: " . $message;
    $to = "laniak@levelnext.fr";
    $subject = "New Message from Laniak Basketball Academy Contact Form";
    $headers = "From: laniak@levelnext.fr";

    if (mail($to, $subject, $emailContent, $headers)) {
        $mailSuccess = true;
    } else {
        $mailSuccess = false;
        $mailError = "Error sending the email. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link href="public/css/.css" rel="stylesheet"> <!-- Replace with actual CSS file name -->
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
                    <p class="message-success"><strong>Message sent successfully!</strong></p>
                <?php else: ?>
                    <p class="message-error"><?= $mailError ?? 'Unexpected error during sending.' ?></p>
                <?php endif; ?>
            <?php else: ?>
                <?php if (!empty($errorMessage)): ?>
                    <p class="message-error"><?= $errorMessage; ?></p>
                <?php endif; ?>

                <form action="" method="post">
                    <p>
                        <label for="nom">Name:</label>
                        <input type="text" name="nom" id="nom" placeholder="Your name here" required>
                    </p>
                    <p>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="Your Email here" required>
                    </p>
                    <p>
                        <label for="sujet">Subject:</label>
                        <input type="text" name="sujet" id="sujet" placeholder="Your subject here">
                    </p>
                    <p>
                        <label for="message">Message:</label>
                        <textarea name="message" id="message" cols="30" rows="10" placeholder="Your message here"></textarea>
                    </p>
                    <button type="submit" name="pierre">Send</button>
                </form><br><br>
                <strong>The information you provide will be used solely to personalize training programs and enhance your experience with our service, in accordance with Article 6 of the GDPR (see <a href="index.php?page=MentionsLegalesEn">legal notice here</a>).</strong>
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
