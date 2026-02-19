<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class WithdrawalModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une demande de retrait
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO withdrawals
                (user_id, amount, wallet_address, network, status, created_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $data['user_id'],
            $data['amount'],
            $data['wallet_address'],
            $data['network']
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupérer les retraits d'un utilisateur
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM withdrawals
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un retrait par son ID (pour un utilisateur spécifique)
     */
    public function getById(int $withdrawalId, int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM withdrawals
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$withdrawalId, $userId]);
        $withdrawal = $stmt->fetch();
        return $withdrawal ?: null;
    }

    /**
     * Vérifier si l'utilisateur a une demande en attente
     */
    public function hasPendingWithdrawal(int $userId): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM withdrawals
            WHERE user_id = ? AND status = 'pending'
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Mettre à jour le statut d'une demande (pour annulation ou approbation)
     */
    public function updateStatus(int $withdrawalId, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE withdrawals
            SET status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$status, $withdrawalId]);
    }

    /**
     * Approuver un retrait (admin) – déclenche le trigger after_withdrawal_approved
     */
    public function approve(int $withdrawalId, int $adminId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE withdrawals
            SET status = 'approved',
                approved_by = ?,
                approved_at = NOW()
            WHERE id = ? AND status = 'pending'
        ");
        return $stmt->execute([$adminId, $withdrawalId]);
    }

    /**
     * Rejeter un retrait (admin) – remboursement via déblocage des fonds
     */
    public function reject(int $withdrawalId, int $adminId): bool
    {
        $this->db->beginTransaction();

        try {
            // Récupérer le montant pour débloquer
            $stmt = $this->db->prepare("SELECT user_id, amount FROM withdrawals WHERE id = ?");
            $stmt->execute([$withdrawalId]);
            $withdrawal = $stmt->fetch();

            if (!$withdrawal) {
                throw new \Exception("Retrait introuvable");
            }

            // Débloquer les fonds
            $walletModel = new WalletModel();
            $walletModel->unlockFunds($withdrawal['user_id'], $withdrawal['amount']);

            // Mettre à jour le statut
            $stmt = $this->db->prepare("
                UPDATE withdrawals
                SET status = 'rejected',
                    approved_by = ?,
                    approved_at = NOW()
                WHERE id = ? AND status = 'pending'
            ");
            $stmt->execute([$adminId, $withdrawalId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur rejet retrait : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculer les frais de retrait
     */
    public function calculateFees(float $amount, string $network): float
    {
        // Frais fixes selon le réseau
        $fees = [
            'TRC20' => 1.0,
            'ERC20' => 10.0,
            'BEP20' => 0.5
        ];
        return $fees[$network] ?? 0;
    }

    /**
     * Total des retraits approuvés d'un utilisateur
     */
    public function getTotalWithdrawn(int $userId): float
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0)
            FROM withdrawals
            WHERE user_id = ? AND status = 'approved'
        ");
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }

    /**
     * Compter les retraits en attente d'un utilisateur
     */
    public function countPending(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM withdrawals
            WHERE user_id = ? AND status = 'pending'
        ");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupérer tous les retraits en attente (admin)
     */
    public function getPending(): array
    {
        $stmt = $this->db->prepare("
            SELECT w.*, u.name as user_name, u.email
            FROM withdrawals w
            JOIN users u ON w.user_id = u.id
            WHERE w.status = 'pending'
            ORDER BY w.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}