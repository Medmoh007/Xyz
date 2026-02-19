<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\WalletModel;
use App\Models\WithdrawalModel;

class WalletController extends BaseController
{
    private WalletModel $walletModel;
    private WithdrawalModel $withdrawalModel;

    public function __construct()
    {
        parent::__construct();
        $this->walletModel = new WalletModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    public function index(): void
    {
        $userId = $_SESSION['user']['id'];
        $wallet = $this->walletModel->getByUser($userId);

        if (!$wallet) {
            $wallet = $this->walletModel->create($userId);
        }

        // Récupérer l'historique des retraits
        $withdrawals = $this->withdrawalModel->getByUser($userId);

        $this->view('pages/wallet', [
            'wallet'      => $wallet,
            'withdrawals' => $withdrawals,
            'title'       => 'Wallet | COMCV'
        ]);
    }

    /**
     * Formulaire de retrait et soumission
     */
    public function withdraw(): void
    {
        $userId = $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleWithdrawRequest();
        } else {
            // Afficher le formulaire (déjà intégré dans wallet/index)
            $this->redirect('/wallet');
        }
    }

    private function handleWithdrawRequest(): void
    {
        $userId = $_SESSION['user']['id'];
        $amount = (float) ($_POST['amount'] ?? 0);
        $address = trim($_POST['address'] ?? '');
        $network = $_POST['network'] ?? 'TRC20';

        // 1. Validation du montant
        if ($amount < 50) { // Seuil minimum de retrait
            $_SESSION['error'] = "Le montant minimum de retrait est de 50 USDT.";
            $this->redirect('/wallet');
        }

        // 2. Validation adresse (exemple pour TRC20)
        if ($network === 'TRC20' && !preg_match('/^T[a-zA-Z0-9]{33}$/', $address)) {
            $_SESSION['error'] = "Adresse TRC20 invalide.";
            $this->redirect('/wallet');
        }

        // 3. Vérifier solde disponible
        $wallet = $this->walletModel->getByUser($userId);
        $available = $wallet['balance'] - $wallet['locked_balance'];
        if ($available < $amount) {
            $_SESSION['error'] = "Solde insuffisant. Disponible: $" . number_format($available, 2);
            $this->redirect('/wallet');
        }

        // 4. Frais (optionnel)
        $fees = $this->withdrawalModel->calculateFees($amount, $network);
        $netAmount = $amount - $fees;
        if ($netAmount <= 0) {
            $_SESSION['error'] = "Le montant après frais est négatif.";
            $this->redirect('/wallet');
        }

        // 5. Vérifier qu'il n'y a pas déjà une demande en attente
        if ($this->withdrawalModel->hasPendingWithdrawal($userId)) {
            $_SESSION['error'] = "Vous avez déjà une demande de retrait en attente.";
            $this->redirect('/wallet');
        }

        $db = \App\Lib\Database::getInstance();
        try {
            $db->beginTransaction();

            // 6. Créer la demande de retrait (statut pending)
            $withdrawalId = $this->withdrawalModel->create([
                'user_id'         => $userId,
                'amount'          => $amount,
                'wallet_address'  => $address,
                'network'         => $network,
                'status'          => 'pending'
            ]);

            // 7. Geler les fonds : balance -= amount, locked_balance += amount
            $this->walletModel->lockFunds($userId, $amount);

            $db->commit();
            $_SESSION['success'] = "Demande de retrait soumise. Montant après frais: $" . number_format($netAmount, 2);
        } catch (\Exception $e) {
            $db->rollBack();
            error_log("Erreur demande retrait : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la demande de retrait.";
        }

        $this->redirect('/wallet');
    }

    /**
     * Annuler une demande de retrait en attente
     */
    public function cancelWithdrawal(int $id): void
    {
        $userId = $_SESSION['user']['id'];

        $withdrawal = $this->withdrawalModel->getById($id, $userId);
        if (!$withdrawal || $withdrawal['status'] !== 'pending') {
            $_SESSION['error'] = "Demande de retrait introuvable ou déjà traitée.";
            $this->redirect('/wallet');
        }

        $db = \App\Lib\Database::getInstance();
        try {
            $db->beginTransaction();

            // 1. Mettre à jour le statut de la demande
            $this->withdrawalModel->updateStatus($id, 'cancelled');

            // 2. Débloquer les fonds : locked_balance -= amount, balance += amount
            $this->walletModel->unlockFunds($userId, $withdrawal['amount']);

            $db->commit();
            $_SESSION['success'] = "Demande de retrait annulée, vos fonds sont débloqués.";
        } catch (\Exception $e) {
            $db->rollBack();
            error_log("Erreur annulation retrait : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de l'annulation.";
        }

        $this->redirect('/wallet');
    }
}