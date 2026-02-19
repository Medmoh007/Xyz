<?php
// src/Views/pages/deposit.php
// Variables attendues du contrôleur :
// - $deposits : array
// - $wallet : array (balance, address, network)
// - $success : string|null
// - $error : string|null
// - $title : string

$deposits = $deposits ?? [];
$wallet = $wallet ?? ['balance' => 0, 'address' => 'Non disponible', 'network' => 'TRC20'];
$success = $success ?? $_SESSION['success'] ?? null;
$error = $error ?? $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$title = $title ?? 'Dépôt | COMCV Trading';
ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ========== STYLES IDENTIQUES À LA VERSION ORIGINALE ========== */
        :root {
            --primary-color: #f0b90b;
            --profit-color: #0ecb81;
            --loss-color: #f6465d;
            --background-dark: #181a20;
            --background-darker: #0b0e11;
            --border-color: #2b3139;
            --text-primary: #eaecef;
            --text-secondary: #848e9c;
            --card-bg: #1e2026;
        }

        body {
            background-color: var(--background-darker);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .main-nav {
            background: var(--background-dark);
            border-bottom: 1px solid var(--border-color);
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-brand-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--background-dark);
            font-size: 1.2rem;
        }

        .nav-brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary-color), #ff9900);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-item {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 0;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item:hover {
            color: var(--text-primary);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .deposit-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--text-primary);
        }

        .methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .method-card {
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 25px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .method-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(240, 185, 11, 0.1);
        }

        .method-card.active {
            border-color: var(--primary-color);
            background: rgba(240, 185, 11, 0.05);
        }

        .method-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: white;
        }

        .method-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .method-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .method-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--profit-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .deposit-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--background-darker);
            color: var(--text-primary);
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .input-with-icon input {
            padding-left: 45px;
        }

        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--background-darker);
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(240, 185, 11, 0.05);
        }

        .upload-area i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .upload-area p {
            color: var(--text-secondary);
            margin: 0;
        }

        .upload-area span {
            color: var(--primary-color);
            text-decoration: underline;
        }

        #previewContainer {
            margin-top: 15px;
            display: none;
        }

        #previewImage {
            max-width: 200px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary-color), #ff9900);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(240, 185, 11, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .instructions {
            background: var(--background-darker);
            border-radius: 10px;
            padding: 25px;
            margin-top: 40px;
            border-left: 4px solid var(--primary-color);
        }

        .instructions h4 {
            color: var(--text-primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed var(--border-color);
        }

        .step:last-child {
            border-bottom: none;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }

        .step-content h5 {
            margin: 0 0 5px 0;
            color: var(--text-primary);
        }

        .step-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .history-table th {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .history-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-approved {
            background: rgba(14, 203, 129, 0.1);
            color: var(--profit-color);
        }

        .status-rejected {
            background: rgba(246, 70, 93, 0.1);
            color: var(--loss-color);
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            .methods-grid {
                grid-template-columns: 1fr;
            }
            .deposit-container {
                padding: 0 15px;
            }
            .card {
                padding: 20px;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .session-message {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .alert-success {
            background: rgba(14, 203, 129, 0.1);
            border: 1px solid var(--profit-color);
            color: var(--profit-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid var(--loss-color);
            color: var(--loss-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="<?= BASE_URL ?>/home" class="nav-brand">
                <div class="nav-brand-logo">C</div>
                <div class="nav-brand-text">COMCV Trading</div>
            </a>
            
            <div class="nav-menu">
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item <?= basename($_SERVER['REQUEST_URI']) === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="<?= BASE_URL ?>/investments" class="nav-item <?= basename($_SERVER['REQUEST_URI']) === 'investments' ? 'active' : '' ?>">
                    <i class="fas fa-briefcase"></i> Investments
                </a>
                <a href="<?= BASE_URL ?>/wallet" class="nav-item <?= basename($_SERVER['REQUEST_URI']) === 'wallet' ? 'active' : '' ?>">
                    <i class="fas fa-wallet"></i> Wallet
                </a>
                <a href="<?= BASE_URL ?>/trade" class="nav-item <?= basename($_SERVER['REQUEST_URI']) === 'trade' ? 'active' : '' ?>">
                    <i class="fas fa-exchange-alt"></i> Trade
                </a>
                <a href="<?= BASE_URL ?>/logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Messages flash -->
    <div class="session-message">
        <?php if ($success): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="deposit-container">
        <!-- Carte principale -->
        <div class="card fade-in">
            <div class="card-header">
                <i class="fas fa-arrow-down"></i>
                <h2>Déposer des Fonds</h2>
            </div>

            <div class="methods-grid">
                <div class="method-card active" id="cryptoMethod">
                    <div class="method-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="method-title">Cryptomonnaie</div>
                    <div class="method-description">
                        Déposez USDT via le réseau TRC20. Rapide et sécurisé.
                    </div>
                    <div class="method-badge">Recommandé</div>
                </div>
                <div class="method-card" id="cardMethod">
                    <div class="method-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="method-title">Carte Bancaire</div>
                    <div class="method-description">
                        Visa, Mastercard, et cartes prépayées acceptées.
                    </div>
                    <div class="method-badge">Bientôt disponible</div>
                </div>
                <div class="method-card" id="bankMethod">
                    <div class="method-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="method-title">Virement Bancaire</div>
                    <div class="method-description">
                        Transférez depuis votre compte bancaire.
                    </div>
                    <div class="method-badge">Bientôt disponible</div>
                </div>
            </div>

            <!-- Formulaire Crypto -->
            <div id="cryptoForm" class="deposit-form">
                <form action="<?= BASE_URL ?>/deposit/store" method="POST" enctype="multipart/form-data">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="form-group">
                        <label class="form-label">Montant (USDT)</label>
                        <div class="input-with-icon">
                            <i class="fas fa-dollar-sign"></i>
                            <input type="number" 
                                   class="form-input" 
                                   name="amount" 
                                   min="10" 
                                   step="0.01" 
                                   placeholder="0.00" 
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Adresse de dépôt</label>
                        <div class="address-display" style="background: var(--background-darker); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="color: var(--text-secondary); font-size: 0.9rem;">Votre adresse TRC20</span>
                                <button type="button" id="copyAddressBtn" style="background: var(--primary-color); color: white; border: none; padding: 5px 15px; border-radius: 6px; cursor: pointer;">
                                    <i class="fas fa-copy"></i> Copier
                                </button>
                            </div>
                            <code id="walletAddress" style="word-break: break-all; color: var(--text-primary); font-family: 'Roboto Mono', monospace; font-size: 0.9rem;">
                                <?= htmlspecialchars(DEPOSIT_ADDRESS, ENT_QUOTES, 'UTF-8') ?>
                            </code>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preuve de transaction (Capture d'écran)</label>
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Glissez-déposez votre capture ou <span>cliquez pour sélectionner</span></p>
                            <input type="file" 
                                   id="proofInput" 
                                   name="proof" 
                                   accept="image/*" 
                                   style="display: none;" 
                                   required>
                        </div>
                        <div id="previewContainer">
                            <img id="previewImage" src="" alt="Aperçu">
                            <button type="button" id="removeImageBtn" style="margin-left: 10px; background: var(--loss-color); color: white; border: none; padding: 5px 15px; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Soumettre le dépôt
                    </button>
                </form>
            </div>

            <!-- Formulaires masqués -->
            <div id="cardForm" class="deposit-form" style="display: none;">
                <div style="text-align: center; padding: 40px 0;">
                    <i class="fas fa-clock fa-3x" style="color: var(--text-secondary); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--text-primary);">Bientôt disponible</h3>
                    <p style="color: var(--text-secondary);">Les dépôts par carte bancaire seront disponibles prochainement.</p>
                </div>
            </div>
            <div id="bankForm" class="deposit-form" style="display: none;">
                <div style="text-align: center; padding: 40px 0;">
                    <i class="fas fa-clock fa-3x" style="color: var(--text-secondary); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--text-primary);">Bientôt disponible</h3>
                    <p style="color: var(--text-secondary);">Les virements bancaires seront disponibles prochainement.</p>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="instructions fade-in">
            <h4><i class="fas fa-info-circle"></i> Instructions pour le dépôt crypto</h4>
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h5>Copiez votre adresse</h5>
                    <p>Copiez l'adresse TRC20 ci-dessus ou scannez le QR code sur votre wallet.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h5>Effectuez le transfert</h5>
                    <p>Envoyez uniquement des USDT via le réseau TRC20 depuis votre wallet.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h5>Uploader la preuve</h5>
                    <p>Téléchargez une capture d'écran de la transaction pour validation.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h5>Attendez la confirmation</h5>
                    <p>Les fonds seront crédités dans les 13 minutes après validation.</p>
                </div>
            </div>
        </div>

        <!-- Historique -->
        <div class="card fade-in">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h2>Historique des Dépôts</h2>
            </div>

            <?php if (empty($deposits)): ?>
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-history fa-3x" style="color: var(--text-secondary); margin-bottom: 15px;"></i>
                    <h3 style="color: var(--text-primary);">Aucun dépôt</h3>
                    <p style="color: var(--text-secondary);">Vous n'avez effectué aucun dépôt pour le moment.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deposits as $deposit): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($deposit['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>$<?= number_format($deposit['amount'] ?? 0, 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($deposit['created_at'] ?? 'now')) ?></td>
                                <td>
                                    <span class="status-badge status-<?= htmlspecialchars($deposit['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?= $deposit['status'] === 'pending' ? 'En attente' : ($deposit['status'] === 'approved' ? 'Approuvé' : 'Rejeté') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($deposit['screenshot'])): ?>
                                        <a href="<?= BASE_URL . htmlspecialchars($deposit['screenshot'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                                            <i class="fas fa-eye"></i> Voir preuve
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-secondary);">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélection des méthodes
        const methods = {
            crypto: document.getElementById('cryptoMethod'),
            card: document.getElementById('cardMethod'),
            bank: document.getElementById('bankMethod')
        };
        const forms = {
            crypto: document.getElementById('cryptoForm'),
            card: document.getElementById('cardForm'),
            bank: document.getElementById('bankForm')
        };

        Object.keys(methods).forEach(method => {
            methods[method].addEventListener('click', function() {
                Object.keys(methods).forEach(m => {
                    methods[m].classList.remove('active');
                    forms[m].style.display = 'none';
                });
                this.classList.add('active');
                forms[method].style.display = 'block';
            });
        });

        // Upload
        const uploadArea = document.getElementById('uploadArea');
        const proofInput = document.getElementById('proofInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const removeImageBtn = document.getElementById('removeImageBtn');

        uploadArea.addEventListener('click', () => proofInput.click());
        uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.style.borderColor = 'var(--primary-color)'; });
        uploadArea.addEventListener('dragleave', () => { uploadArea.style.borderColor = 'var(--border-color)'; });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = 'var(--border-color)';
            if (e.dataTransfer.files.length) {
                proofInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });
        proofInput.addEventListener('change', (e) => {
            if (e.target.files.length) handleFileSelect(e.target.files[0]);
        });

        function handleFileSelect(file) {
            if (!file.type.match('image.*')) {
                alert('Veuillez sélectionner une image');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        removeImageBtn.addEventListener('click', () => {
            proofInput.value = '';
            previewContainer.style.display = 'none';
        });

        // Copie adresse
        const copyBtn = document.getElementById('copyAddressBtn');
        const addressElement = document.getElementById('walletAddress');
        copyBtn.addEventListener('click', function() {
            const address = addressElement.textContent.trim();
            if (!address || address === 'Non disponible') {
                alert('Adresse non disponible');
                return;
            }
            navigator.clipboard.writeText(address)
                .then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copié!';
                    this.style.background = 'var(--profit-color)';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.background = 'var(--primary-color)';
                    }, 2000);
                })
                .catch(() => {
                    const textArea = document.createElement('textarea');
                    textArea.value = address;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Adresse copiée!');
                });
        });

        // Validation formulaire
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        form.addEventListener('submit', function(e) {
            const amount = document.querySelector('input[name="amount"]').value;
            const proof = document.querySelector('input[name="proof"]').files[0];
            if (!amount || parseFloat(amount) < 10) {
                e.preventDefault();
                alert('Le montant minimum est de 10 USDT');
                return;
            }
            if (!proof) {
                e.preventDefault();
                alert('Veuillez télécharger une preuve de transaction');
                return;
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();
echo $content;
?>