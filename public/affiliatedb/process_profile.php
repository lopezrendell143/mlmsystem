<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($fullName)) {
        header("Location: profile.php?status=error");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Scenario A: Updating full_name and using plain-text password mapping
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                header("Location: profile.php?status=error");
                exit;
            }

            // Fixed: Storing raw plain-text password to match existing database entries
            $sql = "UPDATE users 
                    SET full_name = :full_name, password = :password 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'full_name' => $fullName,
                'password'  => $newPassword,
                'id'        => $userId
            ]);
        } 
        // Scenario B: Updating full name only
        else {
            $sql = "UPDATE users 
                    SET full_name = :full_name 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'full_name' => $fullName,
                'id'        => $userId
            ]);
        }

        $pdo->commit();
        
        // Fixed: Synchronize current session storage so navbar/sidebar name updates immediately
        $_SESSION['username'] = $fullName;

        header("Location: profile.php?status=updated");
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header("Location: profile.php?status=error");
        exit;
    }
} else {
    header("Location: profile.php");
    exit;
}