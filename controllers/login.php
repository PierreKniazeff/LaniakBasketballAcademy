<?php

// Affichage des erreurs pour le débogage (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la config base de données robuste (chemin MVC correct)
$config = require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.class.php';
// Inclusion de la config MVC centrale si besoin de la constante URL
require_once __DIR__ . '/../config/config.php';

/**
 * Contrôleur d'authentification utilisateur et mise à jour profil
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
     * Tente de connecter un utilisateur à partir d'un email/mot de passe
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
                    // Création de l'objet User depuis les données BDD
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
                    // Stocker dans la session
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user'] = serialize($user);
                    $_SESSION['user_prenom'] = $user->getPrenom();
                    $_SESSION['email'] = $user->getEmail();

                    // Redirection : TOUJOURS par le contrôleur MVC
                    header('Location: ' . URL . 'index.php?page=utilisateur');
                    exit();
                } else {
                    return "Identifiants incorrects. Veuillez réessayer.";
                }
            } else {
                return "Identifiants incorrects. Veuillez réessayer.";
            }
        } catch (PDOException $e) {
            error_log("Erreur de requête : " . $e->getMessage());
            die("Une erreur s'est produite. Veuillez réessayer ultérieurement.");
        }
    }

    /**
     * Met à jour le champ profil utilisateur donné
     */
    public function updateUserField($userEmail, $field, $value)
    {
        // Validation des champs autorisés
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
            return '<div class="error">Modification non autorisée.</div>';
        }

        try {
            $sql = "UPDATE inscription SET $field = :value WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':email', $userEmail);
            $stmt->execute();

            return '<div class="success">Mise à jour réussie.</div>';
        } catch (PDOException $e) {
            return '<div class="error">Échec de la mise à jour : ' . $e->getMessage() . '</div>';
        }
    }
    public function updateUser($email, $fields)
    {
        require_once __DIR__ . '/crud.php';
        $crud = new CRUD();
        return $crud->updateUser($email, $fields);
    }

}


// Utilisation de la classe LoginController
$loginController = new LoginController();

// Messages par défaut pour affichage
$successMessage = "";
$errorMessage = "";

// Vérifie si le formulaire de connexion est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Si les identifiants sont bien remplis
    if ($email && $password) {
        $loginResult = $loginController->loginUser($email, $password);
        if ($loginResult === true) {
            // Utilisateur connecté (normalement la redirection bloque ici)
            header('Location: ' . URL . 'index.php?page=utilisateur');
            exit();
        } else {
            $errorMessage = $loginResult;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Styles CSS -->
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
        <!-- Affiche le formulaire si non connecté -->
        <h1>Login</h1>
        <?php if (isset($errorMessage) && $errorMessage): ?>
            <div class="error"><?= $errorMessage ?></div>
        <?php endif; ?>
        <!-- (Insère ici ton formulaire login si tu en as un !) -->
    <?php endif; ?>
</body>

</html>