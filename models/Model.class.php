<?php
abstract class Model
{
    private static $pdo;

    /**
     * Initialise la connexion PDO en chargeant la conf, si besoin
     */
    private static function setBdd()
    {
        // Chargement de la configuration de la base de données avec chemin MVC robuste
        $config = require __DIR__ . '/../config/database.php';

        // Création d'une instance PDO avec la configuration chargée
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        self::$pdo = new PDO($dsn, $config['user'], $config['password']);

        // Configuration de PDO pour générer des exceptions en cas d'erreur
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Définition du jeu de caractères à utf8
        self::$pdo->exec("set names utf8");
    }

    /**
     * Retourne la seule connexion BDD utilisée partout via ModelManager
     * @return PDO
     */
    protected function getBdd()
    {
        if (self::$pdo === null) {
            self::setBdd();
        }
        return self::$pdo;
    }
}
