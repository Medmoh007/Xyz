<?php
namespace App\Models;

use App\Lib\Database;
use PDO;
use PDOException;

class DepositModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupère tous les dépôts d'un utilisateur avec l'adresse du wallet
     */
    public function getUserDeposits(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT d.*, w.address, w.network
            FROM deposits d
            JOIN wallets w ON d.user_id = w.user_id   -- jointure sur user_id (1 user = 1 wallet)
            WHERE d.user_id = ?
            ORDER BY d.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un dépôt par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM deposits WHERE id = ?");
        $stmt->execute([$id]);
        $deposit = $stmt->fetch();
        return $deposit ?: null;
    }

    /**
     * Crée une nouvelle demande de dépôt
     * @return int ID du dépôt créé, 0 en cas d'échec
     */
    public function create(int $userId, float $amount, string $screenshotPath): int
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO deposits (user_id, amount, screenshot, status, created_at)
                VALUES (?, ?, ?, 'pending', NOW())
            ");
            $stmt->execute([$userId, $amount, $screenshotPath]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur DepositModel::create : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Met à jour le statut d'un dépôt
     */
    public function updateStatus(int $depositId, string $status): bool
    {
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            return false;
        }
        try {
            $stmt = $this->db->prepare("UPDATE deposits SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $depositId]);
        } catch (PDOException $e) {
            error_log("Erreur DepositModel::updateStatus : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Approuve un dépôt et crédite le wallet de l'utilisateur
     */
    public function approve(int $depositId): bool
    {
        $this->db->beginTransaction();
        try {
            // Récupérer le dépôt
            $deposit = $this->find($depositId);
            if (!$deposit || $deposit['status'] !== 'pending') {
                throw new \Exception("Dépôt non trouvé ou déjà traité");
            }

            // Créditer le wallet (le wallet est lié à l'utilisateur)
            $stmt = $this->db->prepare("
                UPDATE wallets SET balance = balance + ? WHERE user_id = ?
            ");
            if (!$stmt->execute([$deposit['amount'], $deposit['user_id']])) {
                throw new \Exception("Échec du crédit");
            }

            // Mettre à jour le statut
            $stmt = $this->db->prepare("UPDATE deposits SET status = 'approved' WHERE id = ?");
            if (!$stmt->execute([$depositId])) {
                throw new \Exception("Échec de la mise à jour du statut");
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur DepositModel::approve : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rejette un dépôt (sans crédit)
     */
    public function reject(int $depositId): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE deposits SET status = 'rejected' WHERE id = ?");
            return $stmt->execute([$depositId]);
        } catch (PDOException $e) {
            error_log("Erreur DepositModel::reject : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les dépôts en attente (pour l'admin)
     */
    public function getPendingDeposits(): array
    {
        $stmt = $this->db->prepare("
            SELECT d.*, u.name as user_name, u.email, w.address
            FROM deposits d
            JOIN users u ON d.user_id = u.id
            JOIN wallets w ON d.user_id = w.user_id
            WHERE d.status = 'pending'
            ORDER BY d.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}