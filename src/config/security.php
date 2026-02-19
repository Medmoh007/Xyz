<?php
namespace App\Config;

class Security
{
    /**
     * Initialise les paramètres de session
     */
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $sessionName = $_ENV['SESSION_NAME'] ?? 'hyip_session';
        $lifetime = (int) ($_ENV['SESSION_LIFETIME'] ?? 7200);

        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', $_ENV['APP_ENV'] === 'production' ? 1 : 0);
        ini_set('session.cookie_samesite', 'Strict');

        session_name($sessionName);
        session_start();

        // Régénération périodique de l'ID
        if (!isset($_SESSION['_CREATED'])) {
            $_SESSION['_CREATED'] = time();
        } elseif (time() - $_SESSION['_CREATED'] > $lifetime) {
            session_regenerate_id(true);
            $_SESSION['_CREATED'] = time();
        }
    }

    /**
     * Génère et stocke un token CSRF
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie le token CSRF
     */
    public static function verifyCsrfToken(?string $token): bool
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Nettoie les entrées utilisateur (XSS)
     */
    public static function escape(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Vérifie si l'utilisateur est authentifié
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']['id']);
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Redirige si non authentifié
     */
    public static function requireLogin(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Redirige si non admin
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
}