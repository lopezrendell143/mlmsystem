<?php
namespace Src\Controllers;

use Src\Database\Connection;
use Src\Services\TreeService;
use Src\Services\CommissionEngine;
use Exception;

class RegistrationController {
    private $db;
    private $treeService;
    private $commissionEngine;

    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
        $this->treeService = new TreeService();
        $this->commissionEngine = new CommissionEngine($this->treeService);
    }

    public function handleRegistration($formData) {
        try {
            // 1. Insert core profile record safely
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password, sponsor_id) 
                VALUES (:username, :email, :password, :sponsor_id)
            ");
            
            $hashedPassword = password_hash($formData['password'], PASSWORD_BCRYPT);
            
            $stmt->execute([
                'username'   => $formData['username'],
                'email'      => $formData['email'],
                'password'   => $hashedPassword,
                'sponsor_id' => $formData['sponsor_id']
            ]);

            $newUserId = $this->db->lastInsertId();

            // 2. Put user into network tree leg assignment
            $this->treeService->placeNode($newUserId, $formData['sponsor_id'], $formData['placement']);

            // 3. Process direct commissions if they selected a growth plan tier package right away
            if (isset($formData['plan_price']) && $formData['plan_price'] > 0) {
                $this->commissionEngine->processPlanPurchaseBonus($newUserId, $formData['plan_price']);
            }

            return ['success' => true, 'user_id' => $newUserId];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}