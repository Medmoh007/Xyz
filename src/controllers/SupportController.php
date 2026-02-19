<?php
namespace App\Controllers;

use App\Lib\BaseController;
use App\Models\SupportModel;
use App\Models\FaqModel;

class SupportController extends BaseController
{
    private $supportModel;
    private $faqModel;

    public function __construct()
    {
        parent::__construct();
        $this->supportModel = new SupportModel();
        $this->faqModel = new FaqModel();
    }

    /**
     * Affiche la page de support principale
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté pour pré-remplir le formulaire
        $userData = [];
        if (isset($_SESSION['user_id'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->findById($_SESSION['user_id']);
            if ($user) {
                $userData = [
                    'name' => $user['name'] ?? '',
                    'email' => $user['email'] ?? ''
                ];
            }
        }

        // Récupérer les FAQ
        $faqs = $this->faqModel->getAllByCategory();
        $popularFaqs = $this->faqModel->getPopularFaqs(3);

        // Statistiques (optionnel)
        $stats = $this->supportModel->getSupportStats();

        // Préparer les données pour la vue
        $data = [
            'title' => 'Support & Informations | COMCV Trading',
            'userData' => $userData,
            'faqs' => $faqs,
            'popularFaqs' => $popularFaqs,
            'stats' => $stats,
            'success' => $_SESSION['form_success'] ?? null,
            'error' => $_SESSION['form_error'] ?? null
        ];

        // Effacer les messages flash après lecture
        unset($_SESSION['form_success']);
        unset($_SESSION['form_error']);

        // Afficher la vue
        $this->view('pages/support', $data);
    }

    /**
     * Traite la soumission du formulaire de contact
     */
    public function submitContact()
    {
        // Vérifier le token CSRF
        if (!$this->verifyCsrfToken($_POST['csrf'] ?? '')) {
            $_SESSION['form_error'] = 'Token de sécurité invalide. Veuillez réessayer.';
            header('Location: ' . BASE_URL . '/support');
            exit;
        }

        // Valider les données
        $errors = $this->validateContactForm($_POST);

        if (!empty($errors)) {
            $_SESSION['form_error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/support#contact');
            exit;
        }

        // Vérifier si l'utilisateur a déjà soumis une demande récemment
        $hasRecentRequest = $this->supportModel->checkRecentRequest($_POST['email']);
        if ($hasRecentRequest) {
            $_SESSION['form_error'] = 'Vous avez déjà soumis une demande récemment. Veuillez patienter avant de soumettre une nouvelle demande.';
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/support#contact');
            exit;
        }

        // Préparer les données
        $contactData = [
            'name' => htmlspecialchars(trim($_POST['name'])),
            'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
            'subject' => htmlspecialchars(trim($_POST['subject'])),
            'message' => htmlspecialchars(trim($_POST['message'])),
            'user_id' => $_SESSION['user_id'] ?? null
        ];

        // Enregistrer en base de données
        $saved = $this->supportModel->createSupportRequest($contactData);

        if ($saved) {
            // Envoyer une notification email (optionnel)
            $this->supportModel->sendNotificationEmail($contactData);

            // Message de succès
            $_SESSION['form_success'] = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les 24 heures.';
            
            // Effacer les données du formulaire
            unset($_SESSION['form_data']);
            
        } else {
            $_SESSION['form_error'] = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';
            $_SESSION['form_data'] = $_POST;
        }

        header('Location: ' . BASE_URL . '/support#contact');
        exit;
    }

    /**
     * Valide le formulaire de contact
     */
    private function validateContactForm($data)
    {
        $errors = [];

        // Nom
        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors[] = 'Le nom doit contenir au moins 2 caractères.';
        }

        // Email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Veuillez saisir une adresse email valide.';
        }

        // Sujet
        if (empty($data['subject'])) {
            $errors[] = 'Veuillez sélectionner un sujet.';
        }

        // Message
        if (empty($data['message']) || strlen($data['message']) < 10) {
            $errors[] = 'Le message doit contenir au moins 10 caractères.';
        }

        // Vérifier la longueur maximale
        if (strlen($data['message']) > 2000) {
            $errors[] = 'Le message ne doit pas dépasser 2000 caractères.';
        }

        return $errors;
    }

    /**
     * Recherche dans les FAQ (AJAX)
     */
    public function searchFaq()
    {
        // Vérifier si c'est une requête AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès non autorisé']);
            exit;
        }

        $query = $_POST['query'] ?? '';

        if (strlen($query) < 2) {
            echo json_encode(['results' => [], 'count' => 0]);
            exit;
        }

        $results = $this->faqModel->search($query);

        echo json_encode([
            'results' => $results,
            'count' => count($results)
        ]);
        exit;
    }

    /**
     * Enregistre une vue de FAQ (AJAX)
     */
    public function logFaqView()
    {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(403);
            exit;
        }

        $faqId = $_POST['faq_id'] ?? 0;

        if ($faqId > 0) {
            $this->supportModel->logFaqView($faqId);
        }

        echo json_encode(['success' => true]);
        exit;
    }
}