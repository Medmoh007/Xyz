<?php

namespace App\Controllers;

use App\Lib\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Accueil | COMCV Trading',
            'message' => 'Bienvenue sur la plateforme'
        ];

        // ✅ Utiliser la méthode héritée
        $this->view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'À propos | COMCV Trading',
            'content' => 'Présentation de la société...'
        ];

        $this->view('pages/about', $data);
    }
}