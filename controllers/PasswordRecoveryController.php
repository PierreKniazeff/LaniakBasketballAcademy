<?php

// Récupération du chemin vers le fichier .env
$envFilePath = __DIR__ . '/../.env';

// Vérification si le fichier .env existe
if (file_exists($envFilePath)) {
    // Lecture du contenu du fichier .env
    $envContent = file_get_contents($envFilePath);

    // Séparation des lignes du fichier .env
    $envLines = explode("\n", $envContent);

    // Parcours de chaque ligne pour extraire les variables d'environnement
    foreach ($envLines as $line) {
        // Ignorer les lignes vides et les commentaires
        if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            // Séparation de la clé et de la valeur de la variable
            list($key, $value) = explode('=', $line, 2);

            // Suppression des espaces en début et fin de la clé et de la valeur
            $key = trim($key);
            $value = trim($value);

            // Définition de la variable d'environnement si elle n'est pas déjà définie
            if (!isset($_ENV[$key]) && !isset($_SERVER[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
} else {
    // Gérer le cas où le fichier .env n'existe pas
    die('.env file not found.');
}

// Inclure le fichier de configuration de la base de données
$config = require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../vendor/autoload.php';

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
            // Gérer les erreurs de connexion à la base de données de manière appropriée
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    public function getSuccessMessage()
    {
        if (isset($_SESSION['success_message'])) {
            $message = $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Supprimer le message de la session après l'avoir récupéré
            return $message;
        }
        return null;
    }

    // Méthode pour obtenir le message d'erreur
    public function getErrorMessage()
    {
        if (isset($_SESSION['error_message'])) {
            $message = $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Supprimer le message de la session après l'avoir récupéré
            return $message;
        }
        return null;
    }

    public function demandeMotDePasseOublie($email) {
        try {
            // Vérifier si l'email existe dans la base de données
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                // Utiliser le token existant s'il y en a un pour cet utilisateur
                $resetToken = $user['reset_mdp_token'] ?? ''; // Utiliser le token existant s'il existe, sinon initialiser à une chaîne vide
    
                // Si aucun token n'existe pour cet utilisateur, en générer un nouveau
                if (empty($resetToken)) {
                    $resetToken = bin2hex(random_bytes(32));
                    $expiration = date('Y-m-d H:i:s', strtotime('+3 minutes'));
    
                    // Mettre à jour le jeton et sa date d'expiration dans la base de données pour cet utilisateur
                    $stmt = $this->pdo->prepare("UPDATE inscription SET reset_mdp_token = :reset_token, token_expiration = :expiration WHERE email = :email");
                    $stmt->execute(['reset_token' => $resetToken, 'expiration' => $expiration, 'email' => $email]);
                }
    
                // Envoyer un email de réinitialisation avec le bon token
                $user['reset_token'] = $resetToken; // Assurer que le token est passé dans les données de l'utilisateur
                $this->sendPasswordResetEmail($user);
    
                // Redirection vers la page de modification de mot de passe avec un message de succès et le token
                $_SESSION['success_message'] = 'Demande de réinitialisation envoyée avec succès. Veuillez vérifier votre email.';
                echo "<script>window.location.href = 'https://levelnext.fr/views/password_recovery.view.php?success=1';</script>";
                exit();
            } else {
                // Redirection vers la page de récupération de mot de passe avec un message d'erreur
                $_SESSION['error_message'] = 'Email introuvable. Veuillez vérifier l\'adresse email saisie.';
                echo "<script>window.location.href = 'https://levelnext.fr/views/password_recovery.view.php?error=1';</script>";
                exit();
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de base de données de manière appropriée
            die("Erreur de base de données : " . $e->getMessage());
        }
    }
    
    public function resetPassword($resetToken, $newPassword, $confirmPassword)
    {
        try {
            // Vérifier si le token existe dans la base de données
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE reset_mdp_token = :token");
            $stmt->execute(['token' => $resetToken]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Vérifier si les mots de passe correspondent
                if ($newPassword === $confirmPassword) {
                    // Hasher le nouveau mot de passe
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Mettre à jour le mot de passe dans la base de données
                    $stmt = $this->pdo->prepare("UPDATE inscription SET password = :password, reset_mdp_token = NULL WHERE reset_mdp_token = :token");
                    $stmt->execute(['password' => $hashedPassword, 'token' => $resetToken]);

                    // Redirection vers la page de connexion avec un message de succès
                    $_SESSION['success_message'] = 'Mot de passe réinitialisé avec succès.';
                    echo "<script>window.location.href = 'https://levelnext.fr/views/password_modification.view.php?success=1';</script>";
                    exit();
                } else {
                    // Redirection vers la page de réinitialisation de mot de passe avec un message d'erreur
                    $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas.';
                    echo "<script>window.location.href = 'https://levelnext.fr/views/password_recovery.view.php?error=2';</script>";
                    exit();
                }
            } else {
                // Redirection vers la page de réinitialisation de mot de passe avec un message d'erreur
                $_SESSION['error_message'] = 'Token invalide.';
                echo "<script>window.location.href = 'https://levelnext.fr/views/password_recovery.view.php?error=3';</script>";
                exit();
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de base de données de manière appropriée
            die("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function sendPasswordResetEmail($user)
{
    // Récupérer les données de l'utilisateur depuis le tableau
    $email = $user['email'];
    $prenom = $user['prenom'];
    $resetToken = $user['reset_token']; // Utiliser le token correctement récupéré depuis les données de l'utilisateur

    // Tronquer le token s'il est plus long que 32 caractères
    $resetToken = substr($resetToken, 0, 32);

    // Utiliser le bon token pour construire le lien de réinitialisation du mot de passe
    $resetLink = 'https://levelnext.fr/views/password_modification.view.php?token=' . urlencode($resetToken);

    $mail = new PHPMailer(true); // Activer les exceptions
    $mail->CharSet = 'UTF-8'; // Définir le jeu de caractères à UTF-8
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST']; // Serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER']; // Votre adresse email
        $mail->Password = $_ENV['SMTP_PASS']; // Mot de passe de votre adresse email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cryptage TLS
        $mail->Port = $_ENV['SMTP_PORT']; // Port SMTP

        // Paramètres d'expéditeur et destinataire
        $mail->setFrom('laniak@levelnext.fr', 'Laniak Basketball Academy');
        $mail->addAddress($email, $prenom); // Envoyer l'email à l'adresse de l'utilisateur

        // Contenu de l'email
        $mail->isHTML(true); // Définir le format de l'email à HTML
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body = "<p>Vous avez demandé la réinitialisation de votre mot de passe. Veuillez cliquer sur le lien ci-dessous pour procéder à la réinitialisation :</p>
        <p><a href='{$resetLink}'>Réinitialiser le mot de passe</a></p>
        <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet email.</p>";

        $mail->send();
    } catch (Exception $e) {
        // Gérer les erreurs d'envoi d'email de manière appropriée
        die("Erreur d'envoi d'email : " . $e->getMessage());
    }
}
}
