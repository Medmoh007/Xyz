<?php
namespace App\Models;

class ReferralModel
{
    public function generateReferralCode($userId)
    {
        return 'REF' . strtoupper(substr(md5($userId . time()), 0, 6));
    }
    
    public function updateUserReferralCode($userId, $code)
    {
        return true;
    }
    
    public function getReferralStats($userId)
    {
        return [
            'total_referrals' => 5,
            'active_referrals' => 3,
            'total_commissions' => 150.75
        ];
    }
    
    public function getReferralList($userId, $level = null, $limit = 10)
    {
        return [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'registration_date' => date('Y-m-d'),
                'investment_amount' => 500,
                'level' => 1
            ]
        ];
    }
    
    public function requestWithdrawal($userId, $amount)
    {
        return ['success' => true, 'message' => 'Retrait traité avec succès'];
    }
}