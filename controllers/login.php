<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de configuration de la base de données
$config = require_once(__DIR__ . '/../config/database.php');
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
            $sql = "SELECT * FROM inscription WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute(array(':email' => $email));

            if ($stmt->rowCount() > 0) {
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $userData['password'])) {
                    // Création de l'instance de l'utilisateur avec des données récupérées
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

                    // Stockage de l'instance $user dans la session
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user'] = serialize($user); // Serialize pour stocker l'objet dans la session
                    $_SESSION['user_prenom'] = $user->getPrenom(); // Ajoutez cette ligne pour stocker le prénom
                    $_SESSION['email'] = $user->getEmail();  // Stocker l'email de l'utilisateur dans la session

                    // Redirection vers la page utilisateur.view.php
                    header('Location: https://levelnext.fr/views/utilisateur.view.php');
                    // include_once(__DIR__ . '/../views/utilisateur.view.php');
                    exit();
                } else {
                    return "Identifiants incorrects. Veuillez réessayer.";
                }
            } else {
                return "Identifiants incorrects. Veuillez réessayer.";
            }
        }  catch (PDOException $e) {
            // Log the error for debugging purposes
            error_log("Erreur de requête : " . $e->getMessage());
        
            // Afficher un message d'erreur générique à l'utilisateur
            die("Une erreur s'est produite. Veuillez réessayer ultérieurement.");
        }
    }

    public function updateUserField($userEmail, $field, $value)
    {
        // Vérifier si le champ est autorisé
        $allowedFields = [
            'prenom', 'nom', 'email', 'tel',
            'date_naissance', 'genre', 'taille', 'poids',
            'club', 'niveau_championnat', 'poste', 'objectifs'
        ];

        if (!in_array($field, $allowedFields)) {
            return '<div class="error">Modification non autorisée.</div>';
        }

        try {
            // Préparer la requête de mise à jour
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
}

// Utilisation de la classe LoginController
$loginController = new LoginController();

// Définir les messages par défaut
$successMessage = "OUI";
$errorMessage = "NON";

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
    <h1>Login</h1>
    <!-- Afficher le message d'erreur -->
    <?php if ($errorMessage) : ?>
        <div class="error"><?= $errorMessage ?></div>
    <?php endif; ?>
</body>

</html>