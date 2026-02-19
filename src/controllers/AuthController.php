<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\UserModel;
use App\Models\WalletModel;

class AuthController extends BaseController
{
    private UserModel $userModel;
    private WalletModel $walletModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->walletModel = new WalletModel();
        $this->ensureCsrfToken();
    }

    private function ensureCsrfToken(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    private function validateCsrfToken(?string $token): bool
    {
        return !empty($token)
            && isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
                return $this->view('pages/login', [
                    'error' => "Session invalide, veuillez réessayer."
                ]);
            }

            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $errors   = [];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide.";
            }
            if (empty($password)) {
                $errors[] = "Mot de passe requis.";
            }

            $user = $this->userModel->findByEmail($email);
            if (!$user || !password_verify($password, $user['password'])) {
                $errors[] = "Email ou mot de passe incorrect.";
            }

            if (!empty($errors)) {
                return $this->view('pages/login', [
                    'error' => implode('<br>', $errors),
                    'email' => $email
                ]);
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = [
                'id'      => $user['id'],
                'name'    => $user['name'] ?? $user['username'],
                'email'   => $user['email'],
                'balance' => $this->walletModel->getAvailableBalance($user['id']),
                'role'    => $user['role'] ?? 'user'
            ];

            $this->redirect('/dashboard');
        }

        $this->view('pages/login');
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
                return $this->view('pages/register', [
                    'error' => "Session invalide, veuillez réessayer."
                ]);
            }

            $name            = trim($_POST['name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $password        = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $terms           = isset($_POST['terms']);

            $errors = [];

            if (empty($name)) $errors[] = "Nom complet requis.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
            if (strlen($password) < 6) $errors[] = "Mot de passe (min 6 caractères).";
            if ($password !== $passwordConfirm) $errors[] = "Les mots de passe ne correspondent pas.";
            if (!$terms) $errors[] = "Vous devez accepter les conditions.";
            if ($this->userModel->findByEmail($email)) $errors[] = "Cet email est déjà utilisé.";

            if (!empty($errors)) {
                return $this->view('pages/register', [
                    'error' => implode('<br>', $errors),
                    'name'  => $name,
                    'email' => $email
                ]);
            }

            // Création utilisateur
            $userId = $this->userModel->create([
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            ]);

            if (!$userId) {
                return $this->view('pages/register', [
                    'error' => "Erreur lors de la création du compte."
                ]);
            }

            // Création wallet (retour booléen)
            if (!$this->walletModel->create($userId)) {
                // Rollback : supprimer l'utilisateur créé
                $this->userModel->delete($userId);
                return $this->view('pages/register', [
                    'error' => "Erreur lors de la création du portefeuille."
                ]);
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $userId;
            $_SESSION['user'] = [
                'id'      => $userId,
                'name'    => $name,
                'email'   => $email,
                'balance' => $this->walletModel->getAvailableBalance($userId),
                'role'    => 'user'
            ];

            $this->redirect('/dashboard');
        }

        $this->view('pages/register');
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }
}