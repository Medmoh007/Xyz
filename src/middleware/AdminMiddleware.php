<?php
namespace App\Middleware;

use function flash;
use function config;

class AdminMiddleware {

    public function handle(): void {
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            flash('error', 'Accès refusé');
            header('Location: ' . config('base_url') . '/dashboard');
            exit;
        }
    }
}
