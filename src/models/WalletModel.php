<?php
namespace App\Models;

use App\Lib\Database;
use PDO;
use PDOException;

class WalletModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupérer le wallet d'un utilisateur
     */
    public function getByUser(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE user_id = ?");
        $stmt->execute([$userId]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $wallet ?: null;
    }

    /**
     * Solde disponible = balance - locked_balance
     */
    public function getAvailableBalance(int $userId): float
    {
        $stmt = $this->db->prepare("
            SELECT balance - locked_balance AS available
            FROM wallets
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetchColumn();
        return $result !== false ? (float) $result : 0.0;
    }

    /**
     * Créer un wallet et retourner true en cas de succès
     */
    public function create(int $userId): bool
    {
        try {
            // Vérifier si l'utilisateur a déjà un wallet
            if ($this->getByUser($userId) !== null) {
                return false; // déjà existant
            }

            $address = $this->generateUniqueAddress();
            $stmt = $this->db->prepare("
                INSERT INTO wallets (user_id, address, network, balance, locked_balance, created_at)
                VALUES (?, ?, 'TRC20', 0.00, 0.00, NOW())
            ");
            return $stmt->execute([$userId, $address]);
        } catch (PDOException $e) {
            error_log("Erreur WalletModel::create : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer le wallet d'un utilisateur (rollback)
     */
    public function deleteByUserId(int $userId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM wallets WHERE user_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erreur WalletModel::deleteByUserId : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Geler des fonds lors d'une demande de retrait
     * balance -= amount, locked_balance += amount
     */
    public function lockFunds(int $userId, float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE wallets
                SET balance = balance - :amount,
                    locked_balance = locked_balance + :amount
                WHERE user_id = :userId AND balance >= :amount
            ");
            $stmt->execute([
                ':amount' => $amount,
                ':userId' => $userId
            ]);

            if ($stmt->rowCount() === 0) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur WalletModel::lockFunds : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Débloquer des fonds (annulation ou rejet de retrait)
     * balance += amount, locked_balance -= amount
     */
    public function unlockFunds(int $userId, float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE wallets
                SET balance = balance + :amount,
                    locked_balance = locked_balance - :amount
                WHERE user_id = :userId AND locked_balance >= :amount
            ");
            $stmt->execute([
                ':amount' => $amount,
                ':userId' => $userId
            ]);

            if ($stmt->rowCount() === 0) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur WalletModel::unlockFunds : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Recalculer le solde d'un utilisateur à partir des transactions (procédure stockée)
     */
    public function recalc(int $userId): bool
    {
        try {
            $stmt = $this->db->prepare("CALL recalc_wallet(?)");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erreur WalletModel::recalc : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Générer une adresse TRC20 unique (simulation)
     */
    private function generateUniqueAddress(): string
    {
        do {
            $address = 'T' . bin2hex(random_bytes(16)); // 33 caractères
            $address = substr($address, 0, 34);
        } while ($this->addressExists($address));
        return $address;
    }

    /**
     * Vérifier si une adresse existe déjà
     */
    private function addressExists(string $address): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM wallets WHERE address = ?");
        $stmt->execute([$address]);
        return (bool) $stmt->fetch();
    }
}