<?php
/**
 * Fichier : database.php
 * Emplacement : /config/database.php
 * Rôle : Gérer la connexion PDO à la base de données
 */

class Database
{
    private static $instance = null;
    private $pdo;

    private $host = "localhost";
    private $dbname = "iua_gestion_notes";
    private $username = "root";
    private $password = "";

    // Constructeur privé (empêche l'instanciation directe)
    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données.");
        }
    }

    // Récupérer l’instance unique
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Récupérer l’objet PDO
    public function getConnection()
    {
        return $this->pdo;
    }
}
