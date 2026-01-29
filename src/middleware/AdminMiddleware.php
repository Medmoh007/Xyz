<?php

namespace App\Middleware;

class AdminMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['is_admin'] ?? false) !== true) {
            http_response_code(403);
            echo "Accès interdit";
            exit;
        }
    }
}
