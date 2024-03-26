<?php

class db
{
    private $pdo;

    public function connect()
    {
        $config = $config = require_once 'config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['database']}";
        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("set names utf8");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    public function executeQuery($query, $params)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
    
    // Fonction pour obtenir l'instance PDO pour des utilisations personnalisées
    public function getPdo()
    {
        return $this->pdo;
    }
}
?>
