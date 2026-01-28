<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\TransactionModel;

class WithdrawalController extends BaseController {

    public function withdraw() {
        $userId = $_SESSION['user']['id'];
        $model = new TransactionModel();

        if ($_POST) {
            if ($model->balance($userId) >= $_POST['amount']) {
                $model->create([
                    'user_id' => $userId,
                    'amount' => $_POST['amount'],
                    'type' => 'withdrawal',
                    'status' => 'pending'
                ]);
                flash('success', 'Demande envoyée');
            } else {
                flash('error', 'Solde insuffisant');
            }
            return $this->redirect('/withdrawals');
        }

        $this->view('pages/withdrawals');
    }
}
