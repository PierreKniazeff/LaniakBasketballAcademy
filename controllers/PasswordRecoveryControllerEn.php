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

// Include the database configuration file
$config = require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.class.php';
// require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class PasswordRecoveryController
{
    private $pdo;

    public function __construct()
    {
        global $config;
        try {
            $this->pdo = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['database'],
                $config['user'],
                $config['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle database connection errors appropriately
            die("Database connection error: " . $e->getMessage());
        }
    }

    public function getSuccessMessage()
    {
        if (isset($_SESSION['success_message'])) {
            $message = $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Remove the message from the session after retrieving it
            return $message;
        }
        return null;
    }

    // Method to get the error message
    public function getErrorMessage()
    {
        if (isset($_SESSION['error_message'])) {
            $message = $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Remove the message from the session after retrieving it
            return $message;
        }
        return null;
    }

    public function demandeMotDePasseOublie($email)
    {
        try {
            // Check if the email exists in the database
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Use the existing token if there is one for this user
                $resetToken = $user['reset_mdp_token'] ?? ''; // Use the existing token if it exists, otherwise initialize to an empty string

                // If no token exists for this user, generate a new one
                if (empty($resetToken)) {
                    $resetToken = bin2hex(random_bytes(32));
                    $expiration = date('Y-m-d H:i:s', strtotime('+3 minutes'));

                    // Update the token and its expiration date in the database for this user
                    $stmt = $this->pdo->prepare("UPDATE inscription SET reset_mdp_token = :reset_token, token_expiration = :expiration WHERE email = :email");
                    $stmt->execute(['reset_token' => $resetToken, 'expiration' => $expiration, 'email' => $email]);
                }

                // Send a reset email with the correct token
                $user['reset_token'] = $resetToken; // Ensure the token is passed in the user data
                $this->sendPasswordResetEmail($user);

                // Redirect to the password change page with a success message and the token
                $_SESSION['success_message'] = 'Password reset request sent successfully. Please check your email.';
                echo "<script>window.location.href = 'https://levelnext.fr/views/password_recoveryEn.view.php?success=1';</script>";
                exit();
            } else {
                // Redirect to the password recovery page with an error message
                $_SESSION['error_message'] = 'Email not found. Please check the entered email address.';
                echo "<script>window.location.href = 'https://levelnext.fr/views/password_recoveryEn.view.php?error=1';</script>";
                exit();
            }
        } catch (PDOException $e) {
            // Handle database errors appropriately
            die("Database error: " . $e->getMessage());
        }
    }

    public function resetPassword($resetToken, $newPassword, $confirmPassword)
    {
        try {
            // Check if the token exists in the database
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE reset_mdp_token = :token");
            $stmt->execute(['token' => $resetToken]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the passwords match
                if ($newPassword === $confirmPassword) {
                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $stmt = $this->pdo->prepare("UPDATE inscription SET password = :password, reset_mdp_token = NULL WHERE reset_mdp_token = :token");
                    $stmt->execute(['password' => $hashedPassword, 'token' => $resetToken]);

                    // Redirect to the login page with a success message
                    $_SESSION['success_message'] = 'Password reset successfully.';
                    echo "<script>window.location.href = 'https://levelnext.fr/views/password_modificationEn.view.php?success=1';</script>";
                    exit();
                } else {
                    // Redirect to the password reset page with an error message
                    $_SESSION['error_message'] = 'Passwords do not match.';
                    echo "<script>window.location.href = 'https://levelnext.fr/views/password_recoveryEn.view.php?error=2';</script>";
                    exit();
                }
            } else {
                // Redirect to the password reset page with an error message
                $_SESSION['error_message'] = 'Invalid token.';
                echo "<script>window.location.href = 'https://levelnext.fr/views/password_recoveryEn.view.php?error=3';</script>";
                exit();
            }
        } catch (PDOException $e) {
            // Handle database errors appropriately
            die("Database error: " . $e->getMessage());
        }
    }

    public function sendPasswordResetEmail($user)
    {
        // Retrieve user data from the array
        $email = $user['email'];
        $prenom = $user['prenom'];
        $resetToken = $user['reset_token']; // Use the token correctly retrieved from the user data

        // Truncate the token if it is longer than 32 characters
        $resetToken = substr($resetToken, 0, 32);

        // Use the correct token to build the password reset link
        $resetLink = 'https://levelnext.fr/views/password_modificationEn.view.php?token=' . urlencode($resetToken);

        // Define the subject
        $subject = 'Reset Your Password';

        // Email content
        $message = "<p>You requested to reset your password. Please click the link below to proceed with resetting:</p>
                <p><a href='{$resetLink}'>Reset Password</a></p>
                <p>If you did not request a password reset, please ignore this email.</p>";

        // Email headers
        $headers = 'From: laniak@levelnext.fr' . "\r\n" . // Replace with your email address
            'Reply-To: laniak@levelnext.fr' . "\r\n" . // Replace with your email address
            'MIME-Version: 1.0' . "\r\n" . // MIME version
            'Content-type:text/html;charset=UTF-8' . "\r\n"; // Define content type

        // Send the email
        if (mail($email, $subject, $message, $headers)) {
            // Optionally handle successful send
        } else {
            // Handle email sending errors appropriately
            die("Email sending error: Please check the email address or server settings.");
        }
    }

    //                public function sendPasswordResetEmail($user)
    //                {
    //                    // Retrieve user data from the array
    //                    $email = $user['email'];
    //                    $prenom = $user['prenom'];
    //                    $resetToken = $user['reset_token']; // Use the token correctly retrieved from the user data

    //                    // Truncate the token if it is longer than 32 characters
    //                    $resetToken = substr($resetToken, 0, 32);

    //                    // Use the correct token to build the password reset link
    //                    $resetLink = 'https://levelnext.fr/views/password_modification.view.php?token=' . urlencode($resetToken);

    //                    $mail = new PHPMailer(true); // Enable exceptions
    //                    $mail->CharSet = 'UTF-8'; // Set the character set to UTF-8
    //                    try {
    //                        // Configure the SMTP server
    //                        $mail->isSMTP();
    //                        $mail->Host = $_ENV['SMTP_HOST']; // SMTP server
    //                        $mail->SMTPAuth = true;
    //                        $mail->Username = $_ENV['SMTP_USER']; // Your email address
    //                        $mail->Password = $_ENV['SMTP_PASS']; // Password for your email address
    //                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
    //                        $mail->Port = $_ENV['SMTP_PORT']; // SMTP port

    //                        // Sender and recipient settings
    //                        $mail->setFrom('laniak@levelnext.fr', 'Laniak Basketball Academy');
    //                        $mail->addAddress($email, $prenom); // Send the email to the user's address

    //                        // Email content
    //                        $mail->isHTML(true); // Set the email format to HTML
    //                        $mail->Subject = 'Reset Your Password';
    //                        $mail->Body = "<p>You requested to reset your password. Please click the link below to proceed with resetting:</p>
    //                                    <p><a href='{$resetLink}'>Reset Password</a></p>
    //         <p>If you did not request a password reset, please ignore this email.</p>";

    //         $mail->send();
    //     } catch (Exception $e) {
    //         // Handle email sending errors appropriately
    //         die("Email sending error: " . $e->getMessage());
    //     }
    // }
}
