<?php

function db(): PDO
{
    static $pdo;

    if (!$pdo) {
        $pdo = new PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8mb4",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    return $pdo;
}

function view(string $path, array $data = [])
{
    extract($data);
    require __DIR__ . '/../Views/' . $path . '.php';
}

function redirect(string $path)
{
    header('Location: ' . config('base_url') . $path);
    exit;
}

function config(string $key)
{
    return $_ENV[strtoupper($key)] ?? null;
}
