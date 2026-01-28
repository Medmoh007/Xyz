<?php
namespace App\Lib;

use PDO;

abstract class BaseModel {
    protected PDO $db;
    protected string $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all() {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    public function where(string $field, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE $field = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public function create(array $data): void {
        $cols = implode(',', array_keys($data));
        $vals = ':' . implode(',:', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($vals)");
        $stmt->execute($data);
    }
}
