<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = filter_input(INPUT_POST, 'action_type', FILTER_DEFAULT);

    // EXECUTE REMOVAL OF PLACED USER FROM THE MATRIX ROW
    if ($actionType === 'remove') {
        $targetUserId = filter_input(INPUT_POST, 'target_user_id', FILTER_VALIDATE_INT);

        if (!$targetUserId) {
            header("Location: genealogy.php?status=error");
            exit;
        }

        try {
            // Delete the unique record entry for this child node user
            $sql = "DELETE FROM network_tree WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $targetUserId]);

            header("Location: genealogy.php?status=removed");
            exit;
        } catch (PDOException $e) {
            header("Location: genealogy.php?status=error");
            exit;
        }
    }

    // EXECUTE PLACEMENT ADDITION TO THE MATRIX ROW
    if ($actionType === 'add') {
        $parentId       = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT);
        $selectedUserId = filter_input(INPUT_POST, 'selected_user_id', FILTER_VALIDATE_INT);
        $targetLeg      = filter_input(INPUT_POST, 'target_leg', FILTER_DEFAULT);

        if (!$parentId || !$selectedUserId || !in_array($targetLeg, ['Left', 'Right'])) {
            header("Location: genealogy.php?status=error");
            exit;
        }

        try {
            // Guard clause to make sure the target parent doesn't already have someone on that exact leg position
            $checkSql = "SELECT id FROM network_tree WHERE parent_id = :parent_id AND position = :position LIMIT 1";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                'parent_id' => $parentId,
                'position'  => $targetLeg
            ]);

            if ($checkStmt->fetch()) {
                header("Location: genealogy.php?status=error");
                exit;
            }

            // Insert single row map setup perfectly correlating with your screenshots
            $insertSql = "INSERT INTO network_tree (user_id, parent_id, position) VALUES (:user_id, :parent_id, :position)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute([
                'user_id'   => $selectedUserId,
                'parent_id' => $parentId,
                'position'  => $targetLeg
            ]);

            header("Location: genealogy.php?status=success");
            exit;
        } catch (PDOException $e) {
            header("Location: genealogy.php?status=error");
            exit;
        }
    }
}

header("Location: genealogy.php");
exit;