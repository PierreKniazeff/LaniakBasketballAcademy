<?php

// Load the .env variables
$envFilePath = __DIR__ . '/../.env';

if (file_exists($envFilePath)) {
    $envContent = file_get_contents($envFilePath);
    $envLines = explode("\n", $envContent);
    foreach ($envLines as $line) {
        if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!isset($_ENV[$key]) && !isset($_SERVER[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
} else {
    die('.env file not found.');
}

// Load DB config and User model with robust path
$config = require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.class.php';
// require_once __DIR__ . '/../vendor/autoload.php'; // Uncomment to use PHPMailer/other libs

// Load MVC config for URL constant (use for all internal redirects)
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

    // Retrieve and delete a session success message
    public function getSuccessMessage()
    {
        if (isset($_SESSION['success_message'])) {
            $message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            return $message;
        }
        return null;
    }

    // Retrieve and delete a session error message
    public function getErrorMessage()
    {
        if (isset($_SESSION['error_message'])) {
            $message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
            return $message;
        }
        return null;
    }

    /**
     * Demander une réinitialisation de mot de passe (envoie mail/stocke token)
     */
    public function demandeMotDePasseOublie($email)
    {
        try {
            // Vérifier que l'email existe
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Utilise ou crée un token pour cet utilisateur
                $resetToken = $user['reset_mdp_token'] ?? '';
                if (empty($resetToken)) {
                    $resetToken = bin2hex(random_bytes(32));
                    $expiration = date('Y-m-d H:i:s', strtotime('+3 minutes'));
                    $stmt = $this->pdo->prepare("UPDATE inscription SET reset_mdp_token = :reset_token, token_expiration = :expiration WHERE email = :email");
                    $stmt->execute(['reset_token' => $resetToken, 'expiration' => $expiration, 'email' => $email]);
                }
                // Envoie l'e-mail avec le token
                $user['reset_token'] = $resetToken;
                $this->sendPasswordResetEmail($user);

                // Succès MVC : redirige via index.php?page=password_recovery
                $_SESSION['success_message'] = 'Demande de réinitialisation envoyée avec succès. Veuillez vérifier votre email.';
                header('Location: ' . URL . 'index.php?page=password_recovery&success=1');
                exit();
            } else {
                // Erreur MVC : redirige
                $_SESSION['error_message'] = 'Email introuvable. Veuillez vérifier l\'adresse email saisie.';
                header('Location: ' . URL . 'index.php?page=password_recovery&error=1');
                exit();
            }
        } catch (PDOException $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }
    }

    /**
     * Réinitialiser le mot de passe via le token
     */
    public function resetPassword($resetToken, $newPassword, $confirmPassword)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE reset_mdp_token = :token");
            $stmt->execute(['token' => $resetToken]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($newPassword === $confirmPassword) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare("UPDATE inscription SET password = :password, reset_mdp_token = NULL WHERE reset_mdp_token = :token");
                    $stmt->execute(['password' => $hashedPassword, 'token' => $resetToken]);
                    $_SESSION['success_message'] = 'Mot de passe réinitialisé avec succès.';
                    header('Location: ' . URL . 'index.php?page=password_modification&success=1');
                    exit();
                } else {
                    $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas.';
                    header('Location: ' . URL . 'index.php?page=password_recovery&error=2');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'Token invalide.';
                header('Location: ' . URL . 'index.php?page=password_recovery&error=3');
                exit();
            }
        } catch (PDOException $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }
    }

    /**
     * Envoi d'un email avec le lien de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail($user)
    {
        // Récupérer les données utilisateur
        $email = $user['email'];
        $prenom = $user['prenom'];
        $resetToken = $user['reset_token'];
        $resetToken = substr($resetToken, 0, 32); // Troncature si nécessaire
        // Lien MVC
        $resetLink = URL . "index.php?page=password_modification&token=" . urlencode($resetToken);

        $subject = 'Réinitialisation de votre mot de passe';

        $message = "
            <p>Vous avez demandé la réinitialisation de votre mot de passe. Veuillez cliquer sur le lien ci-dessous pour procéder à la réinitialisation :</p>
            <p><a href='{$resetLink}'>Réinitialiser le mot de passe</a></p>
            <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet email.</p>";

        $headers = 'From: laniak@levelnext.fr' . "\r\n" .
            'Reply-To: laniak@levelnext.fr' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type:text/html;charset=UTF-8' . "\r\n";

        if (mail($email, $subject, $message, $headers)) {
            // Success
        } else {
            die("Erreur d'envoi d'email. Veuillez vérifier l'adresse e-mail ou les paramètres du serveur.");
        }
    }

    // Version PHPMailer (commentée)
    /*
    public function sendPasswordResetEmailSmtp($user)
    {
        $email = $user['email'];
        $prenom = $user['prenom'];
        $resetToken = $user['reset_token'];
        $resetToken = substr($resetToken, 0, 32);
        $resetLink = URL . "index.php?page=password_modification&token=" . urlencode($resetToken);

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try {
            // Config SMTP, addresses, content... (cf version française)
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
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "<p>Vous avez demandé la réinitialisation de votre mot de passe. Veuillez cliquer sur le lien ci-dessous pour procéder à la réinitialisation :</p>
            <p><a href='{$resetLink}'>Réinitialiser le mot de passe</a></p>
            <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet email.</p>";

            $mail->send();
        } catch (Exception $e) {
            die("Erreur d'envoi d'email : " . $e->getMessage());
        }
    }
    */
}
