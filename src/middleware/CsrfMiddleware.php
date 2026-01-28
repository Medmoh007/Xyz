<?php
namespace App\Middleware;

class CsrfMiddleware {

    public function handle(): void {

        // Génération
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        }

        // Vérification POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf'] ?? '';
            if (!hash_equals($_SESSION['csrf'], $token)) {
                http_response_code(403);
                exit('CSRF token invalide');
            }
        }
    }
}
