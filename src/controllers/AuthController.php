<?php
/*
namespace App\Controllers;

use App\Models\User;
use App\Utils\Security;

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = User::findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                return view('login', ['error' => "Email ou mot de passe incorrect"]);
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ];

            redirect('/dashboard');
        }

        view('login');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = trim($_POST['name']);
            $email   = trim($_POST['email']);
            $pass    = $_POST['password'];
            $confirm = $_POST['password_confirm'];

            if ($pass !== $confirm) {
                return view('register', ['error' => "Les mots de passe ne correspondent pas"]);
            }

            if (User::findByEmail($email)) {
                return view('register', ['error' => "Email déjà utilisé"]);
            }

            User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => password_hash($pass, PASSWORD_BCRYPT),
            ]);

            redirect('/login');
        }

        view('register');
    }

    public function logout()
    {
        session_destroy();
        redirect('/login');
    }
}*/
namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function __construct()
    {
        // Démarre la session si elle n'existe pas
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Page de connexion
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $errors = [];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide";
            }

            if (empty($password)) {
                $errors[] = "Le mot de passe est requis";
            }

            $user = User::findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $errors[] = "Email ou mot de passe incorrect";
            }

            if (!empty($errors)) {
                return view('login', ['error' => implode('<br>', $errors)]);
            }

            // Connexion réussie
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
            ];

            redirect('/dashboard');
        }

        // Afficher le formulaire si GET
        view('login');
    }

    /**
     * Page d'inscription
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name             = trim($_POST['name'] ?? '');
            $email            = trim($_POST['email'] ?? '');
            $password         = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            $errors = [];

            if (empty($name)) {
                $errors[] = "Le nom est requis";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide";
            }

            if (strlen($password) < 6) {
                $errors[] = "Le mot de passe doit faire au moins 6 caractères";
            }

            if ($password !== $password_confirm) {
                $errors[] = "Les mots de passe ne correspondent pas";
            }

            if (User::findByEmail($email)) {
                $errors[] = "Email déjà utilisé";
            }

            if (!empty($errors)) {
                return view('register', ['error' => implode('<br>', $errors)]);
            }

            // Création de l'utilisateur
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => $hashed_password,
            ]);

            // Connexion automatique après inscription
            $newUser = User::findByEmail($email);
            $_SESSION['user'] = [
                'id'    => $newUser['id'],
                'name'  => $newUser['name'],
                'email' => $newUser['email'],
            ];

            redirect('/dashboard');
        }

        // Afficher le formulaire si GET
        view('register');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        redirect('/login');
    }
}

