<?php
// public/index.php

// Activer l'affichage des erreurs (dev)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session OBLIGATOIREMENT avant tout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger l'autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Charger la configuration (définit BASE_URL)
require_once __DIR__ . '/../src/config/config.php';

// Instancier et exécuter le routeur
$router = require __DIR__ . '/../src/Router.php';
$router->dispatch();