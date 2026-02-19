<?php
namespace App\Models;

use App\Lib\Database;
use PDO;

class FaqModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupérer toutes les FAQ groupées par catégorie
     */
    public function getAllByCategory(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM faqs
            ORDER BY category, sort_order, created_at DESC
        ");
        $faqs = $stmt->fetchAll();

        $grouped = [];
        foreach ($faqs as $faq) {
            $grouped[$faq['category']][] = $faq;
        }
        return $grouped;
    }

    /**
     * Récupérer les FAQ les plus populaires
     */
    public function getPopularFaqs(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM faqs
            ORDER BY views DESC, created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Incrémenter le compteur de vues d'une FAQ
     */
    public function incrementView(int $faqId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE faqs SET views = views + 1 WHERE id = ?
        ");
        return $stmt->execute([$faqId]);
    }

    /**
     * Rechercher dans les FAQ
     */
    public function search(string $query): array
    {
        $search = "%{$query}%";
        $stmt = $this->db->prepare("
            SELECT * FROM faqs
            WHERE question LIKE ? OR answer LIKE ? OR category LIKE ?
            ORDER BY views DESC
            LIMIT 20
        ");
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll();
    }

    /**
     * Ajouter une FAQ (admin)
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO faqs (category, question, answer, sort_order, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['category'],
            $data['question'],
            $data['answer'],
            $data['sort_order'] ?? 0
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une FAQ (admin)
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['category', 'question', 'answer', 'sort_order'])) {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE faqs SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprimer une FAQ (admin)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM faqs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}