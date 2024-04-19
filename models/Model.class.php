<?php
abstract class Model
{
    private static $pdo;

    private static function setBdd()
    {
        // Chargement de la configuration de la base de données
        $config = require_once 'config/database.php';

        // Création d'une instance PDO avec la configuration chargée
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        self::$pdo = new PDO($dsn, $config['user'], $config['password']);

        // Configuration de PDO pour générer des exceptions en cas d'erreur
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définition du jeu de caractères à utf8
        self::$pdo->exec("set names utf8");
    }

    protected function getBdd(){
        if (self::$pdo === null) {
            self::setBdd();
        }
        return self::$pdo;
    }
}

