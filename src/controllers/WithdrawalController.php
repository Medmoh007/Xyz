<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\WithdrawalModel;
use App\Models\WalletModel;

class WithdrawalController extends BaseController
{
    private WithdrawalModel $withdrawalModel;
    private WalletModel $walletModel;

    public function __construct()
    {
        parent::__construct();
        $this->withdrawalModel = new WithdrawalModel();
        $this->walletModel = new WalletModel();
    }

    /**
     * Affiche l'historique des retraits (vue dédiée)
     */
    public function index()
    {
        $userId = $_SESSION['user']['id'];
        $withdrawals = $this->withdrawalModel->getByUser($userId);
        $stats = [
            'total_withdrawn' => $this->withdrawalModel->getTotalWithdrawn($userId),
            'pending_count'   => $this->withdrawalModel->countPending($userId)
        ];

        $this->view('pages/withdrawal', [
            'withdrawals' => $withdrawals,
            'stats'       => $stats,
            'title'       => 'Historique des retraits'
        ]);
    }

    /**
     * API AJAX : calcul des frais
     */
    public function calculateFees()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $amount = (float) ($_POST['amount'] ?? 0);
        $network = $_POST['network'] ?? 'TRC20';

        $fees = $this->withdrawalModel->calculateFees($amount, $network);
        $netAmount = $amount - $fees;

        $this->json([
            'fees'          => $fees,
            'net_amount'    => $netAmount,
            'formatted_fees'=> '$' . number_format($fees, 2),
            'formatted_net' => '$' . number_format($netAmount, 2)
        ]);
    }

    /**
     * Annulation d'une demande (redirige vers /wallet après)
     * Utilisé par le wallet.
     */
    public function cancel(int $id)
    {
        // Rediriger vers la méthode du WalletController pour garder la logique cohérente
        $walletController = new WalletController();
        $walletController->cancelWithdrawal($id);
    }
}