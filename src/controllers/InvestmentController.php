<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\InvestmentModel;
use App\Models\WalletModel;
use App\Models\UserModel;

class InvestmentController extends BaseController
{
    private InvestmentModel $investmentModel;
    private WalletModel $walletModel;
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->investmentModel = new InvestmentModel();
        $this->walletModel = new WalletModel();
        $this->userModel = new UserModel();
        $this->checkAuth(); // redirige vers /login si non connecté
    }

    /**
     * Page des investissements
     */
    public function index()
    {
        $userId = $_SESSION['user_id'];

        // Récupérer tous les plans d'investissement
        $plans = $this->investmentModel->getAllPlans();

        // Récupérer les investissements de l'utilisateur
        $investments = $this->investmentModel->getUserInvestments($userId);

        // Récupérer l'utilisateur et son solde
        $user = $this->userModel->find($userId);
        $user['balance'] = $this->walletModel->getAvailableBalance($userId);

        // Statistiques
        $totalInvested = array_sum(array_column($investments, 'amount'));
        $totalProfits = array_sum(array_column($investments, 'total_profit'));
        $activeInvestments = count(array_filter($investments, fn($inv) => $inv['status'] === 'active'));

        $this->view('pages/investments', [
            'plans'              => $plans,
            'investments'        => $investments,
            'user'               => $user,
            'totalInvested'      => $totalInvested,
            'totalProfits'       => $totalProfits,
            'activeInvestments'  => $activeInvestments,
        ]);
    }

    /**
     * Acheter un plan d'investissement
     */
    public function buy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/investments');
        }

        // Validation CSRF
        if (!$this->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Requête invalide (CSRF)');
        }

        $userId = $_SESSION['user_id'];
        $planId = (int) ($_POST['plan_id'] ?? 0);
        $planKey = $_POST['plan_key'] ?? ''; // si on utilise les clés starter/professional/premium

        // Récupérer tous les plans
        $allPlans = $this->investmentModel->getAllPlans();

        // Si on a une clé (starter, professional...), chercher le plan par clé
        if ($planKey && isset($allPlans[$planKey])) {
            $plan = $allPlans[$planKey];
            $amount = $plan['min']; // on investit le minimum par défaut ? À adapter
        } else {
            // Sinon, chercher par ID (si vos plans sont stockés en DB)
            // Pour l'instant, on utilise un fallback
            $this->redirect('/investments?error=plan_invalid');
        }

        // Vérifier le solde disponible
        $available = $this->walletModel->getAvailableBalance($userId);
        if ($available < $amount) {
            $this->redirect('/investments?error=insufficient_balance');
        }

        // Débiter le wallet
        if (!$this->walletModel->lockFunds($userId, $amount)) {
            $this->redirect('/investments?error=lock_failed');
        }

        // Créer l'investissement
        $investmentId = $this->investmentModel->create($userId, $planId ?? 0, $amount);
        if (!$investmentId) {
            // Rollback : débloquer les fonds
            $this->walletModel->unlockFunds($userId, $amount);
            $this->redirect('/investments?error=investment_failed');
        }

        $this->redirect('/investments?success=investment_created');
    }
}