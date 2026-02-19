<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class WithdrawModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(int $userId, float $amount, string $address, string $network = 'TRC20'): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO withdrawals (user_id, amount, address, network, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        return $stmt->execute([$userId, $amount, $address, $network]);
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM withdrawals 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, int $userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM withdrawals 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE withdrawals 
            SET status = ?, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$status, $id]);
    }
}