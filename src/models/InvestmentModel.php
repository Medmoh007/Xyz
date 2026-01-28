<?php
namespace App\Models;

use App\Lib\BaseModel;

class InvestmentModel extends BaseModel {
    protected string $table = 'investments';

    public function byUser(int $userId) {
        return $this->where('user_id', $userId);
    }

    public function total() {
        return $this->db->query("SELECT SUM(amount) FROM investments")->fetchColumn();
    }
}
