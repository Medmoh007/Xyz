<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController
{
    /**
     * LOGIN
     */
    public function login()
    {
        // GET → afficher le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            view('pages/login');
            return;
        }

        // POST → traiter la connexion
        $errors = [];

        // CSRF
        if (
            empty($_POST['csrf']) ||
            $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')
        ) {
            $errors[] = "Session expirée";
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if ($password === '') {
            $errors[] = "Mot de passe requis";
        }

        if (!empty($errors)) {
            view('pages/login', [
                'error' => implode('<br>', $errors)
            ]);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            view('pages/login', [
                'error' => 'Identifiants incorrects'
            ]);
            return;
        }

        // Connexion OK
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;

        redirect('/dashboard');
    }

    /**
     * REGISTER (celui que tu avais)
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            view('pages/register');
            return;
        }

        $errors = [];

        if (
            empty($_POST['csrf']) ||
            $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')
        ) {
            $errors[] = "Session expirée";
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        if ($name === '' || strlen($name) < 3) {
            $errors[] = "Nom invalide";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if (strlen($password) < 8) {
            $errors[] = "Mot de passe trop court (8 caractères min)";
        }

        if ($password !== $confirm) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }

        $userModel = new UserModel();

        if ($userModel->findByEmail($email)) {
            $errors[] = "Email déjà utilisé";
        }

        if (!empty($errors)) {
            view('pages/register', [
                'error' => implode('<br>', $errors)
            ]);
            return;
        }

        $userModel->create([
            'name'     => htmlspecialchars($name),
            'email'    => strtolower($email),
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        $_SESSION['user_id'] = $userModel->findByEmail($email)['id'];

        redirect('/dashboard');
    }

    /**
     * LOGOUT
     */
    public function logout()
    {
        session_destroy();
        redirect('/login');
    }
}
