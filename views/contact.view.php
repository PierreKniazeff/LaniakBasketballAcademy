<link href="public/css/.css" rel="stylesheet">

<?php
require_once "controllers/db.php";

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

// Suite au succès de l'insertion dans la base de données
if ($messageSent) {
    // Valider les données pour éviter les problèmes potentiels
    $nom = validateInput($nom); 
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sujet = validateInput($sujet);
    $message = validateInput($message);

    // Composer le contenu structuré de l'e-mail
    $emailContent = "Nouveau message via le formulaire de contact :\n\n";
    $emailContent .= "Nom: " . $nom . "\n";
    $emailContent .= "Email: " . $email . "\n";
    $emailContent .= "Sujet: " . $sujet . "\n";
    $emailContent .= "Message: " . $message;

    // Envoyer l'e-mail uniquement si les données sont valides
    if (!empty($nom) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($sujet) && !empty($message)) {
        $to = "laniak@levelnext.fr";
        // $to = "laniakbasketballacademy@gmail.com";
        $subject = "Nouveau Message de Contact Form Laniakbballacemy";
        $headers = "From: laniak@levelnext.fr";

        // Vérifier si l'e-mail est envoyé avec succès
        if (mail($to, $subject, $emailContent, $headers)) {
            echo "L'E-mail a été envoyé avec succès.";
        } else {
            echo "Erreur lors de l'envoi de l'E-mail. Veuillez réessayer ultérieurement.";
        }
    } else {
        echo "Les données du formulaire ne sont pas valides. Veuillez vérifier et réessayer.";
    }
}



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
       

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px auto;
            max-width: 960px;
            /* Largeur maximale du conteneur */
        }

        .contact-form {
            background-color: #CFD8DC ;
            /* Couleur pour le fond du formulaire */
            flex: 1;
            /* Flexibilité pour la mise en page responsive */
            margin: 20px;
            padding: 20px;
            border: 2px solid black;
            /* Bordure bleue autour du formulaire */
            border-radius: 10px;
            /* Arrondir les coins de la bordure */
        }

        .contact-info {
            flex: 1;
            /* Flexibilité pour la mise en page responsive */
            margin: 20px;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* footer {
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            width: 100%;
        } */
    </style>
</head>

<body>

    <div class="container">
    <div class="contact-form">
        <h1>CONTACT FORM</h1><br><br>

        <?php if ($messageSent): ?>
            <p class="message-success"><strong>Message envoyé avec succès!</strong></p>
        <?php else: ?>
            <?php if (!empty($errorMessage)): ?>
                <p class="message-error">
                    <?php echo $errorMessage; ?>
                </p>
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
                    <label for="message">
                    <textarea name="message" id="message" cols="30" rows="10" placeholder="Votre message ici"></textarea>
                    </label>
                </p>
                <button type="submit" name="pierre">Envoyer</button>
            </form><br><br>
            <strong>Les données que vous fournissez seront utilisées uniquement dans le but de personnaliser 
                les programmes d'entraînement et d'améliorer votre expérience avec notre service, 
                conformément à l'article 6 du RGPD, qui traite de la licéité du traitement des données 
                (cf. <a href="https://levelnext.fr/views/common/MentionsLegales.php">mention légale ici</a>).</strong>
        <?php endif; ?>
    </div>

    <div class="contact-info">
        <h1>CONTACT</h1><br>
        <p class="roboto-font text-justify" style="max-width: 100%;"><br><strong>MODIBO NIAKATE</strong><br><br>
            <!-- Téléphone : <a href="tel:+33658977895">+336 58 97 78 95</a><br><br> -->
            Email: <a href="mailto:laniakbballacademy@gmail.com">laniakbasketballacademy@gmail.com</a><br><br>
            <!-- INSTAGRAM : <a href="https://www.instagram.com/laniakbasketballacademy">@laniakbasketballacademy</a><br><br> -->
        </p>
    </div>
    </div>

    <footer>
        <?php require_once "views/common/footer.php"; ?>
    </footer>


</body>

</html>