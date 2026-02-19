<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class TransactionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupérer les transactions d'un utilisateur (journal)
     */
    public function getByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM transactions
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer une transaction par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        $transaction = $stmt->fetch();
        return $transaction ?: null;
    }

    /**
     * Récupérer les transactions d'un type spécifique
     */
    public function getByType(int $userId, string $type, int $limit = 20): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM transactions
            WHERE user_id = ? AND type = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $type, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer le solde total d'un utilisateur en sommant les transactions
     * (utile pour vérification)
     */
    public function getSumByUser(int $userId): float
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }
}