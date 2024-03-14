<?php
// Charger la configuration de la base de données
$config = require 'config/database.php';

// Créer une instance de la classe PDO pour la connexion à la base de données
$pdo = new PDO("mysql:dbname={$config['database']};host={$config['host']}", $config['user'], $config['password']);

// Configurer PDO pour qu'il génère des exceptions en cas d'erreur
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Définir le jeu de caractères à utf8
$pdo->exec("set names utf8");

// Retourner l'objet PDO
return $pdo;
