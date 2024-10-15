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


require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../vendor/autoload.php';

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

            // Définir le token_expiration à 15 minutes à partir de maintenant
            $tokenExpiration = new DateTime();
            $tokenExpiration->add(new DateInterval('PT15M'));

            $stmt = $this->pdo->prepare("INSERT INTO inscription (prenom, nom, email, tel,
            date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, 
            password, created_at, confirmed, token, token_expiration) 
            VALUES (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, 
            :niveau_championnat, :poste, :objectifs, :password, NOW(), :confirmed, :token, :token_expiration)");

            $tokenExpirationFormatted = $tokenExpiration->format('Y-m-d H:i:s');
            $stmt->bindParam(':token_expiration', $tokenExpirationFormatted);

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
            $confirmed = 0;
            $token = $user->getToken();

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
            $stmt->bindParam(':confirmed', $confirmed);
            $stmt->bindParam(':token', $token);

            $stmt->execute();

            // Envoi de l'e-mail de confirmation avec le lien de vérification
            $emailResult = $this->sendVerificationEmail($user); // Vérifiez si l'envoi est réussi

            // Envoi de l'e-mail de confirmation
            $emailConfirmationResult = $this->sendConfirmationEmail($user); // Vérifiez si l'envoi est réussi

            if ($emailResult['class'] === 'success' && $emailConfirmationResult['class'] === 'success') {
                // Stockez l'e-mail de l'utilisateur dans la session
                $_SESSION['user_email'] = $user->getEmail();

                $result['message'] = "Attention: Inscription à finaliser via l'email de confirmation qui vous est envoyé.";
                $result['class'] = "success";
            } else {
                // S'il y a eu une erreur lors de l'envoi des e-mails
                $result['message'] = "Une erreur s'est produite lors de l'envoi de l'e-mail de confirmation. Veuillez contacter directement laniak@levelnext.fr";
                $result['class'] = "error";
            }

            return $result;
        } catch (PDOException $e) {
            $result['message'] = "Une erreur s'est produite lors de l'inscription: veuillez contacter directement laniak@levelnext.fr " . $e->getMessage();
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

            // Activer le débogage SMTP
            $mail->SMTPDebug = 0; // 0 = désactivé, 1 = erreurs, 2 = détaillé


            // Configuration du serveur SMTP pour Hotmail/Outlook
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST']; // Serveur SMTP de Hotmail/Outlook
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER']; // Votre adresse email Hotmail/Outlook
            $mail->Password = $_ENV['SMTP_PASS']; // Mot de passe de votre adresse email
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cryptage TLS
            $mail->Port = $_ENV['SMTP_PORT']; // Port SMTP pour Hotmail/Outlook

            // Paramètres d'expéditeur et destinataire
            $mail->setFrom($_ENV['SMTP_USER'], 'Laniak Basketball Academy');
            $mail->addAddress($user->getEmail()); // Envoyer l'email à l'adresse de l'utilisateur

            // Contenu de l'email
            $mail->isHTML(true); // Définir le format de l'email à HTML
            $mail->Subject = 'Confirmation d\'inscription';
            $mail->Body = "
            <p>Merci de vous être inscrit ! Veuillez confirmer votre adresse e-mail en cliquant sur le lien suivant :</p>
            <p><a href='http://levelnext.fr/views/confirmation.view.php?token=" . urlencode($user->getToken()) . "&email=" . urlencode($user->getEmail()) . "'>Confirmer l'inscription</a></p>";

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


    public function deleteUser($userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }


    public function confirmUserByToken($token, $email) // Ajoutez un paramètre pour l'e-mail
    {
        try {
            // Vérifiez si le token existe dans la base de données pour l'utilisateur correspondant à l'e-mail
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE token = :token AND email = :email");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Vérifiez si le token n'a pas expiré
                $currentDateTime = new DateTime();
                $tokenExpiration = new DateTime($user['token_expiration']);
                if ($currentDateTime < $tokenExpiration) {
                    // Mettez à jour la colonne 'confirmed' dans la base de données pour marquer l'utilisateur comme confirmé
                    $stmt = $this->pdo->prepare("UPDATE inscription SET confirmed = 1 WHERE token = :token AND email = :email");
                    $stmt->bindParam(':token', $token);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    return array('success' => true);
                } else {
                    return array('success' => false, 'message' => 'Le lien de confirmation a expiré.');
                }
            } else {
                return array('success' => false, 'message' => 'Token invalide ou ne correspond pas à l\'e-mail.');
            }
        } catch (PDOException $e) {
            return array('success' => false, 'message' => 'Erreur lors de la confirmation de l\'utilisateur: ' . $e->getMessage());
        }
    }


    public function deleteExpiredUsers()
    {
        try {
            // Calculer la date et l'heure actuelles
            $currentDateTime = new DateTime();

            // Calculer la date et l'heure à laquelle les tokens expireront (15 minutes avant l'heure actuelle)
            $tokenExpiration = clone $currentDateTime;
            $tokenExpiration->sub(new DateInterval('PT15M')); // Soustraire 15 minutes

            // Supprimer les utilisateurs non confirmés dont le token a expiré
            $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE confirmed = 0 AND token_expiration < :tokenExpiration");
            $formattedTokenExpiration = $tokenExpiration->format('Y-m-d H:i:s'); // Liaison du paramètre avec la valeur formatée
            $stmt->bindParam(':tokenExpiration', $formattedTokenExpiration, PDO::PARAM_STR);

            $stmt->execute();

            $expiredUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Supprimer les profils des utilisateurs expirés
            foreach ($expiredUsers as $user) {
                $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE id = :id");
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            // Gérer l'erreur si la suppression échoue
            echo "Erreur lors de la suppression des utilisateurs expirés : " . $e->getMessage();
        }
    }

    public function sendConfirmationEmail($user)
{
    $result = array();

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // Configuration du serveur SMTP pour Hotmail/Outlook
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        // Paramètres d'expéditeur et destinataire
        $mail->setFrom($_ENV['SMTP_USER'], 'Laniak Basketball Academy');
        $mail->addAddress('laniak@levelnext.fr', 'Laniak Basketball Academy');

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle inscription';
        $mail->Body = "
        <p>Nouvelle inscription confirmée:</p>
        Prénom: {$user->getPrenom()}<br>
        Nom: {$user->getNom()}<br>
        Email: {$user->getEmail()}<br>
        Téléphone: {$user->getTel()}<br>
        Date de naissance: {$user->getDateNaissance()}<br>
        Genre: {$user->getGenre()}<br>
        Taille: {$user->getTaille()}<br>
        Poids: {$user->getPoids()}<br>
        Club: {$user->getClub()}<br>
        Niveau de championnat: {$user->getNiveauChampionnat()}<br>
        Poste: {$user->getPoste()}<br>
        Objectifs: {$user->getObjectifs()}<br>
        <p>Merci.</p>";    

        $mail->send();

        $result['message'] = "L'e-mail de confirmation a été envoyé avec succès.";
        $result['class'] = "success";
    } catch (Exception $e) {
        $result['message'] = "Votre profil joueur n'a pas pu être envoyé à LaniakBasketballAcademy. Veuillez contacter directement laniak@levelnext.fr. Erreur : " . $mail->ErrorInfo;
        $result['class'] = "error";
    }

    return $result;
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
