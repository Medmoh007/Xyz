<?php
namespace App\Lib;

use App\Lib\Database;

class BaseController
{
    protected $db;
    protected string $viewPath;

    public function __construct()
    {
        // Connexion DB (si nécessaire)
        $this->db = Database::getInstance();
        $this->viewPath = __DIR__ . '/../views/';
    }

    /**
     * Affiche une vue
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $file = $this->viewPath . $view . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            die("Vue non trouvée: $file");
        }
    }

    /**
     * Retourne une réponse JSON
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirige vers une URL
     */
    protected function redirect(string $url): void
    {
        $baseUrl = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $baseUrl . $url);
        exit;
    }

    /**
     * Vérifie le token CSRF
     */
    protected function verifyCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }

    /**
     * Vérifie l'authentification
     */
    protected function checkAuth(): void
    {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    /**
     * Génère un token CSRF
     */
    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}