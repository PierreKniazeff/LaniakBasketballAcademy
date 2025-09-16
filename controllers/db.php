<?php
// Secure & robust database class (PHP Data Objects wrapper)
// Use: $db = new db(); $db->connect();

class db
{
    private $pdo;

    /**
     * Establishes the connection to the database using config/database.php
     */
    public function connect()
    {
        // Always use a robust path to config file
        $config = require __DIR__ . '/../config/database.php'; // Assumes db.php is in /models/ or /core/
        $dsn = "mysql:host={$config['host']};dbname={$config['database']}";

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("set names utf8");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    /**
     * Executes a prepared query in a transaction
     *
     * @param string $query
     * @param array $params
     */
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

    /**
     * Get the PDO connection instance (for custom queries)
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
