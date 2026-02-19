<?php
// src/Views/pages/withdrawal.php
// Variables attendues du contrôleur :
// - $user : array (balance)
// - $withdrawals : array
// - $stats : array (total_withdrawn, withdrawal_count, pending_withdrawals)
// - $success : string|null
// - $error : string|null
// - $title : string

$user = $user ?? ['balance' => 0];
$withdrawals = $withdrawals ?? [];
$stats = $stats ?? ['total_withdrawn' => 0, 'withdrawal_count' => 0, 'pending_withdrawals' => false];
$success = $success ?? $_SESSION['success'] ?? null;
$error = $error ?? $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$title = $title ?? 'Retrait - COMCV Trading';
$fees = [
    'TRC20' => 1.0,
    'ERC20' => 10.0,
    'BEP20' => 0.5
];
ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
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

        .withdrawal-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

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
            margin-bottom: 15px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .withdrawal-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .withdrawal-form {
                grid-template-columns: 1fr;
            }
        }

        .form-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--background-darker);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(240, 185, 11, 0.2);
        }

        .amount-input {
            position: relative;
        }

        .currency-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-weight: 600;
        }

        .amount-input .form-control {
            padding-left: 30px;
        }

        .amount-info {
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .btn-max {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-weight: 600;
        }

        .btn-max:hover {
            text-decoration: underline;
        }

        .network-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .network-option {
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--background-darker);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .network-option:hover {
            border-color: var(--text-secondary);
        }

        .network-option.selected {
            border-color: var(--primary-color);
            background: rgba(240, 185, 11, 0.1);
        }

        .network-fee {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        .fee-calculation {
            background: var(--background-darker);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid var(--border-color);
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .fee-row.total {
            border-bottom: none;
            border-top: 1px solid var(--border-color);
            padding-top: 10px;
            margin-top: 5px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .fee-amount {
            color: var(--text-primary);
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary-color), #ff9900);
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 185, 11, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .withdrawals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .withdrawals-table th {
            text-align: left;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.2);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-color);
        }

        .withdrawals-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(240, 185, 11, 0.1);
            color: var(--primary-color);
        }

        .status-approved {
            background: rgba(14, 203, 129, 0.1);
            color: var(--profit-color);
        }

        .status-rejected {
            background: rgba(246, 70, 93, 0.1);
            color: var(--loss-color);
        }

        .warning-box {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid rgba(246, 70, 93, 0.3);
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .warning-icon {
            color: var(--loss-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .warning-content h4 {
            margin: 0 0 5px 0;
            color: var(--text-primary);
        }

        .warning-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .withdrawal-container {
                padding: 0 10px;
            }
            .nav-menu {
                display: none;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .withdrawals-table {
                display: block;
                overflow-x: auto;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
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

    <div class="withdrawal-container">
        <!-- En-tête -->
        <div class="card fade-in">
            <h2><i class="fas fa-arrow-up"></i> Retrait de Fonds</h2>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                Retirez vos fonds vers votre wallet personnel. Les retraits sont traités manuellement dans les 24 heures.
            </p>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Solde Disponible</div>
                    <div class="stat-value">$<?= number_format($user['balance'] ?? 0, 2) ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Total Retiré</div>
                    <div class="stat-value">$<?= number_format($stats['total_withdrawn'] ?? 0, 2) ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-history"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Retraits</div>
                    <div class="stat-value"><?= (int)($stats['withdrawal_count'] ?? 0) ?></div>
                </div>
            </div>
        </div>

        <!-- Avertissement si retrait en attente -->
        <?php if ($stats['pending_withdrawals'] ?? false): ?>
        <div class="warning-box fade-in">
            <div class="warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="warning-content">
                <h4>Retrait en attente</h4>
                <p>Vous avez déjà une demande de retrait en attente de traitement. Vous ne pouvez pas créer une nouvelle demande tant que la précédente n'est pas traitée.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Formulaire de retrait -->
        <div class="card fade-in">
            <h2><i class="fas fa-paper-plane"></i> Nouveau Retrait</h2>
            
            <form id="withdrawalForm" method="POST" action="<?= BASE_URL ?>/withdrawal/store" <?= ($stats['pending_withdrawals'] ?? false) ? 'style="opacity: 0.5; pointer-events: none;"' : '' ?>>
                <!-- Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="withdrawal-form">
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label" for="amount">Montant à retirer (USDT)</label>
                            <div class="amount-input">
                                <span class="currency-prefix">$</span>
                                <input type="number" 
                                       id="amount" 
                                       name="amount" 
                                       class="form-control" 
                                       min="10" 
                                       max="<?= $user['balance'] ?? 0 ?>" 
                                       step="0.01"
                                       placeholder="0.00"
                                       required
                                       <?= ($stats['pending_withdrawals'] ?? false) ? 'disabled' : '' ?>>
                            </div>
                            <div class="amount-info">
                                <span>Minimum: $10.00</span>
                                <button type="button" class="btn-max" id="maxAmountBtn" <?= ($stats['pending_withdrawals'] ?? false) ? 'disabled' : '' ?>>Max</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Réseau</label>
                            <div class="network-options">
                                <?php foreach ($fees as $network => $fee): ?>
                                <div class="network-option <?= $network === 'TRC20' ? 'selected' : '' ?>" data-network="<?= $network ?>" <?= ($stats['pending_withdrawals'] ?? false) ? 'style="pointer-events: none;"' : '' ?>>
                                    <div><?= $network ?></div>
                                    <div class="network-fee">Frais: $<?= number_format($fee, 2) ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" id="network" name="network" value="TRC20">
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label" for="address">Adresse de destination</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   class="form-control" 
                                   placeholder="Ex: TWBquVpXHRYAjBpPazFfLh9kX5Zq..."
                                   pattern="^T[a-zA-Z0-9]{33}$"
                                   title="Adresse TRC20 valide commençant par T"
                                   required
                                   <?= ($stats['pending_withdrawals'] ?? false) ? 'disabled' : '' ?>>
                            <small style="color: var(--text-secondary); font-size: 0.8rem;">
                                Pour le réseau TRC20: doit commencer par 'T' et contenir 34 caractères
                            </small>
                        </div>

                        <div class="fee-calculation">
                            <div class="fee-row">
                                <span>Montant du retrait:</span>
                                <span class="fee-amount" id="displayAmount">$0.00</span>
                            </div>
                            <div class="fee-row">
                                <span>Frais de réseau:</span>
                                <span class="fee-amount" id="feeAmount">$1.00</span>
                            </div>
                            <div class="fee-row total">
                                <span>Vous recevrez:</span>
                                <span class="fee-amount" id="netAmount" style="color: var(--profit-color);">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn" <?= ($stats['pending_withdrawals'] ?? false) ? 'disabled' : '' ?>>
                    <i class="fas fa-paper-plane"></i> Soumettre la demande de retrait
                </button>
            </form>
        </div>

        <!-- Historique des retraits -->
        <div class="card fade-in">
            <h2><i class="fas fa-history"></i> Historique des Retraits</h2>
            
            <?php if (empty($withdrawals)): ?>
                <div style="text-align: center; padding: 40px 20px; color: var(--text-secondary);">
                    <i class="fas fa-inbox fa-3x" style="margin-bottom: 15px;"></i>
                    <h3>Aucun retrait</h3>
                    <p>Vous n'avez effectué aucun retrait pour le moment.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="withdrawals-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Adresse</th>
                                <th>Réseau</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($withdrawals as $withdrawal): ?>
                            <tr>
                                <td><?= htmlspecialchars($withdrawal['formatted_date'] ?? date('d/m/Y H:i', strtotime($withdrawal['created_at'] ?? 'now')), ENT_QUOTES, 'UTF-8') ?></td>
                                <td style="font-weight: 600;">$<?= number_format($withdrawal['amount'] ?? 0, 2) ?></td>
                                <td>
                                    <span style="font-family: 'Roboto Mono', monospace; font-size: 0.9rem;">
                                        <?= htmlspecialchars(substr($withdrawal['address'] ?? '', 0, 10), ENT_QUOTES, 'UTF-8') ?>...<?= htmlspecialchars(substr($withdrawal['address'] ?? '', -10), ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($withdrawal['network'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="status-badge status-<?= htmlspecialchars($withdrawal['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?= $withdrawal['status_text'] ?? ($withdrawal['status'] === 'pending' ? 'En attente' : ($withdrawal['status'] === 'approved' ? 'Approuvé' : 'Rejeté')) ?>
                                    </span>
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
        const amountInput = document.getElementById('amount');
        const maxAmountBtn = document.getElementById('maxAmountBtn');
        const networkOptions = document.querySelectorAll('.network-option');
        const networkInput = document.getElementById('network');
        const displayAmount = document.getElementById('displayAmount');
        const feeAmount = document.getElementById('feeAmount');
        const netAmount = document.getElementById('netAmount');
        const submitBtn = document.getElementById('submitBtn');
        const addressInput = document.getElementById('address');

        const fees = <?= json_encode($fees) ?>;
        const addressPatterns = {
            'TRC20': /^T[a-zA-Z0-9]{33}$/,
            'ERC20': /^0x[a-fA-F0-9]{40}$/,
            'BEP20': /^0x[a-fA-F0-9]{40}$/
        };
        const addressMessages = {
            'TRC20': "Adresse TRC20 invalide. Doit commencer par 'T' et contenir 34 caractères.",
            'ERC20': "Adresse ERC20 invalide. Doit commencer par '0x' et contenir 42 caractères.",
            'BEP20': "Adresse BEP20 invalide. Doit commencer par '0x' et contenir 42 caractères."
        };

        function updateCalculations() {
            const amount = parseFloat(amountInput.value) || 0;
            const network = networkInput.value;
            const fee = fees[network] || fees['TRC20'];
            displayAmount.textContent = '$' + amount.toFixed(2);
            feeAmount.textContent = '$' + fee.toFixed(2);
            netAmount.textContent = '$' + Math.max(0, amount - fee).toFixed(2);
            if (amount - fee <= 0) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Montant insuffisant après frais';
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Soumettre la demande de retrait';
            }
        }

        function updateAddressValidation() {
            const network = networkInput.value;
            addressInput.pattern = addressPatterns[network].source;
            addressInput.title = addressMessages[network];
            switch(network) {
                case 'TRC20': addressInput.placeholder = 'Ex: TWBquVpXHRYAjBpPazFfLh9kX5Zq...'; break;
                case 'ERC20': case 'BEP20': addressInput.placeholder = 'Ex: 0x742d35Cc6634C0532925a3b...'; break;
            }
        }

        function validateForm() {
            const amount = parseFloat(amountInput.value) || 0;
            const network = networkInput.value;
            const address = addressInput.value.trim();
            const fee = fees[network] || fees['TRC20'];
            let isValid = true;
            let errorMessage = '';

            if (!amount || amount < 10) { isValid = false; errorMessage = 'Le montant minimum est de $10'; }
            else if (amount > parseFloat(amountInput.max)) { isValid = false; errorMessage = 'Solde insuffisant'; }
            else if (amount - fee <= 0) { isValid = false; errorMessage = 'Le montant après frais est insuffisant'; }
            if (!address) { isValid = false; if (!errorMessage) errorMessage = 'Veuillez entrer une adresse de retrait'; }
            else if (!addressPatterns[network].test(address)) { isValid = false; if (!errorMessage) errorMessage = addressMessages[network]; }

            submitBtn.disabled = !isValid;
            submitBtn.innerHTML = isValid
                ? '<i class="fas fa-paper-plane"></i> Soumettre la demande de retrait'
                : '<i class="fas fa-exclamation-triangle"></i> ' + (errorMessage || 'Formulaire invalide');
            return isValid;
        }

        maxAmountBtn?.addEventListener('click', function() {
            const maxAmount = parseFloat(amountInput.getAttribute('max')) || 0;
            amountInput.value = maxAmount.toFixed(2);
            updateCalculations();
            validateForm();
        });

        networkOptions.forEach(option => {
            option.addEventListener('click', function() {
                if (this.classList.contains('selected')) return;
                networkOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                networkInput.value = this.dataset.network;
                updateCalculations();
                updateAddressValidation();
                validateForm();
            });
        });

        amountInput?.addEventListener('input', function() { updateCalculations(); validateForm(); });
        amountInput?.addEventListener('blur', function() { if (this.value) this.value = parseFloat(this.value).toFixed(2); updateCalculations(); });
        addressInput?.addEventListener('input', validateForm);
        addressInput?.addEventListener('blur', validateForm);

        updateAddressValidation();
        validateForm();

        const form = document.getElementById('withdrawalForm');
        form?.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                alert('Veuillez corriger les erreurs dans le formulaire.');
                return false;
            }
            const amount = parseFloat(amountInput.value) || 0;
            const network = networkInput.value;
            const fee = fees[network];
            const net = amount - fee;
            if (!confirm(
                "Confirmez-vous ce retrait ?\n\n" +
                "Montant: $" + amount.toFixed(2) + "\n" +
                "Frais: $" + fee.toFixed(2) + "\n" +
                "Vous recevrez: $" + net.toFixed(2) + "\n" +
                "Réseau: " + network + "\n\n" +
                "Le traitement peut prendre jusqu'à 24 heures."
            )) {
                e.preventDefault();
                return false;
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