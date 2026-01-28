<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\InvestmentModel;
use App\Utils\Calculations;

class InvestmentController extends BaseController {

    public function invest() {
        if ($_POST) {
            (new InvestmentModel())->create([
                'user_id' => $_SESSION['user']['id'],
                'amount' => $_POST['amount'],
                'plan' => $_POST['plan'] ?? 'basic'
            ]);

            Calculations::applyInterest($_POST['amount'], $_SESSION['user']['id']);

            flash('success', 'Investissement simulé');
            return $this->redirect('/dashboard');
        }
        $this->view('pages/invest');
    }
}
