<?php

// Enable debug (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database config (robust path for MVC)
$config = require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.class.php';
// Include global config for the URL constant
require_once __DIR__ . '/../config/config.php';

/**
 * Login controller to authenticate users and update profile info
 */
class LoginController
{
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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

    /**
     * Try to log in a user with email/password
     */
    public function loginUser($email, $password)
    {
        try {
            $sql = "SELECT * FROM inscription WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute(array(':email' => $email));
            if ($stmt->rowCount() > 0) {
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $userData['password'])) {
                    // Create the user instance with retrieved data
                    $user = new User(
                        $userData['prenom'],
                        $userData['nom'],
                        $userData['email'],
                        $userData['tel'],
                        $userData['date_naissance'],
                        $userData['genre'],
                        $userData['taille'],
                        $userData['poids'],
                        $userData['club'],
                        $userData['niveau_championnat'],
                        $userData['poste'],
                        $userData['objectifs'],
                        $userData['password'],
                        $userData['created_at'],
                        $userData['confirmed'],
                        $userData['token']
                    );
                    // Store the $user instance in the session
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user'] = serialize($user);
                    $_SESSION['user_prenom'] = $user->getPrenom();
                    $_SESSION['email'] = $user->getEmail();

                    // MVC redirect (never direct to view file!)
                    header('Location: ' . URL . 'index.php?page=utilisateurEn');
                    exit();
                } else {
                    return "Incorrect credentials. Please try again.";
                }
            } else {
                return "Incorrect credentials. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            die("An error occurred. Please try again later.");
        }
    }

    /**
     * Update a user's profile field (only allowed fields)
     */
    public function updateUserField($userEmail, $field, $value)
    {
        // Allowed fields for update
        $allowedFields = [
            'prenom',
            'nom',
            'email',
            'tel',
            'date_naissance',
            'genre',
            'taille',
            'poids',
            'club',
            'niveau_championnat',
            'poste',
            'objectifs'
        ];

        if (!in_array($field, $allowedFields)) {
            return '<div class="error">Unauthorized modification.</div>';
        }

        try {
            $sql = "UPDATE inscription SET $field = :value WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':email', $userEmail);
            $stmt->execute();

            return '<div class="success">Update successful.</div>';
        } catch (PDOException $e) {
            return '<div class="error">Update failed: ' . $e->getMessage() . '</div>';
        }
    }
    public function updateUser($email, $fields)
    {
        require_once __DIR__ . '/crud.php';
        $crud = new CRUD();
        return $crud->updateUser($email, $fields);
    }

}

// Use the LoginController class
$loginController = new LoginController();

// Set default messages
$successMessage = "";
$errorMessage = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Validate login credentials
    if ($email && $password) {
        $loginResult = $loginController->loginUser($email, $password);
        if ($loginResult === true) {
            // User is successfully authenticated (should redirect)
            header('Location: ' . URL . 'index.php?page=utilisateurEn');
            exit();
        } else {
            $errorMessage = $loginResult;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- CSS Styles -->
    <style>
        .error,
        .success {
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
</head>

<body>
    <?php if (!isset($_SESSION['user_logged_in'])): ?>
        <!-- Display the login form only if the user is not already logged in -->
        <h1>Login</h1>
        <!-- Display the error message -->
        <?php if (isset($errorMessage) && $errorMessage): ?>
            <div class="error"><?= $errorMessage ?></div>
        <?php endif; ?>
        <!-- (Place your login form HTML here if you have one!) -->
    <?php endif; ?>
</body>

</html>