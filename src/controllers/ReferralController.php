<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\ReferralModel;

class ReferralController extends BaseController {

    public function index() {
        $userId = $_SESSION['user']['id'];

        $this->view('pages/referrals', [
            'referrals' => (new ReferralModel())->byUser($userId),
            'link' => config('base_url') . '/register?ref=' . $userId
        ]);
    }
}
