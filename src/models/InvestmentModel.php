<?php
namespace App\Models;

use App\Lib\Database;
use PDO;
use PDOException;

class InvestmentModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupère tous les plans d'investissement depuis la config
     */
    public function getAllPlans(): array
    {
        // Utilise la constante PLANS définie dans config.php
        if (defined('PLANS')) {
            return PLANS;
        }

        // Fallback si la constante n'existe pas
        return [
            'starter'       => ['min' => 50, 'max' => 499, 'daily' => 1.5, 'duration' => 30],
            'professional'  => ['min' => 500, 'max' => 4999, 'daily' => 2.2, 'duration' => 30],
            'premium'       => ['min' => 5000, 'max' => 50000, 'daily' => 3.5, 'duration' => 30],
        ];
    }

    /**
     * Récupère les investissements d'un utilisateur
     */
    public function getUserInvestments(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, p.name as plan_name, p.daily_rate, p.duration_days
            FROM investments i
            JOIN investment_plans p ON i.plan_id = p.id
            WHERE i.user_id = ?
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un investissement par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, p.name as plan_name, p.daily_rate, p.duration_days
            FROM investments i
            JOIN investment_plans p ON i.plan_id = p.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Crée un nouvel investissement
     */
    public function create(int $userId, int $planId, float $amount): ?int
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO investments (user_id, plan_id, amount, status, created_at, updated_at)
                VALUES (?, ?, ?, 'active', NOW(), NOW())
            ");
            $stmt->execute([$userId, $planId, $amount]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur InvestmentModel::create : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour les profits quotidiens (cron)
     */
    public function updateDailyProfits(): int
    {
        // Implémentation à faire selon votre logique
        return 0;
    }
}