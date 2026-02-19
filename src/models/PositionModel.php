<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class PositionModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ouvre une nouvelle position
     */
    public function open($userId, $pair, $entryPrice, $amount)
    {
        try {
            $sql = "INSERT INTO positions 
                    (user_id, pair, entry_price, amount, status, created_at) 
                    VALUES (:user_id, :pair, :entry_price, :amount, 'open', NOW())";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':pair' => $pair,
                ':entry_price' => $entryPrice,
                ':amount' => $amount
            ]);
            
        } catch (\PDOException $e) {
            error_log("Position Open Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère la position ouverte pour un utilisateur et une paire
     */
    public function getOpen($userId, $pair)
    {
        try {
            $sql = "SELECT * FROM positions 
                    WHERE user_id = :user_id 
                    AND pair = :pair 
                    AND status = 'open' 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':pair' => $pair
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            error_log("Position GetOpen Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère toutes les positions ouvertes d'un utilisateur
     */
    public function getAllOpen($userId)
    {
        try {
            $sql = "SELECT * FROM positions 
                    WHERE user_id = :user_id 
                    AND status = 'open' 
                    ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            error_log("Position GetAllOpen Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ferme une position
     */
    public function close($positionId)
    {
        try {
            $sql = "UPDATE positions 
                    SET status = 'closed', closed_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $positionId]);
            
        } catch (\PDOException $e) {
            error_log("Position Close Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcule le PnL flottant
     */
    public function calculateFloatingPnl($position, $currentPrice)
    {
        return ($currentPrice - $position['entry_price']) * $position['amount'];
    }

    /**
     * Récupère les statistiques des positions
     */
    public function getStats($userId)
    {
        try {
            $sql = "SELECT 
                    COUNT(*) as total_positions,
                    SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open_positions,
                    SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_positions,
                    SUM(amount * entry_price) as total_invested
                FROM positions 
                WHERE user_id = :user_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            error_log("Position Stats Error: " . $e->getMessage());
            return [];
        }
    }
}