<?php
require_once('config/database.php');
require_once('models/User.class.php');
require 'vendor/autoload.php';

class CRUD
{
    private $pdo;

    public function __construct()
    {
        $config = require('config/database.php');
        $this->pdo = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['database'], $config['user'], $config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        session_start(); // Démarre la session ici
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

        // Validation des données du formulaire
        if ($user->getPassword() !== $user->getConfirmPassword()) {
            $result['message'] = "Les mots de passe ne correspondent pas.";
            $result['class'] = "error";
            return $result;
        }

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
            // Génération et stockage temporaire du code de vérification
            $verification_code = rand(100000, 999999);
            $_SESSION['verification_code'] = $verification_code;

            $stmt = $this->pdo->prepare("INSERT INTO inscription (prenom, nom, email, tel, date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, password, created_at, verification_code) VALUES (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, :niveau_championnat, :poste, :objectifs, :password, NOW(), :verification_code)");

            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

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
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':verification_code', $verification_code);

            $stmt->execute();

            // Envoi du code de vérification à l'utilisateur par e-mail
            $this->sendVerificationEmail($email, $verification_code);

            $result['message'] = "Le formulaire a été soumis avec succès. Un e-mail contenant un code de vérification vous a été envoyé. Veuillez vérifier votre boîte de réception et cliquer sur le bouton ci-dessous pour entrer le code de vérification et confirmer votre inscription.";
            $result['class'] = "success";
            return $result;
        } catch (PDOException $e) {
            $result['message'] = "Une erreur s'est produite lors de l'inscription, veuillez réessayer.";
            $result['class'] = "error";
            return $result;
        }
    }


    public function verifyVerificationCode($verificationCode)
    {
        // Initialisation du tableau associatif pour le message de retour
        $result = array();

        // Comparaison du code saisi par l'utilisateur avec celui stocké temporairement
        if ($_SESSION['verification_code'] == $verificationCode) {
            $result['message'] = "Code de vérification correct. Votre inscription est confirmée.";
            $result['class'] = "success";
            // Récupérer les données de l'utilisateur depuis la base de données
     $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE verification_code = :verification_code");
            $stmt->bindParam(':verification_code', $verificationCode);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Envoi du mail avec les données de l'utilisateur
            $this->sendConfirmationEmail($user);
        } else {
            $result['message'] = "Code de vérification incorrect. Veuillez réessayer.";
            $result['class'] = "error";
        }
        return $result;
    }



    public function sendVerificationEmail($email, $verification_code)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Passage de `true` active les exceptions
        $mail->CharSet = 'UTF-8'; // Définir le jeu de caractères à UTF-8
        try {
            // Configuration du serveur SMTP
            $mail->isSMTP(); // Utiliser SMTP
            $mail->Host = 'smtp-mail.outlook.com'; // Serveur SMTP de Hotmail (Live)
            $mail->SMTPAuth = true; // Activer l'authentification SMTP
            $mail->Username = 'kniazeff.pierre@hotmail.fr'; // Votre adresse email Hotmail
            $mail->Password = 'Moulinard@95520'; // Votre mot de passe SMTP
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Activer le cryptage TLS
            $mail->Port = 587; // Port TCP pour se connecter

            // Paramètres d'expéditeur et de destinataire
            $mail->setFrom('kniazeff.pierre@hotmail.fr', 'Laniak Basketball Academy'); // L'adresse d'expéditeur
            $mail->addAddress($email); // Ajouter le destinataire

            // Contenu de l'email
            $mail->isHTML(true); // Définir le format de l'email à HTML 
            $mail->Subject = 'Code de vérification pour votre inscription'; // Le sujet de l'email
            $mail->Body = 'Votre code de vérification est : ' . $verification_code; // Le corps de l'email en HTML
            $mail->AltBody = 'Votre code de vérification est : ' . $verification_code; // Le corps de l'email en texte simple pour les clients n'acceptant pas HTML

            $mail->send();
            echo 'Le message de vérification a été envoyé';
        } catch (Exception $e) {
            echo 'Le message n\'a pas pu être envoyé. Erreur de Mailer : ', $mail->ErrorInfo;
        }
    }




    public function sendConfirmationEmail($user)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Activer les exceptions
        $mail->CharSet = 'UTF-8'; // Définir le jeu de caractères à UTF-8
        try {
            // Configuration du serveur SMTP pour Hotmail/Outlook
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com'; // Serveur SMTP de Hotmail/Outlook
            $mail->SMTPAuth = true;
            $mail->Username = 'kniazeff.pierre@hotmail.fr'; // Votre adresse email Hotmail/Outlook
            $mail->Password = 'Moulinard@95520'; // Mot de passe de votre adresse email
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Cryptage TLS
            $mail->Port = 587; // Port SMTP pour Hotmail/Outlook

            // Paramètres d'expéditeur et destinataire
            $mail->setFrom('kniazeff.pierre@hotmail.fr', 'Laniak Basketball Academy');
            $mail->addAddress('kniazeff.pierre@hotmail.fr'); // Envoyer l'email à votre propre adresse (pour test)

            // Contenu de l'email
            $mail->isHTML(true); // Définir le format de l'email à HTML
            $mail->Subject = 'Nouvelle inscription sur votre site';
            $mail->Body = "
                <p>Bonjour,</p>
                <p>Une nouvelle inscription a été confirmée sur votre site. Voici les détails :</p>
                <ul>
                    <li>Prénom: {$user['prenom']}</li>
                    <li>Nom: {$user['nom']}</li>
                    <li>Email: {$user['email']}</li>
                    <li>Téléphone: {$user['tel']}</li>
                    <li>Date de naissance: {$user['date_naissance']}</li>
                    <li>Genre: {$user['genre']}</li>
                    <li>Taille: {$user['taille']}</li>
                    <li>Poids: {$user['poids']}</li>
                    <li>Club: {$user['club']}</li>
                    <li>Niveau de championnat: {$user['niveau_championnat']}</li>
                    <li>Poste: {$user['poste']}</li>
                    <li>Objectifs: {$user['objectifs']}</li>
                </ul>
                <p>Merci.</p>";

            $mail->send();
            echo 'L\'email de confirmation a bien été envoyé';
        } catch (Exception $e) {
            echo 'L\'email de confirmation n\'a pas pu être envoyé. Erreur : ', $mail->ErrorInfo;
        }
    }
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
?>