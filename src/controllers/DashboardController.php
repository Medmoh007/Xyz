<?php
/*
namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        $dashboard_page = true;
        $title = 'Dashboard | COMCV';

        require view('pages/dashboard');
    }
}*/

namespace App\Controllers;

class DashboardController
{
    public function __construct()
    {
        // Démarre la session si elle n'existe pas
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            redirect('/login'); // Utilise le helper de redirection
            exit;
        }
    }

    /**
     * Page dashboard
     */
    public function index()
    {
        $dashboard_page = true;
        $title = 'Dashboard | COMCV';

        // Récupération des infos utilisateur pour affichage
        $user = $_SESSION['user'];

        // Affiche la vue dashboard
        view('pages/dashboard', [
            'title' => $title,
            'user'  => $user,
            'dashboard_page' => $dashboard_page
        ]);
    }
}

