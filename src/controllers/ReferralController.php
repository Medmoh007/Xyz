<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\ReferralModel;

class ReferralController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
    }
    
    public function index()
{
    $data = [
        'title' => 'Programme de Parrainage | COMCV Trading',
        'user' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'referral_code' => 'COMCV123',
            'referral_balance' => 125.50
        ],
        'stats' => [
            'total_referrals' => 15,
            'active_referrals' => 8,
            'total_commissions' => 425.75,
            'level_1' => 10,
            'level_2' => 3,
            'level_3' => 2
        ],
        'referrals' => [
            [
                'id' => 1,
                'name' => 'Alice Smith',
                'email' => 'alice@example.com',
                'registration_date' => '2024-01-15',
                'investment_amount' => 500,
                'level' => 1,
                'referral_date' => '2024-01-15',
                'total_commissions' => 50
            ],
            [
                'id' => 2,
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'registration_date' => '2024-01-20',
                'investment_amount' => 1000,
                'level' => 1,
                'referral_date' => '2024-01-20',
                'total_commissions' => 100
            ]
        ],
        'referral_link' => BASE_URL . '/register?ref=COMCV123',
        'csrf_token' => $this->generateCsrfToken()
    ];

    // ✅ Utiliser $this->view()
    $this->view('pages/referral', $data);
}
    
    public function requestWithdrawal()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/referral');
        }
        
        $_SESSION['success'] = 'Votre demande de retrait de ' . $_POST['amount'] . '€ a été enregistrée.';
        $this->redirect('/referral');
    }
    
    public function exportCommissions()
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="commissions-' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Email', 'Date', 'Montant']);
        fputcsv($output, [1, 'Alice Smith', 'alice@example.com', '2024-01-15', '50€']);
        fputcsv($output, [2, 'Bob Johnson', 'bob@example.com', '2024-01-20', '100€']);
        fclose($output);
        exit;
    }
}