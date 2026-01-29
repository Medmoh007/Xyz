<?php

namespace App\Models;

class ReferralModel extends BaseModel
{
    protected string $table = 'referrals';

    public function getReferrals(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE referrer_id = :uid
        ");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }
}
