<?php

namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    private TransactionModel $transactionModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = new TransactionModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Liste paginée des transactions
    public function index(): void
    {
        $userId = $_SESSION['user']['id'];

        // Récupérer la page depuis l’URL (ex: /transactions?page=2)
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 20; // nombre de transactions par page
        $offset = ($page - 1) * $limit;

        // Charger les transactions
        $transactions = $this->transactionModel->getByUser($userId, $limit, $offset);

        // Préparer les données pour la vue
        $data = [
            'transactions' => $transactions,
            'page' => $page,
            'limit' => $limit,
            'title' => 'Historique des transactions'
        ];

        $this->view('pages/transactions', $data);
    }
}