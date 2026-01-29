<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Middleware\AuthMiddleware;

//$router->get('/dashboard', 'DashboardController@index', ['auth']);

return [

    // Pages publiques
    '/' => [
        'action' => [HomeController::class, 'index'],
    ],

    '/login' => [
        'action' => [AuthController::class, 'login'],
    ],

    '/register' => [
        'action' => [AuthController::class, 'register'],
    ],

    '/about' => [
        'action' => [HomeController::class, 'about'],
    ],

    // Pages protégées (auth requise)
    '/dashboard' => [
        'action' => [DashboardController::class, 'index'],
        'middleware' => [AuthMiddleware::class],
    ],

    '/invest' => [
        'action' => [DashboardController::class, 'invest'],
        'middleware' => [AuthMiddleware::class],
    ],

    '/withdrawals' => [
        'action' => [DashboardController::class, 'withdrawals'],
        'middleware' => [AuthMiddleware::class],
    ],

    '/referrals' => [
        'action' => [DashboardController::class, 'referrals'],
        'middleware' => [AuthMiddleware::class],
    ],

    // Actions
    '/logout' => [
        'action' => [AuthController::class, 'logout'],
    ],
];
