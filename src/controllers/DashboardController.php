<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\InvestmentModel;
use App\Models\TransactionModel;

class DashboardController extends BaseController {

    public function index() {
        $userId = $_SESSION['user']['id'];

        $this->view('pages/dashboard', [
            'balance' => (new TransactionModel())->balance($userId),
            'investments' => (new InvestmentModel())->byUser($userId)
        ]);
    }
}
