<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\UserModel;
use App\Models\InvestmentModel;

class AdminController extends BaseController {

    public function index() {
        $this->view('admin/index', [
            'users' => (new UserModel())->all(),
            'total_invest' => (new InvestmentModel())->total()
        ]);
    }
}
