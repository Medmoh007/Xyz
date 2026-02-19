<?php
// src/controllers/Api/TradingController.php

namespace App\Controllers\Api;

use App\Lib\BaseController;
use App\Services\BinanceService;
use App\Models\TradeModel;
use App\Models\UserModel;

class TradingController extends BaseController
{
    private BinanceService $binanceService;
    private TradeModel $tradeModel;
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->binanceService = new BinanceService();
        $this->tradeModel = new TradeModel();
        $this->userModel = new UserModel();
        
        // Vérifier l'authentification pour les API de trading
        $this->requireAuth();
    }

    // API: Données du marché
    public function market(string $symbol): void
    {
        try {
            $data = $this->binanceService->getMarketData($symbol);
            
            if (empty($data)) {
                $this->jsonResponse(['success' => false, 'message' => 'Symbol not found'], 404);
                return;
            }
            
            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'symbol' => $data['symbol'],
                    'price' => $data['lastPrice'],
                    'change_24h' => $data['priceChangePercent'],
                    'high_24h' => $data['highPrice'],
                    'low_24h' => $data['lowPrice'],
                    'volume_24h' => $data['volume'],
                    'quote_volume' => $data['quoteVolume']
                ]
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // API: Livre d'ordres
    public function orderbook(string $symbol): void
    {
        try {
            $orderBook = $this->binanceService->getOrderBook($symbol, 50);
            
            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'symbol' => $symbol,
                    'bids' => array_slice($orderBook['bids'], 0, 20),
                    'asks' => array_slice($orderBook['asks'], 0, 20),
                    'last_update_id' => $orderBook['lastUpdateId'] ?? null
                ]
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // API: Exécuter un trade
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validation
        $required = ['symbol', 'side', 'amount', 'price'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->jsonResponse(['success' => false, 'message' => "Missing field: $field"], 400);
                return;
            }
        }

        $userId = $_SESSION['user']['id'];
        $symbol = strtoupper($data['symbol']);
        $side = strtolower($data['side']); // 'buy' or 'sell'
        $amount = (float) $data['amount'];
        $price = (float) $data['price'];
        $total = $amount * $price;
        $fee = $total * 0.001; // 0.1% trading fee

        // Vérifier le solde
        $user = $this->userModel->find($userId);
        
        if ($side === 'buy') {
            $requiredBalance = $total + $fee;
            
            if ($user['balance'] < $requiredBalance) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Insufficient balance',
                    'required' => $requiredBalance,
                    'available' => $user['balance']
                ], 400);
                return;
            }
            
            // Débiter le solde
            $this->userModel->debitBalance($userId, $requiredBalance);
            
        } else if ($side === 'sell') {
            // Vérifier si l'utilisateur possède assez de l'actif
            // Cette logique dépendrait de ton système de wallet multi-actifs
            // Pour l'exemple, nous supposons que l'utilisateur vend depuis son portfolio d'investissement
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid side'], 400);
            return;
        }

        try {
            // Enregistrer le trade
            $tradeId = $this->tradeModel->create([
                'user_id' => $userId,
                'symbol' => $symbol,
                'side' => $side,
                'amount' => $amount,
                'price' => $price,
                'total' => $total,
                'fee' => $fee,
                'status' => 'completed'
            ]);

            // Mettre à jour le portfolio de trading
            $this->updateTradingPortfolio($userId, $symbol, $side, $amount, $price);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Trade executed successfully',
                'data' => [
                    'trade_id' => $tradeId,
                    'symbol' => $symbol,
                    'side' => $side,
                    'amount' => $amount,
                    'price' => $price,
                    'total' => $total,
                    'fee' => $fee
                ]
            ]);

        } catch (\Exception $e) {
            // Rembourser en cas d'erreur
            if ($side === 'buy') {
                $this->userModel->creditBalance($userId, $requiredBalance);
            }
            
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function updateTradingPortfolio(int $userId, string $symbol, string $side, float $amount, float $price): void
    {
        // Cette fonction met à jour le portfolio de trading de l'utilisateur
        // Elle dépendrait de ta structure de base de données pour les actifs
        
        // Exemple simplifié:
        // - Si achat: ajouter l'actif au portfolio
        // - Si vente: retirer l'actif du portfolio
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Authentication required'], 401);
            exit;
        }
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}