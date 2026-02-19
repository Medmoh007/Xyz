<?php
// src/Router.php
namespace App;

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\WalletController;
use App\Controllers\DepositController;
use App\Controllers\WithdrawalController;
use App\Controllers\InvestmentController;
use App\Controllers\TradeController;
use App\Controllers\TransactionController;
use App\Controllers\SupportController;
use App\Controllers\AdminController;

class Router
{
    private array $routes = [];
    private string $basePath;

    public function __construct()
    {
        // Définir le chemin de base à partir de BASE_URL ou du script
        $baseUrl = defined('BASE_URL') ? BASE_URL : '';
        $parsed = parse_url($baseUrl);
        $this->basePath = $parsed['path'] ?? '';
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        // Normalisation : on stocke le chemin sans le basePath
        $normalized = '/' . ltrim($path, '/');
        $this->routes[$method][$normalized] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Supprimer la partie query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Retirer le basePath de l'URI
        if ($this->basePath && str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
        }

        $uri = '/' . ltrim($uri, '/');

        // Route par défaut
        if ($uri === '' || $uri === '/') {
            $uri = '/';
        }

        // Recherche route exacte
        if (isset($this->routes[$method][$uri])) {
            $this->executeHandler($this->routes[$method][$uri]);
            return;
        }

        // Recherche avec paramètres
        foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
            $pattern = $this->convertToRegex($routePath);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->executeHandler($handler, $matches);
                return;
            }
        }

        // 404
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
    }

    private function convertToRegex(string $route): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private function executeHandler(callable|array $handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], $params);
                    return;
                }
            }
        }

        throw new \RuntimeException('Handler invalide pour la route');
    }
}

// ========== INSTANCIATION ET DÉFINITION DES ROUTES ==========
$router = new Router();

// --- Pages publiques ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// --- Espace membre (authentification requise) ---
$router->get('/dashboard', [DashboardController::class, 'index']);

// Wallet & retraits
$router->get('/wallet', [WalletController::class, 'index']);
$router->post('/wallet/withdraw', [WalletController::class, 'withdraw']);
$router->post('/wallet/cancel-withdrawal/{id}', [WalletController::class, 'cancelWithdrawal']);

// Dépôts
$router->get('/deposit', [DepositController::class, 'index']);
$router->post('/deposit', [DepositController::class, 'store']);

// Retraits
$router->get('/withdrawal', [WithdrawalController::class, 'index']);
$router->post('/withdrawal/calculate-fees', [WithdrawalController::class, 'calculateFees']);

// Investissements
$router->get('/investments', [InvestmentController::class, 'index']); // homogénéisation
$router->post('/invest/buy', [InvestmentController::class, 'buy']);

// Trading
$router->get('/trade', [TradeController::class, 'index']);
$router->post('/trade/execute', [TradeController::class, 'execute']);
$router->get('/trade/price', [TradeController::class, 'getPrice']); // AJAX

// Transactions
$router->get('/transactions', [TransactionController::class, 'index']);

// Support
$router->get('/support', [SupportController::class, 'index']);
$router->post('/support/contact', [SupportController::class, 'submitContact']);

// --- Administration ---
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/deposits/pending', [AdminController::class, 'depositsPending']);
$router->post('/admin/deposits/approve/{id}', [AdminController::class, 'approveDeposit']);
$router->post('/admin/deposits/reject/{id}', [AdminController::class, 'rejectDeposit']);

return $router;