<?php
// cron/update_market_data.php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Models\MarketDataModel;
use App\Services\BinanceService;

$marketModel = new MarketDataModel();
$binanceService = new BinanceService();

// Paires à suivre
$symbols = [
    'BTCUSDT', 'ETHUSDT', 'BNBUSDT', 
    'XRPUSDT', 'ADAUSDT', 'SOLUSDT',
    'DOTUSDT', 'DOGEUSDT', 'MATICUSDT'
];

foreach ($symbols as $symbol) {
    try {
        $data = $binanceService->getMarketData($symbol);
        
        if (!empty($data)) {
            $marketData = [
                'price' => (float) $data['lastPrice'],
                'change_24h' => (float) $data['priceChangePercent'],
                'high_24h' => (float) $data['highPrice'],
                'low_24h' => (float) $data['lowPrice'],
                'volume_24h' => (float) $data['volume'],
                'market_cap' => (float) $data['lastPrice'] * (float) $data['volume']
            ];
            
            $marketModel->saveMarketData($symbol, $marketData);
            echo "Updated $symbol: $" . $marketData['price'] . " (" . $marketData['change_24h'] . "%)\n";
        }
        
        // Pause pour éviter le rate limiting
        usleep(200000); // 200ms
        
    } catch (\Exception $e) {
        echo "Error updating $symbol: " . $e->getMessage() . "\n";
        continue;
    }
}

echo "Market data update completed at " . date('Y-m-d H:i:s') . "\n";