<?php
/*
require_once __DIR__ . '/../config/security.php';

function view(string $view, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../views/pages/' . $view . '.php';
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}
*/

require_once __DIR__ . '/../config/security.php';

/**
 * Affiche une vue
 *
 * @param string $view Chemin de la vue depuis le dossier pages (ex: 'login' ou 'auth/login')
 * @param array $data Données à passer à la vue
 */
function view(string $view, array $data = []): void
{
    // Extraire les variables pour les utiliser directement dans la vue
    extract($data);

    // Nettoyer le chemin pour éviter un double 'pages/pages/'
    $view = preg_replace('#^pages/#', '', $view);

    // Chemin complet vers le fichier
    $file = __DIR__ . '/../views/pages/' . $view . '.php';

    if (!file_exists($file)) {
        http_response_code(404);
        die("Vue introuvable : {$file}");
    }

    require $file;
}

/**
 * Redirige vers une URL relative à BASE_URL
 *
 * @param string $path Chemin relatif (ex: '/login')
 */
function redirect(string $path): void
{
    // Nettoyer le chemin pour éviter les doubles slash
    $path = '/' . ltrim($path, '/');
    header('Location: ' . rtrim(BASE_URL, '/') . $path);
    exit;
}

/**
 * Affiche une erreur 404 et stoppe le script
 *
 * @param string $message Message optionnel
 */
function abort404(string $message = '404 - Page non trouvée'): void
{
    http_response_code(404);
    die($message);
}

function generateCryptoAddress(): string
{
    return 'T' . substr(bin2hex(random_bytes(20)), 0, 33);
}
