<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Middleware\AuthMiddleware;

return [
    '/' => [
        'action' => [HomeController::class, 'index']
    ],

    '/login' => [
        'action' => [AuthController::class, 'login']
    ],

    '/register' => [
        'action' => [AuthController::class, 'register']
    ],

    '/dashboard' => [
        'action' => [DashboardController::class, 'index'],
        'middleware' => [AuthMiddleware::class]
    ],
];
