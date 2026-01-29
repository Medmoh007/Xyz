<?php

namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        view('pages/home');
    }

    public function about(): void
    {
        view('pages/about');
    }
}
