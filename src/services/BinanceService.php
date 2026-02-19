<?php
// src/services/BinanceService.php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinanceService
{
    private Client $client;
    private string $apiKey;
    private string $secretKey;
    
    public function __construct(string $apiKey = '', string $secretKey = '')
    {
        $this->client = new Client([
            'base_uri' => 'https://api.binance.com',
            'timeout' => 10.0,
        ]);
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    // Récupérer les prix du marché (pas besoin d'API key)
    public function getMarketData(string $symbol = null): array
    {
        try {
            if ($symbol) {
                $response = $this->client->get('/api/v3/ticker/24hr', [
                    'query' => ['symbol' => $symbol]
                ]);
                return json_decode($response->getBody(), true);
            }
            
            // Récupérer tous les marchés USDT
            $response = $this->client->get('/api/v3/ticker/24hr');
            $allData = json_decode($response->getBody(), true);
            
            // Filtrer seulement les paires USDT
            return array_filter($allData, function($item) {
                return str_ends_with($item['symbol'], 'USDT');
            });
            
        } catch (GuzzleException $e) {
            error_log('Binance API Error: ' . $e->getMessage());
            return [];
        }
    }

    // Récupérer le livre d'ordres
    public function getOrderBook(string $symbol, int $limit = 100): array
    {
        try {
            $response = $this->client->get('/api/v3/depth', [
                'query' => [
                    'symbol' => $symbol,
                    'limit' => $limit
                ]
            ]);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            return ['bids' => [], 'asks' => []];
        }
    }

    // Récupérer l'historique des trades
    public function getRecentTrades(string $symbol, int $limit = 100): array
    {
        try {
            $response = $this->client->get('/api/v3/trades', [
                'query' => [
                    'symbol' => $symbol,
                    'limit' => $limit
                ]
            ]);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            return [];
        }
    }

    // Récupérer les bougies (candlesticks)
    public function getKlines(string $symbol, string $interval = '1h', int $limit = 100): array
    {
        try {
            $response = $this->client->get('/api/v3/klines', [
                'query' => [
                    'symbol' => $symbol,
                    'interval' => $interval,
                    'limit' => $limit
                ]
            ]);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            return [];
        }
    }
}