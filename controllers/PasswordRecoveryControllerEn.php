<?php

// Retrieve the path to the .env file
$envFilePath = __DIR__ . '/../.env';

// Check if the .env file exists
if (file_exists($envFilePath)) {
    // Read the content of the .env file
    $envContent = file_get_contents($envFilePath);
    $envLines = explode("\n", $envContent);

    // Loop through each line to extract environment variables
    foreach ($envLines as $line) {
        // Ignore empty lines and comments
        if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            // Split the key and value of the variable
            list($key, $value) = explode('=', $line, 2);
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

// Include the database configuration file and model, robust path for MVC
$config = require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.class.php';
// require_once __DIR__ . '/../vendor/autoload.php'; // Uncomment if using PHPMailer

// Load main config for URL constant (for all internal redirects)
require_once __DIR__ . '/../config/config.php';

// use PHPMailer\PHPMailer\PHPMailer;

class PasswordRecoveryController
{
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        try {
            $this->pdo = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['database'] . ";charset=utf8",
                $config['user'],
                $config['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    // Retrieve and clear success message from session
    public function getSuccessMessage()
    {
        if (isset($_SESSION['success_message'])) {
            $message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            return $message;
        }
        return null;
    }

    // Retrieve and clear error message from session
    public function getErrorMessage()
    {
        if (isset($_SESSION['error_message'])) {
            $message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
            return $message;
        }
        return null;
    }

    // Request password reset (send email, store token)
    public function demandeMotDePasseOublie($email)
    {
        try {
            // Check if the email exists in the database
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Use the existing token or generate a new one
                $resetToken = $user['reset_mdp_token'] ?? '';
                if (empty($resetToken)) {
                    $resetToken = bin2hex(random_bytes(32));
                    $expiration = date('Y-m-d H:i:s', strtotime('+3 minutes'));
                    $stmt = $this->pdo->prepare("UPDATE inscription SET reset_mdp_token = :reset_token, token_expiration = :expiration WHERE email = :email");
                    $stmt->execute(['reset_token' => $resetToken, 'expiration' => $expiration, 'email' => $email]);
                }
                // Send password reset email with the token
                $user['reset_token'] = $resetToken;
                $this->sendPasswordResetEmail($user);

                // Set success and redirect to the recovery page (MVC)
                $_SESSION['success_message'] = 'Password reset request sent successfully. Please check your email.';
                header('Location: ' . URL . 'index.php?page=password_recoveryEn&success=1');
                exit();
            } else {
                // Set error and redirect
                $_SESSION['error_message'] = 'Email not found. Please check the entered email address.';
                header('Location: ' . URL . 'index.php?page=password_recoveryEn&error=1');
                exit();
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    // Actually reset password based on token
    public function resetPassword($resetToken, $newPassword, $confirmPassword)
    {
        try {
            // Check if the token exists in the database
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE reset_mdp_token = :token");
            $stmt->execute(['token' => $resetToken]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($newPassword === $confirmPassword) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare("UPDATE inscription SET password = :password, reset_mdp_token = NULL WHERE reset_mdp_token = :token");
                    $stmt->execute(['password' => $hashedPassword, 'token' => $resetToken]);
                    $_SESSION['success_message'] = 'Password reset successfully.';
                    header('Location: ' . URL . 'index.php?page=password_modificationEn&success=1');
                    exit();
                } else {
                    $_SESSION['error_message'] = 'Passwords do not match.';
                    header('Location: ' . URL . 'index.php?page=password_recoveryEn&error=2');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'Invalid token.';
                header('Location: ' . URL . 'index.php?page=password_recoveryEn&error=3');
                exit();
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    // Send password reset email with the correct token
    public function sendPasswordResetEmail($user)
    {
        $email = $user['email'];
        $prenom = $user['prenom'];
        $resetToken = $user['reset_token'];
        $resetToken = substr($resetToken, 0, 32);
        $resetLink = URL . "index.php?page=password_modificationEn&token=" . urlencode($resetToken);

        $subject = 'Reset Your Password';

        $message = "<p>You requested to reset your password. Please click the link below to proceed with resetting:</p>
            <p><a href='{$resetLink}'>Reset Password</a></p>
            <p>If you did not request a password reset, please ignore this email.</p>";

        $headers = 'From: laniak@levelnext.fr' . "\r\n" .
            'Reply-To: laniak@levelnext.fr' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type:text/html;charset=UTF-8' . "\r\n";

        if (mail($email, $subject, $message, $headers)) {
            // Success (silent)
        } else {
            die("Email sending error: Please check the email address or server settings.");
        }
    }

    // PHPMailer version (commented out)
    /*
    public function sendPasswordResetEmailSmtp($user)
    {
        $email = $user['email'];
        $prenom = $user['prenom'];
        $resetToken = $user['reset_token'];
        $resetToken = substr($resetToken, 0, 32);
        $resetLink = URL . "index.php?page=password_modificationEn&token=" . urlencode($resetToken);

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try {
            // Configure the SMTP server and send (adapt the code as in FR version)
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];
            $mail->setFrom('laniak@levelnext.fr', 'Laniak Basketball Academy');
            $mail->addAddress($email, $prenom);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            $mail->Body = "<p>You requested to reset your password. Please click the link below to proceed with resetting:</p>
                <p><a href='{$resetLink}'>Reset Password</a></p>
                <p>If you did not request a password reset, please ignore this email.</p>";

            $mail->send();
        } catch (Exception $e) {
            die("Email sending error: " . $e->getMessage());
        }
    }
    */
}
