<?php
namespace App\Models;

use App\Lib\BaseModel;

class TransactionModel extends BaseModel {
    protected string $table = 'transactions';

    public function balance(int $userId): float {
        $stmt = $this->db->prepare(
            "SELECT SUM(CASE WHEN type='deposit' THEN amount ELSE -amount END)
             FROM transactions WHERE user_id=?"
        );
        $stmt->execute([$userId]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }
}
