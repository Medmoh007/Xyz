<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\UserModel;
use App\Models\DepositModel;
use App\Models\WithdrawalModel;

class AdminController extends BaseController
{
    private UserModel $userModel;
    private DepositModel $depositModel;
    private WithdrawalModel $withdrawalModel;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();         // utilisateur connecté ?
        $this->checkAdminRole();    // est-il admin ?
        
        $this->userModel = new UserModel();
        $this->depositModel = new DepositModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    /**
     * Vérifie que l'utilisateur a le rôle 'admin'
     */
    private function checkAdminRole(): void
    {
        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
    }

    /**
     * Dashboard admin (page d'accueil)
     */
    public function index(): void
    {
        $this->view('admin/dashboard');
    }

    /**
     * Liste des dépôts en attente
     */
    public function depositsPending(): void
    {
        $deposits = $this->depositModel->getPendingDeposits();
        $this->view('admin/deposits_pending', ['deposits' => $deposits]);
    }

    /**
     * Approuver un dépôt
     */
    public function approveDeposit(int $id): void
    {
        if ($this->depositModel->approve($id)) {
            $_SESSION['success'] = "Dépôt #$id approuvé et crédité.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'approbation.";
        }
        $this->redirect('/admin/deposits/pending');
    }

    /**
     * Rejeter un dépôt
     */
    public function rejectDeposit(int $id): void
    {
        if ($this->depositModel->reject($id)) {
            $_SESSION['success'] = "Dépôt #$id rejeté.";
        } else {
            $_SESSION['error'] = "Erreur lors du rejet.";
        }
        $this->redirect('/admin/deposits/pending');
    }
}