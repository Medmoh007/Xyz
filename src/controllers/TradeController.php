<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\TradeModel;
use App\Models\WalletModel;
use App\Models\UserModel;

class TradeController extends BaseController
{
    private TradeModel $tradeModel;
    private WalletModel $walletModel;
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->tradeModel = new TradeModel();
        $this->walletModel = new WalletModel();
        $this->userModel = new UserModel();
        $this->checkAuth(); // redirige vers /login si non connecté
    }

    /**
     * Page principale du trading
     */
    public function index(): void
    {
        $userId = $_SESSION['user_id'];

        // Données
        $trades = $this->tradeModel->getRecent($userId, 50);
        $tradeStats = $this->tradeModel->getStats($userId);
        $user = $this->userModel->find($userId);
        $user['balance'] = $this->walletModel->getAvailableBalance($userId);
        
        // Paires supportées
        $pairs = [
            'BTCUSDT' => 'BTC/USDT',
            'ETHUSDT' => 'ETH/USDT',
            'BNBUSDT' => 'BNB/USDT',
            'SOLUSDT' => 'SOL/USDT'
        ];

        // Prix simulés (à remplacer par une vraie API plus tard)
        $marketPrices = $this->tradeModel->getSimulatedPrices();

        // Positions ouvertes : à implémenter selon votre logique
        $openPositions = [];
        $positionStats = ['open_positions' => 0];

        // Messages flash (déjà gérés par session)
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        $this->view('pages/trade', [
            'trades'        => $trades,
            'tradeStats'    => $tradeStats,
            'positionStats' => $positionStats,
            'user'          => $user,
            'pairs'         => $pairs,
            'marketPrices'  => $marketPrices,
            'openPositions' => $openPositions,
            'success'       => $success,
            'error'         => $error,
            'title'         => 'Trading | COMCV'
        ]);
    }

    /**
     * Exécution d'un ordre MARKET
     */
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/trade');
        }

        // Validation CSRF
        if (!$this->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Requête invalide (CSRF).";
            $this->redirect('/trade');
        }

        $userId = $_SESSION['user_id'];
        $pair   = trim($_POST['pair'] ?? '');
        $side   = trim($_POST['side'] ?? ''); // 'buy' ou 'sell'
        $amount = (float) ($_POST['amount'] ?? 0);   // quantité de crypto
        $price  = (float) ($_POST['price'] ?? 0);    // prix unitaire

        // Validation
        $validPairs = ['BTCUSDT','ETHUSDT','BNBUSDT','SOLUSDT'];
        if (!in_array($pair, $validPairs) || !in_array($side, ['buy','sell']) || $amount <= 0 || $price <= 0) {
            $_SESSION['error'] = "Paramètres de trade invalides.";
            $this->redirect('/trade');
        }

        // Exécution via le modèle (transaction incluse)
        $tradeId = $this->tradeModel->executeTrade($userId, $pair, $side, $amount, $price);

        if ($tradeId > 0) {
            $_SESSION['success'] = "✅ Trade {$side} exécuté : {$amount} {$pair} @ $" . number_format($price, 2);
        } else {
            $_SESSION['error'] = "Échec de l'exécution du trade. Vérifiez votre solde.";
        }

        $this->redirect('/trade');
    }

    /**
     * API : retourne le prix actuel d'une paire (AJAX)
     */
    public function getPrice(): void
    {
        $pair = $_GET['pair'] ?? 'BTCUSDT';
        $prices = $this->tradeModel->getSimulatedPrices();
        $price = $prices[$pair] ?? 0;

        $this->json([
            'success' => true,
            'price'   => $price,
            'pair'    => $pair
        ]);
    }
}