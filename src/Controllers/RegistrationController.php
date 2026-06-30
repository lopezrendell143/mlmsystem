<?php
namespace Src\Controllers;

// Import the correct namespace paths for your services
use Src\Services\TreeService;
use Src\Services\CommissionEngine;
use Exception;

class RegistrationController {
    private $db;
    private $treeService;
    private $commissionEngine;

    public function __construct() {
        // Pull the live global PDO connection instance
        global $pdo;
        
        if (!isset($pdo)) {
            require_once __DIR__ . '/../../config/database.php';
        }
        
        $this->db = $pdo;
        
        $this->treeService = new TreeService();
        $this->commissionEngine = new CommissionEngine($this->treeService);
    }

    public function handleRegistration($formData) {
        try {
            // 1. Check if email or username already exists to prevent duplicate key exceptions
            $checkStmt = $this->db->prepare("SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1");
            $checkStmt->execute([
                ':email'    => $formData['email'],
                ':username' => $formData['username']
            ]);
            if ($checkStmt->fetch()) {
                return ['success' => false, 'error' => 'The email address or username is already registered.'];
            }

            // 2. Insert core profile record safely
            $stmt = $this->db->prepare("
                INSERT INTO users (full_name, username, email, password, sponsor_id, placement, role) 
                VALUES (:full_name, :username, :email, :password, :sponsor_id, :placement, 'Affiliate')
            ");
            
            // FIXED: Stripped password_hash encryption out to store passwords as plain-text strings
            $plainPassword = $formData['password'];
            
            $stmt->execute([
                ':full_name'  => $formData['full_name'],
                ':username'   => $formData['username'],
                ':email'      => $formData['email'],
                ':password'   => $plainPassword,
                ':sponsor_id' => $formData['sponsor_id'],
                ':placement'  => $formData['placement']
            ]);

            $newUserId = $this->db->lastInsertId();

            // 3. Put user into network tree leg assignment
            if (isset($this->treeService)) {
                $this->treeService->placeNode($newUserId, $formData['sponsor_id'], $formData['placement']);
            }

            // 4. Process direct commissions if they selected a growth plan tier package right away
            if (isset($this->commissionEngine) && isset($formData['plan_price']) && $formData['plan_price'] > 0) {
                $this->commissionEngine->processPlanPurchaseBonus($newUserId, $formData['plan_price']);
            }

            return ['success' => true, 'user_id' => $newUserId];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Database Transaction Error: ' . $e->getMessage()];
        }
    }
}