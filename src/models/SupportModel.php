<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class SupportModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une demande de support
     */
    public function createSupportRequest(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO support_requests
                (user_id, name, email, subject, message, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'open', NOW())
        ");
        $stmt->execute([
            $data['user_id'] ?? null,
            $data['name'],
            $data['email'],
            $data['subject'],
            $data['message']
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Vérifier si une adresse email a déjà soumis une demande récemment
     */
    public function checkRecentRequest(string $email, int $hours = 24): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM support_requests
            WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? HOUR)
            LIMIT 1
        ");
        $stmt->execute([$email, $hours]);
        return (bool) $stmt->fetch();
    }

    /**
     * Récupérer les demandes de support d'un utilisateur
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM support_requests
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer toutes les demandes en attente (admin)
     */
    public function getPending(): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM support_requests
            WHERE status = 'open'
            ORDER BY created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Répondre à une demande (admin)
     */
    public function reply(int $requestId, string $response, int $adminId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE support_requests
            SET response = ?,
                status = 'answered',
                answered_by = ?,
                answered_at = NOW()
            WHERE id = ? AND status = 'open'
        ");
        return $stmt->execute([$response, $adminId, $requestId]);
    }

    /**
     * Clôturer une demande
     */
    public function close(int $requestId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE support_requests
            SET status = 'closed'
            WHERE id = ?
        ");
        return $stmt->execute([$requestId]);
    }

    /**
     * Envoyer une notification par email (simulation)
     */
    public function sendNotificationEmail(array $contactData): bool
    {
        // Simulation d'envoi d'email
        error_log("Email envoyé à {$contactData['email']} - Sujet: {$contactData['subject']}");
        return true;
    }
}