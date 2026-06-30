<?php
namespace Src\Services;

use Src\Database\Connection;
use PDO;

class TreeService {
    private $db;

    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }

    // Safely puts a user into their chosen Left or Right leg position
    public function placeNode($userId, $parentId, $position) {
        $stmt = $this->db->prepare("
            INSERT INTO network_tree (user_id, parent_id, position) 
            VALUES (:user_id, :parent_id, :position)
        ");
        return $stmt->execute([
            'user_id'   => $userId,
            'parent_id' => $parentId,
            'position'  => $position
        ]);
    }

    // Traverses the structural genealogy upwards to find all active uplines
    public function getAncestors($userId) {
        $sql = "
            WITH RECURSIVE Upline AS (
                SELECT user_id, parent_id, position, 1 as level 
                FROM network_tree WHERE user_id = :user_id
                UNION ALL
                SELECT t.user_id, t.parent_id, t.position, u.level + 1
                FROM network_tree t
                INNER JOIN Upline u ON t.user_id = u.parent_id
            )
            SELECT * FROM Upline WHERE parent_id IS NOT NULL
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}