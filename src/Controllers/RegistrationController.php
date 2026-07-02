<?php
namespace Src\Controllers;

use PDO;
use Exception;

class RegistrationController {
    
    public function handleRegistration($data) {
        require_once __DIR__ . '/../../config/database.php';
        
        try {
            $pdo->beginTransaction();
            
            // 1. FIXED: Added the 'placement' column so it saves Left/Right to the users table as well
            $userSql = "INSERT INTO users (full_name, username, email, password, sponsor_id, role, placement, created_at) 
                        VALUES (:full_name, :username, :email, :password, :sponsor_id, 'Affiliate', :placement, NOW())";
            
            $userStmt = $pdo->prepare($userSql);
            $userStmt->execute([
                'full_name'  => $data['full_name'],
                'username'   => $data['username'],
                'email'      => $data['email'],
                'password'   => $data['password'], 
                'sponsor_id' => $data['sponsor_id'],
                'placement'  => $data['placement'] // Saves 'Left' or 'Right' directly to users table
            ]);
            
            $newUserId = $pdo->lastInsertId();
            
            // 2. Insert into your existing network_tree table
            $treeSql = "INSERT INTO network_tree (user_id, parent_id, position, created_at) 
                        VALUES (:user_id, :parent_id, :position, NOW())";
            
            $treeStmt = $pdo->prepare($treeSql);
            $treeStmt->execute([
                'user_id'   => $newUserId,
                'parent_id' => $data['sponsor_id'], 
                'position'  => $data['placement']   
            ]);
            
            $pdo->commit();
            return ['success' => true];
            
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}