<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\WithdrawModel;
use App\Models\WalletModel;

class WithdrawController extends BaseController
{
    public function index()
    {
        $userId = $_SESSION['user']['id'];
        
        // Modèles
        $withdrawModel = new WithdrawModel();
        $userModel = new UserModel();
        $walletModel = new WalletModel();
        
        // Données
        $withdrawals = $withdrawModel->getByUser($userId);
        $user = $userModel->find($userId);
        $wallet = $walletModel->getByUser($userId);
        
        // Configurations
        $config = [
            'min_amount' => 10,
            'fee_trc20' => 1,
            'fee_erc20' => 10,
            'networks' => [
                'TRC20' => 'TRC20 (USDT) - Frais: 1 USDT',
                'ERC20' => 'ERC20 (USDT) - Frais: 10 USDT'
            ]
        ];
        
        $this->render('pages/withdraw', [
            'withdrawals' => $withdrawals,
            'user' => $user,
            'wallet' => $wallet,
            'config' => $config
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /withdraw');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        
        // Validation
        $amount = (float)($_POST['amount'] ?? 0);
        $address = trim($_POST['address'] ?? '');
        $network = $_POST['network'] ?? 'TRC20';
        
        // Vérification de l'adresse
        if ($network === 'TRC20' && !preg_match('/^T[a-zA-Z0-9]{33}$/', $address)) {
            $_SESSION['error'] = 'Adresse TRC20 invalide';
            header('Location: /withdraw');
            exit;
        }
        
        // Vérification du montant
        $minAmount = ($network === 'TRC20') ? 10 : 20;
        if ($amount < $minAmount) {
            $_SESSION['error'] = "Montant minimum: $minAmount USDT";
            header('Location: /withdraw');
            exit;
        }
        
        // Vérification du solde
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        $fee = ($network === 'TRC20') ? 1 : 10;
        $total = $amount + $fee;
        
        if ($user['balance'] < $total) {
            $_SESSION['error'] = 'Solde insuffisant';
            header('Location: /withdraw');
            exit;
        }
        
        // Création de la demande de retrait
        $withdrawModel = new WithdrawModel();
        $success = $withdrawModel->create($userId, $amount, $address, $network);
        
        if ($success) {
            // Déduction du solde
            $newBalance = $user['balance'] - $total;
            $userModel->updateBalance($userId, $newBalance);
            
            $_SESSION['success'] = 'Demande de retrait envoyée avec succès';
        } else {
            $_SESSION['error'] = 'Erreur lors de la demande de retrait';
        }
        
        header('Location: /withdraw');
        exit;
    }

    public function cancel(int $id)
    {
        $userId = $_SESSION['user']['id'];
        $withdrawModel = new WithdrawModel();
        
        $withdrawal = $withdrawModel->getById($id, $userId);
        
        if (!$withdrawal) {
            $_SESSION['error'] = 'Demande non trouvée';
            header('Location: /withdraw');
            exit;
        }
        
        if ($withdrawal['status'] !== 'pending') {
            $_SESSION['error'] = 'Seules les demandes en attente peuvent être annulées';
            header('Location: /withdraw');
            exit;
        }
        
        // Annulation
        $withdrawModel->updateStatus($id, 'cancelled');
        
        // Remboursement
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $fee = ($withdrawal['network'] === 'TRC20') ? 1 : 10;
        $refundAmount = $withdrawal['amount'] + $fee;
        $newBalance = $user['balance'] + $refundAmount;
        $userModel->updateBalance($userId, $newBalance);
        
        $_SESSION['success'] = 'Demande de retrait annulée';
        header('Location: /withdraw');
        exit;
    }
}