<?php

namespace App\Models;

class InvestmentModel extends BaseModel
{
    protected string $table = 'investments';

    public function getByUser(int $userId): array
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
