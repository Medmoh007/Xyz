<?php
namespace App\Lib;

class BaseController
{
    protected function view(string $path, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $path . '.php';

        if (!file_exists($viewFile)) {
            die("Vue introuvable : $viewFile");
        }

        require $viewFile;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
