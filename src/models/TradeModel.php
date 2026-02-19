<?php
namespace App\Models;

use App\Lib\Database;
use PDO;
use PDOException;

class TradeModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Exécute un trade complet avec transaction (insertion + mise à jour wallet)
     * @return int ID du trade créé, 0 en cas d'échec
     */
    public function executeTrade(int $userId, string $symbol, string $side, float $quantity, float $price): int
    {
        $total = $quantity * $price;

        try {
            $this->db->beginTransaction();

            if ($side === 'buy') {
                $walletModel = new WalletModel();
                $available = $walletModel->getAvailableBalance($userId);
                if ($available < $total) {
                    throw new \Exception("Solde insuffisant");
                }
                $stmt = $this->db->prepare("
                    UPDATE wallets 
                    SET balance = balance - ?, 
                        updated_at = NOW() 
                    WHERE user_id = ? AND balance >= ?
                ");
                if (!$stmt->execute([$total, $userId, $total])) {
                    throw new \Exception("Échec du débit");
                }
            } else {
                $stmt = $this->db->prepare("
                    UPDATE wallets 
                    SET balance = balance + ?, 
                        updated_at = NOW() 
                    WHERE user_id = ?
                ");
                if (!$stmt->execute([$total, $userId])) {
                    throw new \Exception("Échec du crédit");
                }
            }

            $stmt = $this->db->prepare("
                INSERT INTO trades (user_id, symbol, side, quantity, price, total, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$userId, $symbol, $side, $quantity, $price, $total]);
            $tradeId = (int) $this->db->lastInsertId();

            $this->db->commit();
            return $tradeId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("TradeModel::executeTrade error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère les derniers trades d'un utilisateur
     */
    public function getRecent(int $userId, int $limit = 50): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM trades
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        // Forcer le typage entier pour éviter l'erreur SQL avec LIMIT '50'
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Statistiques de trading d'un utilisateur
     */
    public function getStats(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                COUNT(*) as total_trades,
                SUM(CASE WHEN side = 'buy' THEN 1 ELSE 0 END) as buy_count,
                SUM(CASE WHEN side = 'sell' THEN 1 ELSE 0 END) as sell_count,
                SUM(CASE WHEN side = 'buy' THEN total ELSE 0 END) as total_buy,
                SUM(CASE WHEN side = 'sell' THEN total ELSE 0 END) as total_sell
            FROM trades
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: [
            'total_trades' => 0,
            'buy_count'    => 0,
            'sell_count'   => 0,
            'total_buy'    => 0,
            'total_sell'   => 0
        ];
    }

    /**
     * Prix simulés pour les paires supportées
     */
    public function getSimulatedPrices(): array
    {
        return [
            'BTCUSDT' => 45000 + (rand(-500, 500)),
            'ETHUSDT' => 3000 + (rand(-50, 50)),
            'BNBUSDT' => 400 + (rand(-10, 10)),
            'SOLUSDT' => 100 + (rand(-5, 5))
        ];
    }
}