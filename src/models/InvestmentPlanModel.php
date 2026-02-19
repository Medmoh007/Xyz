<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class InvestmentPlanModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupérer tous les plans d'investissement
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM investment_plans
            ORDER BY amount ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un plan par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM investment_plans WHERE id = ?");
        $stmt->execute([$id]);
        $plan = $stmt->fetch();
        return $plan ?: null;
    }

    /**
     * Ajouter un nouveau plan (admin)
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO investment_plans (name, amount, daily_rate, duration_days, description, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['name'],
            $data['amount'],
            $data['daily_rate'],
            $data['duration_days'],
            $data['description'] ?? null
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un plan (admin)
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'amount', 'daily_rate', 'duration_days', 'description'])) {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE investment_plans SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprimer un plan (admin)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM investment_plans WHERE id = ?");
        return $stmt->execute([$id]);
    }
}