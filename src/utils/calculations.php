<?php
namespace App\Utils;

use App\Models\TransactionModel;

class Calculations {
    public static function applyInterest(float $amount, int $userId): void {
        $interest = $amount * 0.05; // 5% fictif
        (new TransactionModel())->create([
            'user_id' => $userId,
            'amount' => $interest,
            'type' => 'deposit',
            'status' => 'approved'
        ]);
    }
}
