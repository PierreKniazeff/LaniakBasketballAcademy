<?php
// Sécurité optionnelle : empêche l'accès direct au contrôleur (à activer si besoin)
// if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
//     header("Location: /");
//     exit;
// }

// Inclusion de la config centrale (accès à la constante URL partout)
require_once __DIR__ . '/../config/config.php';

// --- Récupération du chemin vers le fichier .env ---
$envFilePath = __DIR__ . '/../.env';

// --- Chargement de la configuration .env ---
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

// Inclusion du modèle utilisateur avec chemin MVC robuste
require_once __DIR__ . '/../models/User.class.php';

// --- Classe CRUD principale ---
class CRUD
{
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        $this->pdo = new PDO(
            "mysql:host=" . $config['host'] . ";dbname=" . $config['database'] . ";charset=utf8",
            $config['user'],
            $config['password'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        // Définir le mode d’erreur sur Exception
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Vérifie si un email est déjà utilisé
    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscription WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    // Crée un utilisateur dans la base (avec mail de confirmation)
    public function createUser(User $user)
    {
        $result = array();

        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "L'adresse email n'est pas valide.";
            $result['class'] = "error";
            return $result;
        }

        if ($this->emailExists($user->getEmail())) {
            $result['message'] = "L'e-mail saisi est déjà utilisé. Veuillez en choisir un autre.";
            $result['class'] = "error";
            return $result;
        }

        try {
            // Génération d’un token pour l’email de validation
            $token = bin2hex(random_bytes(16));
            $user->setToken($token);

            // Expiration du token : 15 minutes
            $tokenExpiration = new DateTime();
            $tokenExpiration->add(new DateInterval('PT15M'));
            $tokenExpirationFormatted = $tokenExpiration->format('Y-m-d H:i:s');

            $stmt = $this->pdo->prepare("INSERT INTO inscription 
                (prenom, nom, email, tel, date_naissance, genre, taille, poids, club,
                niveau_championnat, poste, objectifs, password, created_at, confirmed, token, token_expiration) 
                VALUES 
                (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club,
                :niveau_championnat, :poste, :objectifs, :password, NOW(), :confirmed, :token, :token_expiration)"
            );

            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $confirmed = 0;

            $stmt->bindValue(':prenom', $user->getPrenom());
            $stmt->bindValue(':nom', $user->getNom());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':tel', $user->getTel());
            $stmt->bindValue(':date_naissance', $user->getDateNaissance());
            $stmt->bindValue(':genre', $user->getGenre());
            $stmt->bindValue(':taille', $user->getTaille());
            $stmt->bindValue(':poids', $user->getPoids());
            $stmt->bindValue(':club', $user->getClub());
            $stmt->bindValue(':niveau_championnat', $user->getNiveauChampionnat());
            $stmt->bindValue(':poste', $user->getPoste());
            $stmt->bindValue(':objectifs', $user->getObjectifs());
            $stmt->bindValue(':password', $hashedPassword);
            $stmt->bindValue(':confirmed', $confirmed);
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':token_expiration', $tokenExpirationFormatted);

            $stmt->execute();

            // Mail de vérification (facultatif)
            $this->sendVerificationEmail($user);
            $this->sendConfirmationEmail($user);

            $_SESSION['user_email'] = $user->getEmail();

            $result['message'] = "Attention: Inscription à finaliser via l'email de confirmation qui vous est envoyé.";
            $result['class'] = "success";
            return $result;
        } catch (PDOException $e) {
            $result['message'] = "Une erreur s'est produite lors de l'inscription: veuillez contacter directement <a href='mailto:laniak@levelnext.fr'>laniak@levelnext.fr</a> " . $e->getMessage();
            $result['class'] = "error";
            return $result;
        }
    }

    // Met à jour les champs utilisateur par email (mise à jour groupée)
    public function updateUser($email, $fields)
    {
        if (empty($fields) || empty($email)) return false;
        $setParts = [];
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $sql = "UPDATE inscription SET " . implode(', ', $setParts) . " WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':email', $email);

        try {
            $stmt->execute();
            return $stmt->rowCount() >= 0; // true même si pas de modif
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupère l'utilisateur par email (tableau OU false si non trouvé)
    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Authentifie (login)
    public function authenticateUser($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Suppression d’un utilisateur par id (à adapter si tu utilises email)
    public function deleteUser($userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }

    // Suppression des utilisateurs non confirmés et expirés
    public function deleteExpiredUsers()
    {
        try {
            $currentDateTime = new DateTime();
            $tokenExpiration = clone $currentDateTime;
            $tokenExpiration->sub(new DateInterval('PT15M'));
            $formattedTokenExpiration = $tokenExpiration->format('Y-m-d H:i:s');

            $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE confirmed = 0 AND token_expiration < :tokenExpiration");
            $stmt->bindParam(':tokenExpiration', $formattedTokenExpiration, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des utilisateurs expirés : " . $e->getMessage();
        }
    }

    // Validation de compte par token
    public function confirmUserByToken($token, $email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE token = :token AND email = :email");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $currentDateTime = new DateTime();
                $tokenExpiration = new DateTime($user['token_expiration']);
                if ($currentDateTime < $tokenExpiration) {
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
            return array('success' => false, 'message' => 'Erreur lors de la confirmation : ' . $e->getMessage());
        }
    }
    // Envoi d’un mail de vérification à l’utilisateur
    public function sendVerificationEmail($user)
    {
        $result = array();
        $to = $user->getEmail();
        $subject = 'Vérification de votre adresse e-mail';

        $message = "
            <p>Merci de vous être inscrit ! Veuillez confirmer votre adresse e-mail en cliquant sur le lien suivant :</p>
            <p><a href='" . URL . "index.php?page=confirmation&token=" . urlencode($user->getToken()) . "&email=" . urlencode($user->getEmail()) . "'>Confirmer l'inscription</a></p>
        ";

        $headers = 'From: laniak@levelnext.fr' . "\r\n" .
            'Reply-To: laniak@levelnext.fr' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type:text/html;charset=UTF-8' . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $result['message'] = "L'e-mail de vérification a été envoyé avec succès.";
            $result['class'] = "success";
        } else {
            $result['message'] = "Une erreur s'est produite lors de l'envoi de l'e-mail de vérification. Veuillez réessayer.";
            $result['class'] = "error";
        }
        return $result;
    }

    // Envoi d’un mail d’alerte admin (nouvelle inscription)
    public function sendConfirmationEmail($user)
    {
        $result = array();
        $to = "kniazeff.pierre@hotmail.fr, laniakbasketballacademy@gmail.com";
        $subject = 'Nouvelle inscription';

        $message = "
            <p>Bonjour,</p>
            <p>Nouvelle inscription confirmée sur votre site. Voici les détails:</p>
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
            <p>Merci.</p>
        ";

        $headers = 'From: laniak@levelnext.fr' . "\r\n" .
            'Reply-To: laniak@levelnext.fr' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type:text/html;charset=UTF-8' . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $result['message'] = "L'e-mail de confirmation a été envoyé avec succès.";
            $result['class'] = "success";
        } else {
            $result['message'] = "Votre profil joueur n'a pas pu être envoyé à LaniakBasketballAcademy. Veuillez contacter directement <a href='mailto:laniak@levelnext.fr'>laniak@levelnext.fr</a>.";
            $result['class'] = "error";
        }
        return $result;
    }

   
}

// --- Styles CSS pour affichage des alertes error/success dans les vues ---
echo '
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
</style>';
