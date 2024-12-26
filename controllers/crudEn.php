<?php

// Retrieve the path to the .env file
$envFilePath = __DIR__ . '/../.env';

// Check if the .env file exists
if (file_exists($envFilePath)) {
    // Read the content of the .env file
    $envContent = file_get_contents($envFilePath);

    // Separate the lines of the .env file
    $envLines = explode("\n", $envContent);

    // Loop through each line to extract environment variables
    foreach ($envLines as $line) {
        // Ignore empty lines and comments
        if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            // Split the key and value of the variable
            list($key, $value) = explode('=', $line, 2);

            // Trim spaces from the beginning and end of the key and value
            $key = trim($key);
            $value = trim($value);

            // Set the environment variable if it is not already defined
            if (!isset($_ENV[$key]) && !isset($_SERVER[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
} else {
    // Handle the case where the .env file does not exist
    die('.env file not found.');
}

require_once __DIR__ . '/../models/User.class.php';
// require_once __DIR__ . '/../vendor/autoload.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PhpParser\Node\Name;

// $mailHost = $_ENV['SMTP_HOST'];
// $mailUsername = $_ENV['SMTP_USER'];
// $mailPassword = $_ENV['SMTP_PASS'];
// $mailPort = $_ENV['SMTP_PORT'];

class CRUD
{
    private $pdo;

    public function __construct()
    {
        $config = require_once __DIR__ . '/../config/database.php';
        $this->pdo = new PDO(
            "mysql:host=" . $config['host'] . ";dbname=" . $config['database'],
            $config['user'],
            $config['password'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscription WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function createUser(User $user)
    {
        // Initialize the associative array for the return message
        $result = array();

        // Validate the email address
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "The email address is not valid.";
            $result['class'] = "error";
            return $result;
        }

        // Check if the email already exists
        if ($this->emailExists($user->getEmail())) {
            $result['message'] = "The entered email is already in use. Please choose another one.";
            $result['class'] = "error";
            return $result;
        }

        // Insert data into the database
        try {
            // Generate and temporarily store the authentication token
            $token = bin2hex(random_bytes(16));
            $user->setToken($token);

            // Set token_expiration to 15 minutes from now
            $tokenExpiration = new DateTime(); // Current time
            $tokenExpiration->add(new DateInterval('PT15M')); // Add 15 minutes

            $stmt = $this->pdo->prepare("INSERT INTO inscription (prenom, nom, email, tel,
            date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, 
            password, created_at, confirmed, token, token_expiration) 
            VALUES (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, 
            :niveau_championnat, :poste, :objectifs, :password, NOW(), :confirmed, :token, :token_expiration)");

            // In addition to other parameters already bound
            $tokenExpirationFormatted = $tokenExpiration->format('Y-m-d H:i:s');
            $stmt->bindParam(':token_expiration', $tokenExpirationFormatted);

            // Hash the password before inserting it into the database
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

            // Here, you need to define the variables before passing them to bindParam()
            $prenom = $user->getPrenom();
            $nom = $user->getNom();
            $email = $user->getEmail();
            $tel = $user->getTel();
            $date_naissance = $user->getDateNaissance();
            $genre = $user->getGenre();
            $taille = $user->getTaille();
            $poids = $user->getPoids();
            $club = $user->getClub();
            $niveau_championnat = $user->getNiveauChampionnat();
            $poste = $user->getPoste();
            $objectifs = $user->getObjectifs();
            $confirmed = 0; // Assuming the user is not confirmed at registration

            // Bind the parameters
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':taille', $taille);
            $stmt->bindParam(':poids', $poids);
            $stmt->bindParam(':club', $club);
            $stmt->bindParam(':niveau_championnat', $niveau_championnat);
            $stmt->bindParam(':poste', $poste);
            $stmt->bindParam(':objectifs', $objectifs);
            $stmt->bindParam(':password', $hashedPassword); // Use the hashed password
            $stmt->bindParam(':confirmed', $confirmed);
            $stmt->bindParam(':token', $token);

            $stmt->execute();

            // Send confirmation email with the verification link
            $this->sendVerificationEmail($user);

            // Send the confirmation email
            $this->sendConfirmationEmail($user);

            // Store the user's email in the session
            $_SESSION['user_email'] = $user->getEmail();

            $result['message'] = "Please complete your registration via the confirmation email sent to you.";
            $result['class'] = "error";
            return $result;
        } catch (PDOException $e) {
            $result['message'] = "An error occurred during registration: please contact laniak@levelnext.fr " . $e->getMessage();
            $result['class'] = "error";
            return $result;
        }
    }

    // public function sendVerificationEmail($user)
    // {
    //     $result = array();

    //     try {
    //         $mail = new PHPMailer(true); // Enable exceptions
    //         $mail->CharSet = 'UTF-8'; // Set the character set to UTF-8

    //         // Enable SMTP debugging
    //         $mail->SMTPDebug = 0; // 0 = off, 1 = errors, 2 = detailed

    //         // SMTP configuration for Hotmail/Outlook
    //         $mail->isSMTP();
    //         $mail->Host = $_ENV['SMTP_HOST']; // SMTP server for Hotmail/Outlook
    //         $mail->SMTPAuth = true;
    //         $mail->Username = $_ENV['SMTP_USER']; // Your Hotmail/Outlook email address
    //         $mail->Password = $_ENV['SMTP_PASS']; // Password for your email address
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
    //         $mail->Port = $_ENV['SMTP_PORT']; // SMTP port for Hotmail/Outlook

    //         // Sender and recipient settings
    //         $mail->setFrom($_ENV['SMTP_USER'], 'Laniak Basketball Academy');
    //         $mail->addAddress($user->getEmail()); // Send the email to the user's address

    //         // Email content
    //         $mail->isHTML(true); // Set the email format to HTML
    //         $mail->Subject = 'Registration Confirmation';
    //         $mail->Body = "
    //                     <p>Thank you for signing up! Please confirm your email address by clicking the following link:</p>
    //                     <p><a href='http://levelnext.fr/views/confirmationEn.view.php?token=" . urlencode($user->getToken()) . "&email=" . urlencode($user->getEmail()) . "'>Confirm Registration</a></p>";

    //         $mail->send();

    //         $result['message'] = "The form has been successfully submitted. An email containing a verification code has been sent to you. Please check your inbox and click the button below to enter the verification code and confirm your registration.";
    //         $result['class'] = "success";

    //         return $result;
    //     } catch (Exception $e) {
    //         $result['message'] = "An error occurred while sending the confirmation email. Please try again.";
    //         $result['class'] = "error";
    //         return $result;
    //     }
    // }

    public function sendVerificationEmail($user)
{
    $result = array();

    // Define the recipient
    $to = $user->getEmail();
    $subject = 'Email Verification';

    // Email content
    $message = "
    <p>Thank you for signing up! Please confirm your email address by clicking the following link:</p>
    <p><a href='http://levelnext.fr/views/confirmationEn.view.php?token=" . urlencode($user->getToken()) . "&email=" . urlencode($user->getEmail()) . "'>Confirm Registration</a></p>";

    // Email headers
    $headers = 'From: laniak@levelnext.fr' . "\r\n" . // Replace with your email address
               'Reply-To: laniak@levelnext.fr' . "\r\n" . // Replace with your email address
               'MIME-Version: 1.0' . "\r\n" . // MIME version
               'Content-type:text/html;charset=UTF-8' . "\r\n"; // Define content type

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        $result['message'] = "The verification email has been sent successfully.";
        $result['class'] = "success";
    } else {
        $result['message'] = "An error occurred while sending the verification email. Please try again.";
        $result['class'] = "error";
    }

    return $result;
}


    public function deleteUser($userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function confirmUserByToken($token, $email) // Add a parameter for email
    {
        try {
            // Check if the token exists in the database for the user corresponding to the email
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE token = :token AND email = :email");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the token has not expired
                $currentDateTime = new DateTime();
                $tokenExpiration = new DateTime($user['token_expiration']);
                if ($currentDateTime < $tokenExpiration) {
                    // Update the 'confirmed' column in the database to mark the user as confirmed
                    $stmt = $this->pdo->prepare("UPDATE inscription SET confirmed = 1 WHERE token = :token AND email = :email");
                    $stmt->bindParam(':token', $token);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    return array('success' => true);
                } else {
                    return array('success' => false, 'message' => 'The confirmation link has expired.');
                }
            } else {
                return array('success' => false, 'message' => 'Invalid token or does not match the email.');
            }
        } catch (PDOException $e) {
            return array('success' => false, 'message' => 'Error while confirming the user: ' . $e->getMessage());
        }
    }

    public function deleteExpiredUsers()
    {
        try {
            // Calculate the current date and time
            $currentDateTime = new DateTime();

            // Calculate the date and time when the tokens will expire (15 minutes before the current time)
            $tokenExpiration = clone $currentDateTime;
            $tokenExpiration->sub(new DateInterval('PT15M')); // Subtract 15 minutes

            // Delete unconfirmed users whose token has expired
            $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE confirmed = 0 AND token_expiration < :tokenExpiration");
            $formattedTokenExpiration = $tokenExpiration->format('Y-m-d H:i:s'); // Bind the parameter with the formatted value
            $stmt->bindParam(':tokenExpiration', $formattedTokenExpiration, PDO::PARAM_STR);

            $stmt->execute();

            $expiredUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Delete profiles of expired users
            foreach ($expiredUsers as $user) {
                $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE id = :id");
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            // Handle the error if deletion fails
            echo "Error while deleting expired users: " . $e->getMessage();
        }
    }

    public function sendConfirmationEmail($user)
    {
        $result = array();

        // Set the recipients
        $to = 'kniazeff.pierre@hotmail.fr, laniakbasketballacademy@gmail.com';
        $subject = 'New Registration';

        // Email content
        $message = "
                    <p>Hello,<p>
                    <p>A new registration has been confirmed on your site. Here are the details:</p>
                    First Name: {$user->getPrenom()}<br>
                    Last Name: {$user->getNom()}<br>
                    Email: {$user->getEmail()}<br>
                    Phone: {$user->getTel()}<br>
                    Date of Birth: {$user->getDateNaissance()}<br>
                    Gender: {$user->getGenre()}<br>
                    Height: {$user->getTaille()}<br>
                    Weight: {$user->getPoids()}<br>
                    Team: {$user->getClub()}<br>
                    Championship Level: {$user->getNiveauChampionnat()}<br>
                    Position: {$user->getPoste()}<br>
                    Goals: {$user->getObjectifs()}<br>
                    <p>Thank you.</p>
                     ";

        // Email headers
        $headers = 'From: laniak@levelnext.fr' . "\r\n" . // Replace with your email address
            'Reply-To: laniak@levelnext.fr' . "\r\n" . // Replace with your email address
            'MIME-Version: 1.0' . "\r\n" . // MIME version
            'Content-type:text/html;charset=UTF-8' . "\r\n"; // Set content type

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            $result['message'] = "The confirmation email has been successfully sent.";
            $result['class'] = "success";
        } else {
            $result['message'] = "Your player profile could not be sent to LaniakBasketballAcademy. Please contact laniak@levelnext.fr directly.";
            $result['class'] = "error";
        }

        return $result;
    }
}

// CSS Styles
echo "
<style>
.error, .success {
    padding: 10px;
    border-radius: 5px;
    font-weight: bold;
    margin-top: 20px;
}

.error {
    background-color: #ffcccc;
    color: #cc0000;
}

.success {
    background-color: #ccffcc;
    color: #006600;
}
</style>";
