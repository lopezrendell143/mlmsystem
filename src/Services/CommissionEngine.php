<?php
namespace Src\Services;

use Exception;

class CommissionEngine {
    private $db;
    private $treeService;

    // Injecting our TreeService into the commission rules processor
    public function __construct(TreeService $treeService) {
        // Pull the live global PDO connection instance from your configuration path
        global $pdo;
        
        if (!isset($pdo)) {
            require_once __DIR__ . '/../../config/database.php';
        }
        
        $this->db = $pdo;
        $this->treeService = $treeService;
    }

    public function processPlanPurchaseBonus($buyerId, $amount) {
        try {
            $this->db->beginTransaction();

            // Find all matching uplines in the genealogy tree
            $ancestors = $this->treeService->getAncestors($buyerId);

            // Generational bonus cuts: Level 1 gets 10%, Level 2 gets 5%, Level 3 gets 2%
            $payoutRates = [1 => 0.10, 2 => 0.05, 3 => 0.02];

            foreach ($ancestors as $ancestor) {
                $level = $ancestor['level'];
                if (!isset($payoutRates[$level])) continue;

                $commissionValue = $amount * $payoutRates[$level];
                $uplineId = $ancestor['parent_id'];

                $this->creditWallet($uplineId, $commissionValue, 'UNILEVEL_BONUS', "Generation Payout from Downline User #{$buyerId}");
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("MLM Financial Core Exception: " . $e->getMessage());
            return false;
        }
    }

    private function creditWallet($userId, $amount, $type, $description) {
        $stmt = $this->db->prepare("
            INSERT INTO wallet_ledger (user_id, amount, type, description) 
            VALUES (:user_id, :amount, :type, :description)
        ");
        $stmt->execute([
            ':user_id'     => $userId,
            ':amount'      => $amount,
            ':type'        => $type,
            ':description' => $description
        ]);
    }
}