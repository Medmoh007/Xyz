<?php
namespace App\Models;

use App\Lib\BaseModel;

class ReferralModel extends BaseModel {
    protected string $table = 'referrals';

    public function byUser(int $userId) {
        return $this->where('referrer_id', $userId);
    }
}
