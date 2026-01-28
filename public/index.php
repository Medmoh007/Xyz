<?php
declare(strict_types=1);

use Dotenv\Dotenv;

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/utils/helpers.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

define('BASE_URL', $_ENV['BASE_URL'] ?? '/x/public');

/**
 * URI CLEAN
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// forcer suppression /x/public
$uri = preg_replace('#^/x/public#', '', $uri);

// retirer index.php
$uri = str_replace('/index.php', '', $uri);

$uri = rtrim($uri, '/') ?: '/';


/**
 * ROUTES
 */
$routes = require __DIR__ . '/../src/routes.php';

if (!isset($routes[$uri])) {
    http_response_code(404);
    echo "404 - Route PHP non trouvée ($uri)";
    exit;
}

$route = $routes[$uri];

/**
 * Middleware
 */
foreach ($route['middleware'] ?? [] as $middleware) {
    (new $middleware())->handle();
}

/**
 * Controller
 */
[$controller, $method] = $route['action'];
(new $controller())->$method();
