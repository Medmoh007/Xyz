<?php
namespace App\Lib;

use PDO;

class Database {
    private static ?PDO $pdo = null;

    public static function getInstance(): PDO {
        if (!self::$pdo) {
            $cfg = require __DIR__ . '/../config/database.php';
            self::$pdo = new PDO(
                "mysql:host={$cfg['host']};dbname={$cfg['name']};charset={$cfg['charset']}",
                $cfg['user'],
                $cfg['pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$pdo;
    }
}
