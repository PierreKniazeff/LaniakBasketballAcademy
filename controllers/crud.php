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


require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/User.class.php');
require(__DIR__ . '/../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;

$mailHost = $_ENV['SMTP_HOST'];
$mailUsername = $_ENV['SMTP_USER'];
$mailPassword = $_ENV['SMTP_PASS'];
$mailPort = $_ENV['SMTP_PORT'];

class CRUD
{
    private $pdo;

    public function __construct()
    {
        $config = require('config/database.php');
        $this->pdo = new PDO(
            "mysql:host=" . $config['host'] . ";dbname=" . $config['database'],
            $config['user'],
            $config['password'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // session_start(); // Démarre la session ici
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
        // Initialisation du tableau associatif pour le message de retour
        $result = array();

        // Vérification de la validité de l'adresse email
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "L'adresse email n'est pas valide.";
            $result['class'] = "error";
            return $result;
        }

        // Vérification si l'e-mail existe déjà
        if ($this->emailExists($user->getEmail())) {
            $result['message'] = "L'e-mail saisi est déjà utilisé. Veuillez en choisir un autre.";
            $result['class'] = "error";
            return $result;
        }

        // Insertion des données dans la base de données
        try {
            // Génération et stockage temporaire du jeton d'authentification
            $token = bin2hex(random_bytes(16));
            $user->setToken($token);

            // Définir le token_expiration à 24 heures à partir de maintenant
            $tokenExpiration = new DateTime(); // Heure actuelle
            $tokenExpiration->add(new DateInterval('P1D')); // Ajoute 24 heures

            $stmt = $this->pdo->prepare("INSERT INTO inscription (prenom, nom, email, tel, date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, password, created_at, confirmed, token, token_expiration) VALUES (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, :niveau_championnat, :poste, :objectifs, :password, NOW(), :confirmed, :token, :token_expiration)");

            // En plus des autres paramètres déjà liés
            $tokenExpirationFormatted = $tokenExpiration->format('Y-m-d H:i:s');
            $stmt->bindParam(':token_expiration', $tokenExpirationFormatted);

            // Hashing du mot de passe avant de l'insérer dans la base de données
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

            // Ici, vous devez définir les variables avant de les passer à bindParam()
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
            $confirmed = 0; // Supposition que l'utilisateur n'est pas confirmé à l'inscription
            $token = $user->getToken(); // Généré précédemment dans ce bloc

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
            $stmt->bindParam(':password', $hashedPassword); // Utilisation du mot de passe hashé
            $stmt->bindParam(':confirmed', $confirmed);
            $stmt->bindParam(':token', $token);

            $stmt->execute();

            // Envoi de l'e-mail de confirmation avec le lien de vérification
            $this->sendVerificationEmail($user);

            // Envoi de l'e-mail de confirmation
            $this->sendConfirmationEmail($user);

            $result['message'] = "Inscription réussie. Un email de confirmation vous a été envoyé.";
            $result['class'] = "success";
            return $result;
        } catch (PDOException $e) {
            $result['message'] = "Une erreur s'est produite lors de l'inscription: " . $e->getMessage();
            $result['class'] = "error";
            return $result;
        }
    }

    public function sendVerificationEmail($user)
    {
        $result = array();

        try {
            $mail = new PHPMailer(true); // Activer les exceptions
            $mail->CharSet = 'UTF-8'; // Définir le jeu de caractères à UTF-8

            // Configuration du serveur SMTP pour Hotmail/Outlook
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST']; // Serveur SMTP de Hotmail/Outlook
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER']; // Votre adresse email Hotmail/Outlook
            $mail->Password = $_ENV['SMTP_PASS']; // Mot de passe de votre adresse email
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cryptage TLS
            $mail->Port = $_ENV['SMTP_PORT']; // Port SMTP pour Hotmail/Outlook

            // Paramètres d'expéditeur et destinataire
            $mail->setFrom('kniazeff.pierre@hotmail.fr', 'Laniak Basketball Academy');
            $mail->addAddress($user->getEmail()); // Envoyer l'email à l'adresse de l'utilisateur

            // Contenu de l'email
            $mail->isHTML(true); // Définir le format de l'email à HTML
            $mail->Subject = 'Confirmation d\'inscription';
            $mail->Body = "
                <p>Merci de vous être inscrit ! Veuillez confirmer votre adresse e-mail en cliquant sur le lien suivant :</p>
                <p><a href='http://levelnext.fr/confirmation?token={$user->getToken()}'>Confirmer l'inscription</a></p>";

            $mail->send();

            $result['message'] = "Le formulaire a été soumis avec succès. Un e-mail contenant un code de vérification vous a été envoyé. Veuillez vérifier votre boîte de réception et cliquer sur le bouton ci-dessous pour entrer le code de vérification et confirmer votre inscription.";
            $result['class'] = "success";

            return $result;
        } catch (Exception $e) {
            $result['message'] = "Une erreur s'est produite lors de l'envoi de l'e-mail de confirmation. Veuillez réessayer.";
            $result['class'] = "error";
            return $result;
        }
    }

    public function sendConfirmationEmail($user)
    {
        $mail = new PHPMailer(true); // Activer les exceptions
        $mail->CharSet = 'UTF-8'; // Définir le jeu de caractères à UTF-8
        try {
            // Configuration du serveur SMTP pour Hotmail/Outlook
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST']; // Serveur SMTP de Hotmail/Outlook
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER']; // Votre adresse email Hotmail/Outlook
            $mail->Password = $_ENV['SMTP_PASS']; // Mot de passe de votre adresse email
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cryptage TLS
            $mail->Port = $_ENV['SMTP_PORT']; // Port SMTP pour Hotmail/Outlook

            // Paramètres d'expéditeur et destinataire
            $mail->setFrom('kniazeff.pierre@hotmail.fr', 'Laniak Basketball Academy');
            $mail->addAddress('kniazeff.pierre@hotmail.fr', 'Laniak Basketball Academy'); // Envoyer l'email à laniak

            // Contenu de l'email
            $mail->isHTML(true); // Définir le format de l'email à HTML
            $mail->Subject = 'Nouvelle inscription sur votre site';
            $mail->Body = "
                <p>Bonjour,</p>
                <p>Une nouvelle inscription a été confirmée sur votre site. Voici les détails :</p>
                <ul>
                    <li>Prénom: {$user->getPrenom()}</li>
                    <li>Nom: {$user->getNom()}</li>
                    <li>Email: {$user->getEmail()}</li>
                    <li>Téléphone: {$user->getTel()}</li>
                    <li>Date de naissance: {$user->getDateNaissance()}</li>
                    <li>Genre: {$user->getGenre()}</li>
                    <li>Taille: {$user->getTaille()}</li>
                    <li>Poids: {$user->getPoids()}</li>
                    <li>Club: {$user->getClub()}</li>
                    <li>Niveau de championnat: {$user->getNiveauChampionnat()}</li>
                    <li>Poste: {$user->getPoste()}</li>
                    <li>Objectifs: {$user->getObjectifs()}</li>
                </ul>
                <p>Merci.</p>";

            $mail->send();
            echo 'Votre profil joueur a bien été soumis à LaniakBasketballAcademy';
        } catch (Exception $e) {
            echo 'Votre profil joueur n\'a pas pu être envoyé à LaniakBasketballAcademy. Erreur : ', $mail->ErrorInfo;
        }
    }

     // Ajout d'une nouvelle méthode pour mettre à jour les informations de l'utilisateur
    
}
  

// Styles CSS
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