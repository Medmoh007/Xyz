<?php
// src/models/MarketDataModel.php

namespace App\Models;

use App\Lib\Database;
use PDO;

class MarketDataModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Enregistrer les données de marché
    public function saveMarketData(string $symbol, array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO market_data (symbol, price, change_24h, high_24h, low_24h, volume_24h, market_cap, last_updated)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE 
             price = VALUES(price),
             change_24h = VALUES(change_24h),
             high_24h = VALUES(high_24h),
             low_24h = VALUES(low_24h),
             volume_24h = VALUES(volume_24h),
             market_cap = VALUES(market_cap),
             last_updated = NOW()"
        );
        
        $stmt->execute([
            $symbol,
            $data['price'],
            $data['change_24h'],
            $data['high_24h'],
            $data['low_24h'],
            $data['volume_24h'],
            $data['market_cap'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    // Récupérer les données d'un symbole
    public function getSymbolData(string $symbol): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM market_data WHERE symbol = ? ORDER BY last_updated DESC LIMIT 1"
        );
        $stmt->execute([$symbol]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les marchés populaires
    public function getPopularMarkets(): array
    {
        return $this->db->query(
            "SELECT * FROM market_data 
             WHERE symbol IN ('BTCUSDT', 'ETHUSDT', 'BNBUSDT', 'XRPUSDT', 'ADAUSDT', 'SOLUSDT')
             ORDER BY market_cap DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Historique des prix pour un symbole
    public function getPriceHistory(string $symbol, int $days = 30): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE(last_updated) as date, 
                    AVG(price) as avg_price,
                    MAX(high_24h) as high,
                    MIN(low_24h) as low
             FROM market_data 
             WHERE symbol = ? 
             AND last_updated >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(last_updated)
             ORDER BY date"
        );
        $stmt->execute([$symbol, $days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}