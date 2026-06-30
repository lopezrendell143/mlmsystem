<?php
// Database Configuration Parameters
$host     = 'localhost';
$db_name  = 'mlmsystem_db'; 
$username = 'root';        
$password = '';            

try {
    // Instantiate a secure PDO database connection channel
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // Configure error reporting mode to throw exceptions for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // If connection drops, kill the execution thread securely
    die("Database Connection Engine Failed: " . $e->getMessage());
}
?>