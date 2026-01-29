<?php
// src/lib/BaseController.php

class BaseController {
    protected $db;
    protected $viewPath = 'src/views/';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function render($view, $data = []) {
        extract($data);
        require $this->viewPath . $view . '.php';
    }

    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    protected function checkCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('CSRF token validation failed');
            }
        }
    }
}
?>