<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\WalletModel;
use App\Models\DepositModel;
use App\Models\UserModel;

class DepositController extends BaseController
{
    private WalletModel $walletModel;
    private DepositModel $depositModel;
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        // Vérification de l'authentification via BaseController
        $this->checkAuth();

        $this->walletModel   = new WalletModel();
        $this->depositModel  = new DepositModel();
        $this->userModel     = new UserModel();
    }

    /**
     * Affiche la page de dépôt
     */
    public function index(): void
    {
        $userId = $_SESSION['user_id'];

        // Récupérer le wallet de l'utilisateur
        $wallet = $this->walletModel->getByUser($userId);
        if (!$wallet) {
            // Si pas de wallet, en créer un (normalement déjà fait à l'inscription)
            $this->walletModel->create($userId);
            $wallet = $this->walletModel->getByUser($userId);
        }

        // Récupérer l'historique des dépôts
        $deposits = $this->depositModel->getUserDeposits($userId);

        // Récupérer l'utilisateur (pour le solde, etc.)
        $user = $this->userModel->find($userId);
        $user['balance'] = $this->walletModel->getAvailableBalance($userId);

        $this->view('pages/deposit', [
            'wallet'    => $wallet,
            'deposits'  => $deposits,
            'user'      => $user,
            'title'     => 'Dépôt | COMCV Trading',
            'success'   => $_SESSION['success'] ?? null,
            'error'     => $_SESSION['error'] ?? null
        ]);

        // Nettoyer les messages flash après affichage
        unset($_SESSION['success'], $_SESSION['error']);
    }

    /**
     * Traite la soumission d'un dépôt
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/deposit');
        }

        // Validation CSRF
        if (!$this->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Requête invalide (CSRF).";
            $this->redirect('/deposit');
        }

        $userId = $_SESSION['user_id'];
        $amount = (float) ($_POST['amount'] ?? 0);
        $wallet = $this->walletModel->getByUser($userId);

        if (!$wallet) {
            $_SESSION['error'] = "Wallet non trouvé.";
            $this->redirect('/deposit');
        }

        // Validation du montant
        if ($amount < 10) {
            $_SESSION['error'] = "Le montant minimum est de 10 USDT.";
            $this->redirect('/deposit');
        }

        // Gestion de la preuve de dépôt (upload)
        $screenshotPath = null;
        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['proof'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 5 * 1024 * 1024; // 5 Mo

            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['error'] = "Format de fichier non autorisé (JPEG/PNG uniquement).";
                $this->redirect('/deposit');
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['error'] = "Le fichier ne doit pas dépasser 5 Mo.";
                $this->redirect('/deposit');
            }

            // Générer un nom unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'deposit_' . $userId . '_' . time() . '.' . $extension;
            $uploadDir = __DIR__ . '/../../public/uploads/deposits/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $screenshotPath = '/uploads/deposits/' . $filename;
            } else {
                $_SESSION['error'] = "Erreur lors du téléchargement de la preuve.";
                $this->redirect('/deposit');
            }
        } else {
            $_SESSION['error'] = "Veuillez fournir une preuve de transaction.";
            $this->redirect('/deposit');
        }

        // Création du dépôt
        $depositId = $this->depositModel->create($userId, $amount, $screenshotPath);
        
        if ($depositId) {
            $_SESSION['success'] = "✅ Votre demande de dépôt de $" . number_format($amount, 2) . " a été enregistrée. En attente de validation.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'enregistrement du dépôt.";
        }

        $this->redirect('/deposit');
    }
}