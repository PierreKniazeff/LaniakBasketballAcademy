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

// After a successful insertion into the database
if ($messageSent) {
    // Validate the data to avoid potential issues
    $nom = validateInput($nom); 
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $sujet = validateInput($sujet);
    $message = validateInput($message);

    // Compose the structured email content
    $emailContent = "New message via the contact form:\n\n";
    $emailContent .= "Name: " . $nom . "\n";
    $emailContent .= "Email: " . $email . "\n";
    $emailContent .= "Subject: " . $sujet . "\n";
    $emailContent .= "Message: " . $message;

    // Send the email only if the data is valid
    if (!empty($nom) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($sujet) && !empty($message)) {
        $to = "laniak@levelnext.fr";
        // $to = "laniakbasketballacademy@gmail.com";
        $subject = "New Message from Laniak Basketball Academy Contact Form";
        $headers = "From: laniak@levelnext.fr";

        // Check if the email is sent successfully
        if (mail($to, $subject, $emailContent, $headers)) {
            echo "The email has been sent successfully.";
        } else {
            echo "Error sending the email. Please try again later.";
        }
    } else {
        echo "The form data is not valid. Please check and try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

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
            max-width: 960px; /* Maximum width of the container */
        }

        .contact-form {
            background-color: #CFD8DC; /* Color for the form background */
            flex: 1; /* Flexibility for responsive layout */
            margin: 20px;
            padding: 20px;
            border: 2px solid black; /* Black border around the form */
            border-radius: 10px; /* Round the corners of the border */
        }

        .contact-info {
            flex: 1; /* Flexibility for responsive layout */
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
    </style>
</head>

<body>

    <div class="container">
        <div class="contact-form">
        <h1>CONTACT FORM</h1><br><br>

<?php if ($messageSent): ?>
    <p class="message-success"><strong>Message sent successfully!</strong></p>
<?php else: ?>
    <?php if (!empty($errorMessage)): ?>
        <p class="message-error">
            <?php echo $errorMessage; ?>
        </p>
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
    <strong>The information you provide will be used solely to personalize training programs and enhance your experience with our service, in accordance with Article 6 of the GDPR, which addresses the legality of data processing (see <a href="https://levelnext.fr/views/common/MentionsLegales.php">legal notice here</a>).</strong>
<?php endif; ?>
</div>

<div class="contact-info">
<h1>CONTACT</h1><br>
<p class="roboto-font text-justify" style="max-width: 100%;"><br><strong>MODIBO NIAKATE</strong><br><br>
    Email: <a href="mailto:laniakbballacademy@gmail.com">laniakbasketballacademy@gmail.com</a><br><br>
</p>
</div>
</div>

<footer>
<?php require_once "views/common/footer.php"; ?>
</footer>
</body>

</html>

