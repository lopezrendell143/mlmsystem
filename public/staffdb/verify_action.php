<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kyc_id'], $_POST['status_action'])) {
    $kycId = intval($_POST['kyc_id']);
    $action = $_POST['status_action']; // 'Approved' or 'Rejected'
    
    $status = ($action === 'Approved') ? 'Approved' : 'Rejected';

    try {
        $pdo->beginTransaction();

        // 1. Update status tracking flag within kyc_reviews table grid
        $updateSql = "UPDATE kyc_reviews SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($updateSql);
        $stmt->execute(['status' => $status, 'id' => $kycId]);

        // 2. Insert record stream directly to your systemic audit logs history chain
        $logSql = "INSERT INTO audit_logs (operator_role, operator_name, action_description, target_node, ip_address, created_at) 
                   VALUES ('Staff', :operator, :descr, :target, :ip, NOW())";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'operator' => $_SESSION['username'] ?? 'Ops_Desk',
            'descr' => "{$status} identity verification documents for KYC entry ID {$kycId}",
            'target' => "STX-" . $kycId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

// Bounce operations clean right back to the dynamic display layout desk
header("Location: member_verification.php");
exit;