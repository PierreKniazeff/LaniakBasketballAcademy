<?php
// Optional: Block direct access to this controller file
// if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
//     header("Location: /");
//     exit;
// }

// Include MVC config for base URL and settings
require_once __DIR__ . '/../config/config.php';

// Load the .env file if it exists
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

// Robustly include the User model
require_once __DIR__ . '/../models/User.class.php';
// require_once __DIR__ . '/../vendor/autoload.php';

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
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Check if the email already exists
    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscription WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    // Create a user in the database (with confirmation mail)
    public function createUser(User $user)
    {
        $result = array();

        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "The email address is not valid.";
            $result['class'] = "error";
            return $result;
        }

        if ($this->emailExists($user->getEmail())) {
            $result['message'] = "The entered email is already in use. Please choose another one.";
            $result['class'] = "error";
            return $result;
        }

        try {
            $token = bin2hex(random_bytes(16));
            $user->setToken($token);

            $tokenExpiration = new DateTime();
            $tokenExpiration->add(new DateInterval('PT15M'));
            $tokenExpirationFormatted = $tokenExpiration->format('Y-m-d H:i:s');

            $stmt = $this->pdo->prepare("INSERT INTO inscription 
                (prenom, nom, email, tel, date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, password, created_at, confirmed, token, token_expiration)
                VALUES 
                (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, :niveau_championnat, :poste, :objectifs, :password, NOW(), :confirmed, :token, :token_expiration)"
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

            // Optionally: send verification/admin emails
            $this->sendVerificationEmail($user);
            $this->sendConfirmationEmail($user);

            $_SESSION['user_email'] = $user->getEmail();

            $result['message'] = "Please complete your registration via the confirmation email sent to you.";
            $result['class'] = "success";
            return $result;
        } catch (PDOException $e) {
            $result['message'] = "An error occurred during registration: contact <a href='mailto:laniak@levelnext.fr'>laniak@levelnext.fr</a> " . $e->getMessage();
            $result['class'] = "error";
            return $result;
        }
    }

    // Update user data by email (all fields at once)
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
            return $stmt->rowCount() >= 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get user by email (returns array or false)
    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inscription WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Authenticate user (login)
    public function authenticateUser($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Delete a user by id
    public function deleteUser($userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM inscription WHERE id = ?");
        $stmt->execute([$userId]);
    }

    // Delete users not confirmed and expired
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
            echo "Error while deleting expired users: " . $e->getMessage();
        }
    }

    // Validate account by token and email
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
                    return array('success' => false, 'message' => 'The confirmation link has expired.');
                }
            } else {
                return array('success' => false, 'message' => 'Invalid token or does not match the email.');
            }
        } catch (PDOException $e) {
            return array('success' => false, 'message' => 'Error while confirming the user: ' . $e->getMessage());
        }
    }

    // Send verification email to user (implement according to your context)
    public function sendVerificationEmail($user)
    {
        $result = array();
        $to = $user->getEmail();
        $subject = 'Email Verification';

        $message = "
        <p>Thank you for signing up! Please confirm your email address by clicking the following link:</p>
        <p><a href='" . URL . "index.php?page=confirmationEn&token=" . urlencode($user->getToken()) . "&email=" . urlencode($user->getEmail()) . "'>Confirm Registration</a></p>";

        $headers = 'From: laniak@levelnext.fr' . "\r\n" .
            'Reply-To: laniak@levelnext.fr' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type:text/html;charset=UTF-8' . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $result['message'] = "The verification email has been sent successfully.";
            $result['class'] = "success";
        } else {
            $result['message'] = "An error occurred while sending the verification email. Please try again.";
            $result['class'] = "error";
        }
        return $result;
    }

    // Send admin alert email (new registration)
    public function sendConfirmationEmail($user)
    {
        $result = array();
        $to = "kniazeff.pierre@hotmail.fr, laniakbasketballacademy@gmail.com";
        $subject = 'New Registration';

        $message = "
            <p>Hello,</p>
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

        $headers = 'From: laniak@levelnext.fr' . "\r\n" .
            'Reply-To: laniak@levelnext.fr' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type:text/html;charset=UTF-8' . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $result['message'] = "The confirmation email has been successfully sent.";
            $result['class'] = "success";
        } else {
            $result['message'] = "Your player profile could not be sent to LaniakBasketballAcademy. Please contact <a href='mailto:laniak@levelnext.fr'>laniak@levelnext.fr</a> directly.";
            $result['class'] = "error";
        }
        return $result;
    }
}

// CSS for alerts
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
</style>
";
?>
