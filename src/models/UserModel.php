<?php
namespace App\Models;

use App\Lib\Database;
use PDO;
use PDOException;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        // Force le lancement d'exceptions en cas d'erreur SQL
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Trouver un utilisateur par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Trouver un utilisateur par son email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Créer un nouvel utilisateur
     * @return int ID du nouvel utilisateur, ou 0 en cas d'échec
     */
    public function create(array $data): int
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, created_at)
                VALUES (?, ?, ?, 'user', NOW())
            ");
            $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT)
            ]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Log l'erreur (fichier ou système)
            error_log("Erreur UserModel::create : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Supprimer un utilisateur (utilisé pour rollback si création wallet échoue)
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur UserModel::delete : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Mettre à jour les informations d'un utilisateur (sauf mot de passe)
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'email', 'role'])) {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur UserModel::update : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
        } catch (PDOException $e) {
            error_log("Erreur UserModel::updatePassword : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $role = $stmt->fetchColumn();
        return $role === 'admin';
    }
}