<?php

namespace App\Controllers;

class AdminController
{
    public function index(): void
    {
        view('admin/index');
    }

    public function users(): void
    {
        view('admin/users');
    }

    public function settings(): void
    {
        view('admin/settings');
    }
}
