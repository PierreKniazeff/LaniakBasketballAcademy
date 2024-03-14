<?php
session_start();
require_once('crud.php'); // Importez votre classe CRUD pour accéder aux méthodes de gestion d'utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des informations de connexion
    // Vous devez implémenter cette partie en fonction de votre structure de base de données et de votre méthode de hashage des mots de passe
    $user = $db->getUserByEmail($email); // Méthode hypothétique pour récupérer un utilisateur par son email depuis la base de données

    if ($user && password_verify($password, $user['password'])) {
        // Création d'un cookie pour identifier l'utilisateur
        setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // Cookie valide pendant 30 jours

        // Redirection vers une page de profil ou une page d'accueil
        header("Location: profile.php"); // Redirigez l'utilisateur vers la page de profil
        exit();
    } else {
        // Gestion des erreurs de connexion
        echo "Email ou mot de passe incorrect.";
    }
}

