<?php

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf']) ||
                $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')
            ) {
                http_response_code(419);
                echo "Token CSRF invalide";
                exit;
            }
        }
    }
}
