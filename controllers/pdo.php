<?php
// Charger la configuration de la base de données (chemin robuste)
$config = require __DIR__ . '/../config/database.php';

// Créer une instance PDO pour la connexion à la base
$dsn = "mysql:host={$config['host']};dbname={$config['database']}";

try {
    $pdo = new PDO($dsn, $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Retourner l'objet PDO pour inclusion ailleurs
return $pdo;
