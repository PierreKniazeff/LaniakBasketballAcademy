<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de configuration de la base de données
$config = require(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/User.class.php');

class LoginController
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
        session_start(); // Démarrer la session ici si nécessaire
    }

    public function loginUser($email, $password)
    {
        try {
            // Requête SQL avec des paramètres de requête pour éviter les injections SQL
            $sql = "SELECT * FROM inscription WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(':email' => $email));

            // Vérifier si l'utilisateur existe dans la base de données
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // Vérification du mot de passe hashé
                if (password_verify($password, $user['password'])) {
                    // L'utilisateur est authentifié
                    // Créer une instance de la classe User avec les données récupérées
                    $user = new User(
                        $user['prenom'],
                        $user['nom'],
                        $user['email'],
                        $user['tel'],
                        $user['date_naissance'],
                        $user['genre'],
                        $user['taille'],
                        $user['poids'],
                        $user['club'],
                        $user['niveau_championnat'],
                        $user['poste'],
                        $user['objectifs'],
                        $user['password'],
                        $user['created_at'],
                        $user['confirmed'],
                        $user['token']
                    );

                    // Stocker les informations utilisateur dans la session
                    $_SESSION['user'] = $user;

                    // Indiquer que l'utilisateur est authentifié
                    $_SESSION['user_authenticated'] = true;

                    // Afficher les informations de l'utilisateur directement
                    include_once(__DIR__ . '/../views/utilisateur.view.php');
                    exit;
                } else {
                    // Mot de passe incorrect
                    return false;
                }
            } else {
                // L'utilisateur n'existe pas
                return false;
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de requête de manière appropriée
            die("Erreur de requête : " . $e->getMessage());
        }
    }
}

// Utilisation de la classe LoginController
$loginController = new LoginController();

// Définir les messages par défaut
$successMessage = "";
$errorMessage = "";

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Validation des données d'identification
    if ($email && $password) {
        if ($loginController->loginUser($email, $password)) {
            // L'utilisateur est authentifié
            // Pas besoin de message de succès car les informations seront affichées sur cette même page
        } else {
            // L'utilisateur n'est pas authentifié
            // Définir le message d'erreur
            $errorMessage = "Identifiants incorrects. Veuillez réessayer.";
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
</head>

<body>
    <h1>Login</h1>
    <!-- Afficher le message d'erreur -->
    <?php if ($errorMessage): ?>
        <div class="error"><?= $errorMessage ?></div>
    <?php endif; ?>
</body>
</html>
