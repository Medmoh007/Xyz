<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\InvestmentModel;
use App\Models\DepositModel;

class DashboardController extends BaseController
{
    private UserModel $userModel;
    private WalletModel $walletModel;
    private InvestmentModel $investmentModel;
    private DepositModel $depositModel;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();

        $this->userModel       = new UserModel();
        $this->walletModel     = new WalletModel();
        $this->investmentModel = new InvestmentModel();
        $this->depositModel    = new DepositModel();
    }

    public function index(): void
    {
        $userId = $_SESSION['user_id'];

        // Utilisateur et solde
        $user = $this->userModel->find($userId);
        $user['balance'] = $this->walletModel->getAvailableBalance($userId);

        // Investissements de l'utilisateur
        $investments = $this->investmentModel->getUserInvestments($userId);
        $activeInvestments = array_filter($investments, fn($i) => $i['status'] === 'active');
        $totalInvested = array_sum(array_column($activeInvestments, 'amount'));
        $totalProfits  = array_sum(array_column($investments, 'total_profit'));
        $roi = $totalInvested > 0 ? ($totalProfits / $totalInvested) * 100 : 0;

        // Dépôts approuvés (pour info)
        $deposits = $this->depositModel->getUserDeposits($userId);
        $approvedDeposits = array_filter($deposits, fn($d) => $d['status'] === 'approved');
        $totalDeposits = array_sum(array_column($approvedDeposits, 'amount'));

        // Activité récente (exemple simplifié)
        $recentActivity = $this->buildRecentActivity($userId, $investments, $deposits);

        $this->view('pages/dashboard', [
            'user'               => $user,
            'wallet'             => $this->walletModel->getByUser($userId),
            'investments'        => $investments,
            'activeInvestments'  => $activeInvestments,
            'totalInvested'      => $totalInvested,
            'totalProfits'       => $totalProfits,
            'totalDeposits'      => $totalDeposits,
            'roi'                => $roi,
            'recentActivity'     => $recentActivity,
            'title'              => 'Dashboard | COMCV Trading'
        ]);
    }

    /**
     * Construit un tableau d'activités récentes (mix investissements, dépôts)
     */
    private function buildRecentActivity(int $userId, array $investments, array $deposits): array
    {
        $activities = [];

        // Derniers investissements
        foreach (array_slice($investments, 0, 3) as $inv) {
            $activities[] = [
                'type'   => 'investment',
                'icon'   => 'chart-line',
                'title'  => 'Nouvel investissement',
                'amount' => $inv['amount'],
                'time'   => $this->timeAgo($inv['created_at'])
            ];
        }

        // Derniers dépôts approuvés
        $approved = array_filter($deposits, fn($d) => $d['status'] === 'approved');
        foreach (array_slice($approved, 0, 3) as $dep) {
            $activities[] = [
                'type'   => 'deposit',
                'icon'   => 'arrow-down',
                'title'  => 'Dépôt approuvé',
                'amount' => $dep['amount'],
                'time'   => $this->timeAgo($dep['created_at'])
            ];
        }

        // Trier par date (la plus récente en premier)
        usort($activities, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));

        return $activities;
    }

    /**
     * Formatage relatif du temps (ex: "il y a 2 jours")
     */
    private function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) return 'à l\'instant';
        if ($diff < 3600) return 'il y a ' . floor($diff / 60) . ' min';
        if ($diff < 86400) return 'il y a ' . floor($diff / 3600) . ' h';
        if ($diff < 604800) return 'il y a ' . floor($diff / 86400) . ' j';
        return date('d/m/Y', $time);
    }
}