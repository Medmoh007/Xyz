<?php

namespace App\Models;

class TransactionModel extends BaseModel
{
    protected string $table = 'transactions';

    public function getUserTransactions(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE user_id = :uid
            ORDER BY created_at DESC
        ");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }
}
