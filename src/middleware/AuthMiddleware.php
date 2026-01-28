<?php
namespace App\Middleware;

use function flash;
use function config;

class AuthMiddleware {

    public function handle(): void {
        if (empty($_SESSION['user'])) {
            flash('error', 'Connexion requise');
            header('Location: ' . config('base_url') . '/login');
            exit;
        }
    }
}
