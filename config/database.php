<?php

class Database {
    // Database Configuration Parameters
    private $host     = 'localhost';
    private $db_name  = 'mlmsystem_db'; 
    private $username = 'root';        
    private $password = '';            
    
    private static $instance = null;
    private $pdo;

    // Private constructor prevents direct instantiation outside of this class
    private function __construct() {
        try {
            // Instantiate a secure PDO database connection channel
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4", 
                $this->username, 
                $this->password
            );
            
            // Configure error reporting mode to throw exceptions for debugging
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode to associative arrays
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // If connection drops, kill the execution thread securely
            die("Database Connection Engine Failed: " . $e->getMessage());
        }
    }

    // Static method to get the single class instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Public method to retrieve the active PDO channel connection string
    public function getConnection() {
        return $this->pdo;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton database class.");
    }
}

// Global backward-compatibility wrapper variable
// This ensures that any files using "$pdo" directly won't break.
$pdo = Database::getInstance()->getConnection();