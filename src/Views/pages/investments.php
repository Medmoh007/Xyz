<?php
// src/Views/pages/investments.php
// Variables attendues du contrôleur :
// - $plans : array (structure: ['starter' => ['min'=>50, 'max'=>499, 'daily'=>1.5, 'duration'=>30, 'name'=>'Starter Plan'], ...])
// - $investments : array (investissements de l'utilisateur)
// - $user : array (avec 'balance')
// - $success : string|null
// - $error : string|null
// - $totalInvested, $totalProfits, $activeInvestments (statistiques)

// Valeurs par défaut
$plans = $plans ?? [];
$investments = $investments ?? [];
$user = $user ?? ['balance' => 0];
$success = $success ?? $_SESSION['success'] ?? null;
$error = $error ?? $_SESSION['error'] ?? null;
$totalInvested = $totalInvested ?? array_sum(array_column($investments, 'amount'));
$totalProfits = $totalProfits ?? array_sum(array_column($investments, 'total_profit'));
$activeInvestments = $activeInvestments ?? count(array_filter($investments, fn($inv) => ($inv['status'] ?? '') === 'active'));

// Nettoyer les messages de session après les avoir récupérés
unset($_SESSION['success'], $_SESSION['error']);

$title = $title ?? 'Investissements';
ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> | COMCV Trading</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ============================================
           INVESTMENTS PAGE STYLES (identiques à l'original, inchangés)
           ============================================ */
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

        /* ============ NAVIGATION ============ */
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

        /* Container principal */
        .dashboard {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        /* Messages */
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

        /* Cartes */
        .card {
            background: linear-gradient(135deg, var(--card-bg), #1a1c2b);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--profit-color));
        }

        .card h2 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .subtitle {
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        /* Plans d'investissement */
        .investment-plans {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .plan-card {
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(240, 185, 11, 0.1);
        }

        .plan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .plan-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.3rem;
        }

        .plan-badge {
            background: linear-gradient(45deg, var(--primary-color), var(--background-darker));
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .plan-price {
            text-align: center;
            margin: 25px 0;
            padding: 20px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .price {
            display: block;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
        }

        .duration {
            display: block;
            margin-top: 8px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .plan-features {
            margin: 25px 0;
        }

        .feature {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed var(--border-color);
        }

        .feature:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .feature-label {
            color: var(--text-secondary);
        }

        .feature-value {
            font-weight: 600;
            color: var(--text-primary);
        }

        .feature-value.highlight {
            color: var(--profit-color);
            font-size: 1.1rem;
        }

        .btn-invest {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary-color), #ff9900);
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-invest:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 185, 11, 0.3);
        }

        .btn-invest:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--border-color);
            color: var(--text-secondary);
        }

        /* Info solde */
        .balance-info {
            margin-top: 30px;
            padding: 20px;
            background: var(--background-darker);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .balance-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .balance-display span {
            color: var(--text-secondary);
        }

        .balance-display strong {
            color: var(--text-primary);
            font-size: 1.2rem;
        }

        .balance-warning {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid rgba(246, 70, 93, 0.3);
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--loss-color);
        }

        .balance-warning a {
            color: var(--loss-color);
            text-decoration: underline;
        }

        /* Investissements actifs */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .investment-filters {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 8px 16px;
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .filter-btn.active {
            background: var(--primary-color);
            color: white;
        }

        .filter-btn:hover:not(.active) {
            border-color: var(--text-secondary);
        }

        .investments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .investment-item {
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .investment-item.active {
            border-left: 4px solid var(--profit-color);
        }

        .investment-item.completed {
            border-left: 4px solid var(--text-secondary);
        }

        .investment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .investment-title {
            display: flex;
            flex-direction: column;
        }

        .investment-title h4 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .investment-id {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 2px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge.active {
            background: rgba(14, 203, 129, 0.2);
            color: var(--profit-color);
        }

        .status-badge.completed {
            background: rgba(132, 142, 156, 0.2);
            color: var(--text-secondary);
        }

        .investment-progress {
            margin: 15px 0;
        }

        .progress-bar {
            height: 6px;
            background: var(--border-color);
            border-radius: 3px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--profit-color));
            transition: width 0.3s ease;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .investment-details {
            margin: 15px 0;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 10px;
        }

        .detail {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .detail-value {
            color: var(--text-primary);
            font-weight: 600;
        }

        .detail-value.highlight {
            color: var(--profit-color);
        }

        .detail-value.profit {
            color: var(--profit-color);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .investment-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-view, .btn-info {
            flex: 1;
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-view:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-info {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            cursor: pointer;
        }

        .btn-info:hover {
            border-color: var(--text-secondary);
            color: var(--text-primary);
        }

        /* États vides */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .btn-trade {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-trade:hover {
            background: #f5c542;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 185, 11, 0.3);
        }

        /* Statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .stat-card {
            background: rgba(240, 185, 11, 0.1);
            border: 1px solid rgba(240, 185, 11, 0.2);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-value.profit {
            color: var(--profit-color);
        }

        /* Modal */
        .investment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .investment-modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--background-dark);
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            border: 1px solid var(--border-color);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
        }

        .modal-body {
            padding: 20px;
        }

        .confirmation-icon {
            text-align: center;
            margin-bottom: 20px;
        }

        .confirmation-icon i {
            font-size: 3rem;
            color: var(--primary-color);
        }

        .confirmation-details {
            background: var(--background-darker);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .confirmation-details h4 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--text-primary);
        }

        .detail-row, .profit-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .profit-estimation {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .profit-estimation h5 {
            margin-bottom: 15px;
            color: var(--text-primary);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .btn-cancel, .btn-confirm {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-cancel:hover {
            background: var(--border-color);
        }

        .btn-confirm {
            background: var(--profit-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-confirm:hover {
            background: #0ebc75;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .investment-plans {
                grid-template-columns: 1fr;
            }
            
            .investments-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .investment-filters {
                width: 100%;
                justify-content: center;
            }
            
            .detail-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .modal-content {
                width: 95%;
            }
            
            .nav-menu {
                display: none; /* Pour mobile, à améliorer avec menu hamburger */
            }
        }

        @media (max-width: 480px) {
            .dashboard {
                padding: 10px;
            }
            
            .card {
                padding: 15px;
            }
            
            .price {
                font-size: 2rem;
            }
            
            .investment-actions {
                flex-direction: column;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- NAVIGATION -->
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
                <a href="<?= BASE_URL ?>/investments" class="nav-item active">
                    <i class="fas fa-briefcase"></i> Investments
                </a>
                <a href="<?= BASE_URL ?>/wallet" class="nav-item">
                    <i class="fas fa-wallet"></i> Wallet
                </a>
                <a href="<?= BASE_URL ?>/trade" class="nav-item">
                    <i class="fas fa-exchange-alt"></i> Trade
                </a>
                <a href="<?= BASE_URL ?>/logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Messages de notification -->
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

    <div class="dashboard">
        <!-- SECTION DES PLANS D'INVESTISSEMENT -->
        <div class="card fade-in">
            <h2><i class="fas fa-chart-line"></i> Plans d'Investissement</h2>
            <p class="subtitle">Choisissez un plan et commencez à générer des profits automatiquement</p>

            <?php if (empty($plans)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-box-open"></i></div>
                    <h3>Aucun plan disponible</h3>
                    <p>Veuillez réessayer ultérieurement.</p>
                </div>
            <?php else: ?>
                <div class="investment-plans">
                    <?php foreach ($plans as $key => $plan): ?>
                    <div class="plan-card fade-in" 
                         data-plan-key="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                         data-plan-amount="<?= $plan['min'] ?>"
                         data-plan-rate="<?= $plan['daily'] ?>"
                         data-plan-duration="<?= $plan['duration'] ?? 30 ?>"
                         data-plan-name="<?= htmlspecialchars($plan['name'] ?? ucfirst($key) . ' Plan', ENT_QUOTES, 'UTF-8') ?>">
                        <div class="plan-header">
                            <h3><?= htmlspecialchars($plan['name'] ?? ucfirst($key) . ' Plan', ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if ($key === 'premium'): ?>
                                <span class="plan-badge">Recommandé</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="plan-price">
                            <span class="price">$<?= number_format($plan['min'], 2) ?></span>
                            <span class="duration">Durée: <?= $plan['duration'] ?? 30 ?> jours</span>
                        </div>
                        
                        <div class="plan-features">
                            <div class="feature">
                                <span class="feature-label">Rendement journalier:</span>
                                <span class="feature-value highlight"><?= $plan['daily'] ?>%</span>
                            </div>
                            <div class="feature">
                                <span class="feature-label">Rendement total estimé:</span>
                                <span class="feature-value">
                                    $<?= number_format($plan['min'] * ($plan['daily'] / 100) * ($plan['duration'] ?? 30), 2) ?>
                                </span>
                            </div>
                            <div class="feature">
                                <span class="feature-label">Paiement des profits:</span>
                                <span class="feature-value">Tous les 2 jours</span>
                            </div>
                            <div class="feature">
                                <span class="feature-label">Montant minimum:</span>
                                <span class="feature-value">$<?= number_format($plan['min'], 2) ?></span>
                            </div>
                        </div>
                        
                        <button type="button" class="btn-invest" 
                                <?= ($user['balance'] ?? 0) < $plan['min'] ? 'disabled' : '' ?>>
                            <?php if (($user['balance'] ?? 0) < $plan['min']): ?>
                                <i class="fas fa-lock"></i> Solde insuffisant
                            <?php else: ?>
                                <i class="fas fa-bolt"></i> Investir Maintenant
                            <?php endif; ?>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="balance-info">
                <div class="balance-display">
                    <span>Solde disponible:</span>
                    <strong>$<?= number_format($user['balance'] ?? 0, 2) ?></strong>
                </div>
                <?php if (($user['balance'] ?? 0) < 50): ?>
                <div class="balance-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Minimum requis: $50. <a href="<?= BASE_URL ?>/deposit">Déposer des fonds</a></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- SECTION DES INVESTISSEMENTS DE L'UTILISATEUR -->
        <div class="card fade-in">
            <div class="card-header">
                <h2><i class="fas fa-briefcase"></i> Mes Investissements</h2>
                <div class="investment-filters">
                    <button class="filter-btn active" data-filter="all">Tous</button>
                    <button class="filter-btn" data-filter="active">Actifs</button>
                    <button class="filter-btn" data-filter="completed">Terminés</button>
                </div>
            </div>
            
            <?php if (empty($investments)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-chart-pie"></i></div>
                <h3>Aucun investissement</h3>
                <p>Commencez par investir dans l'un de nos plans pour générer des profits.</p>
                <a href="#plans" class="btn-trade" id="scrollToPlans">
                    <i class="fas fa-rocket"></i> Commencer à investir
                </a>
            </div>
            <?php else: ?>
            <div class="investments-grid">
                <?php foreach ($investments as $inv): ?>
                <div class="investment-item fade-in <?= htmlspecialchars($inv['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                     data-status="<?= htmlspecialchars($inv['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                     data-id="<?= $inv['id'] ?>">
                    <div class="investment-header">
                        <div class="investment-title">
                            <h4><?= htmlspecialchars($inv['plan_name'] ?? $inv['name'] ?? 'Investissement', ENT_QUOTES, 'UTF-8') ?></h4>
                            <span class="investment-id">#<?= $inv['id'] ?></span>
                        </div>
                        <span class="status-badge <?= htmlspecialchars($inv['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <i class="fas fa-circle"></i> <?= strtoupper(htmlspecialchars($inv['status'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                        </span>
                    </div>
                    
                    <?php if (isset($inv['start_date']) && isset($inv['end_date'])): ?>
                    <?php
                        $start = new DateTime($inv['start_date']);
                        $end = new DateTime($inv['end_date']);
                        $now = new DateTime();
                        $totalDays = max(1, $start->diff($end)->days);
                        $passedDays = max(0, $start->diff($now)->days);
                        $progress = min(100, ($passedDays / $totalDays) * 100);
                    ?>
                    <div class="investment-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                        </div>
                        <div class="progress-labels">
                            <span><?= date('d/m/Y', strtotime($inv['start_date'])) ?></span>
                            <span><?= round($progress) ?>%</span>
                            <span><?= date('d/m/Y', strtotime($inv['end_date'])) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="investment-details">
                        <div class="detail-row">
                            <div class="detail">
                                <span class="detail-label">Montant investi:</span>
                                <span class="detail-value">$<?= number_format($inv['amount'], 2) ?></span>
                            </div>
                            <div class="detail">
                                <span class="detail-label">Taux journalier:</span>
                                <span class="detail-value highlight"><?= number_format($inv['daily_rate'] ?? $inv['rate'] ?? 0, 2) ?>%</span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail">
                                <span class="detail-label">Profit total:</span>
                                <span class="detail-value profit">
                                    <i class="fas fa-arrow-up"></i> +$<?= number_format($inv['total_profit'] ?? 0, 2) ?>
                                </span>
                            </div>
                            <div class="detail">
                                <span class="detail-label">Prochain profit:</span>
                                <span class="detail-value">
                                    <?php if (($inv['status'] ?? '') === 'active'): ?>
                                    <i class="fas fa-clock"></i> 48h
                                    <?php else: ?>
                                    <i class="fas fa-check"></i> Terminé
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="investment-actions">
                        <a href="<?= BASE_URL ?>/investments/<?= $inv['id'] ?>" class="btn-view">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                        <?php if (($inv['status'] ?? '') === 'active'): ?>
                        <button class="btn-info" data-investment-id="<?= $inv['id'] ?>">
                            <i class="fas fa-chart-line"></i> Suivre
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- STATISTIQUES -->
        <div class="card fade-in">
            <h2><i class="fas fa-chart-bar"></i> Statistiques d'Investissement</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Total Investi</span>
                        <span class="stat-value">$<?= number_format($totalInvested, 2) ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Profit Total</span>
                        <span class="stat-value profit">+$<?= number_format($totalProfits, 2) ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">Investissements Actifs</span>
                        <span class="stat-value"><?= $activeInvestments ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                    <div class="stat-content">
                        <span class="stat-label">ROI Moyen</span>
                        <span class="stat-value"><?= $totalInvested > 0 ? number_format(($totalProfits / $totalInvested) * 100, 1) : '0.0' ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMATION D'INVESTISSEMENT -->
    <div id="investmentModal" class="investment-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Confirmer l'Investissement</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirmation-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="confirmation-details">
                    <h4 id="modalPlanName"></h4>
                    <div class="detail-row">
                        <span>Montant:</span>
                        <strong id="modalPlanAmount"></strong>
                    </div>
                    <div class="detail-row">
                        <span>Taux journalier:</span>
                        <strong id="modalPlanRate"></strong>
                    </div>
                    <div class="detail-row">
                        <span>Durée:</span>
                        <strong id="modalPlanDuration"></strong>
                    </div>
                    <div class="profit-estimation">
                        <h5>Estimation des Profits:</h5>
                        <div class="profit-row">
                            <span>Quotidien:</span>
                            <strong id="modalDailyProfit"></strong>
                        </div>
                        <div class="profit-row">
                            <span>Mensuel:</span>
                            <strong id="modalMonthlyProfit"></strong>
                        </div>
                        <div class="profit-row">
                            <span>Total (sur durée):</span>
                            <strong id="modalTotalProfit"></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn-cancel">Annuler</button>
                    <form id="confirmInvestmentForm" method="POST" action="<?= BASE_URL ?>/invest/buy">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="plan_key" id="modalPlanKey">
                        <button type="submit" class="btn-confirm">
                            <i class="fas fa-lock"></i> Confirmer & Investir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== MODAL D'INVESTISSEMENT ==========
            const investButtons = document.querySelectorAll('.btn-invest:not([disabled])');
            const modal = document.getElementById('investmentModal');
            const closeModal = document.querySelector('.close-modal');
            const cancelBtn = document.querySelector('.btn-cancel');

            investButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.plan-card');
                    const planKey = card.dataset.planKey;
                    const planName = card.dataset.planName;
                    const planAmount = parseFloat(card.dataset.planAmount);
                    const planRate = parseFloat(card.dataset.planRate);
                    const planDuration = parseInt(card.dataset.planDuration) || 30;

                    const dailyProfit = planAmount * (planRate / 100);
                    const monthlyProfit = dailyProfit * 30;
                    const totalProfit = dailyProfit * planDuration;

                    document.getElementById('modalPlanName').textContent = planName;
                    document.getElementById('modalPlanAmount').textContent = '$' + planAmount.toFixed(2);
                    document.getElementById('modalPlanRate').textContent = planRate + '%';
                    document.getElementById('modalPlanDuration').textContent = planDuration + ' jours';
                    document.getElementById('modalDailyProfit').textContent = '$' + dailyProfit.toFixed(2);
                    document.getElementById('modalMonthlyProfit').textContent = '$' + monthlyProfit.toFixed(2);
                    document.getElementById('modalTotalProfit').textContent = '$' + totalProfit.toFixed(2);
                    document.getElementById('modalPlanKey').value = planKey;

                    modal.classList.add('show');
                });
            });

            function closeModalHandler() {
                modal.classList.remove('show');
            }
            if (closeModal) closeModal.addEventListener('click', closeModalHandler);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModalHandler);
            window.addEventListener('click', function(e) {
                if (e.target === modal) closeModalHandler();
            });

            // ========== FILTRES ==========
            const filterButtons = document.querySelectorAll('.filter-btn');
            const investmentItems = document.querySelectorAll('.investment-item');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const filter = this.dataset.filter;
                    investmentItems.forEach(item => {
                        const status = item.dataset.status;
                        if (filter === 'all' || status === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // ========== ANIMATIONS ==========
            const planCards = document.querySelectorAll('.plan-card');
            planCards.forEach((card, i) => {
                card.style.animationDelay = (i * 0.1) + 's';
            });

            // ========== SCROLL VERS LES PLANS ==========
            const scrollBtn = document.getElementById('scrollToPlans');
            if (scrollBtn) {
                scrollBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector('.investment-plans').scrollIntoView({ behavior: 'smooth' });
                });
            }

            // ========== BOUTON SUIVRE ==========
            const followBtns = document.querySelectorAll('.btn-info');
            followBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const invId = this.dataset.investmentId;
                    alert('Fonctionnalité de suivi pour l\'investissement #' + invId + ' (à implémenter)');
                });
            });
        });
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>