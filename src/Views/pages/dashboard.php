<?php
// src/Views/pages/dashboard.php

// Variables passées par le contrôleur
$user = $user ?? [];
$wallet = $wallet ?? [];
$investments = $investments ?? [];
$deposits = $deposits ?? [];
$approvedDeposits = $approvedDeposits ?? array_filter($deposits, fn($d) => ($d['status'] ?? '') === 'approved');
$activeInvestments = $activeInvestments ?? array_filter($investments, fn($inv) => ($inv['status'] ?? '') === 'active');
$chartData = $chartData ?? [];
$recentActivity = $recentActivity ?? [];
$notifications = $notifications ?? [];

$totalInvested = $totalInvested ?? array_sum(array_column($activeInvestments, 'amount'));
$totalProfits  = $totalProfits  ?? array_sum(array_column($investments, 'total_profit'));
$totalDeposits = $totalDeposits ?? array_sum(array_column($approvedDeposits, 'amount'));
$roi = $roi ?? ($totalDeposits > 0 ? ($totalProfits / $totalDeposits) * 100 : 0);

$activeInvestmentsCount = is_array($activeInvestments) ? count($activeInvestments) : 0;
$hasLowBalance = ($user['balance'] ?? 0) < 50;
$hasActiveInvestments = $activeInvestmentsCount > 0;
$hasRecentProfits = $totalProfits > 0;

ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | COMCV Trading</title>
    
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ===== STYLES IDENTIQUES À LA VERSION ORIGINALE ===== */
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
            --transition-speed: 0.3s;
        }

        body {
            background-color: var(--background-darker);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .dashboard-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-nav {
            background: var(--background-dark);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .nav-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--background-dark);
            font-size: 1.2rem;
            transition: transform var(--transition-speed);
        }

        .logo-icon:hover {
            transform: rotate(-10deg);
        }

        .logo-text {
            font-size: 1.3rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary-color), #ff9900);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-speed);
            padding: 0.5rem 0;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: var(--text-primary);
        }

        .nav-link.active {
            color: var(--primary-color);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -0.25rem;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .dashboard-main {
            flex: 1;
            padding: 2rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr 280px;
            gap: 1.5rem;
        }

        .balance-card {
            background: linear-gradient(145deg, var(--card-bg), #1a1c2b);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--profit-color));
        }

        .balance-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .balance-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--primary-color), var(--profit-color));
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .balance-info {
            flex: 1;
        }

        .balance-label {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }

        .balance-change {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .balance-change.positive {
            color: var(--profit-color);
        }

        .balance-change.negative {
            color: var(--loss-color);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            transition: transform var(--transition-speed);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.05);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .stat-value.profit {
            color: var(--profit-color);
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .trading-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .symbol-select {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: var(--background-dark);
            color: var(--text-primary);
            font-weight: 500;
            min-width: 120px;
        }

        .timeframe-tabs {
            display: flex;
            background: var(--background-dark);
            padding: 0.25rem;
            border-radius: 0.5rem;
        }

        .timeframe-tab {
            padding: 0.5rem 1rem;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all var(--transition-speed);
        }

        .timeframe-tab:hover {
            color: var(--text-primary);
        }

        .timeframe-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .trade-section {
            margin-top: 1.5rem;
        }

        .trading-chart-section {
            margin-bottom: 1.5rem;
        }

        .trading-chart-section .card-body {
            padding: 0;
        }

        #tradingview_chart {
            height: 400px;
            width: 100%;
            background: var(--background-dark);
            border-radius: 0 0 1rem 1rem;
        }

        .order-book-section .card-body {
            padding: 0;
        }

        .order-book-container {
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .order-book {
            display: grid;
            grid-template-columns: 1fr 1fr;
            flex: 1;
            height: 100%;
        }

        .order-side {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .order-side.asks {
            border-right: 1px solid var(--border-color);
        }

        .order-side-title {
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border-color);
            background: rgba(0, 0, 0, 0.2);
        }

        .order-side.asks .order-side-title {
            color: var(--loss-color);
        }

        .order-side.bids .order-side-title {
            color: var(--profit-color);
        }

        .order-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(0, 0, 0, 0.1);
        }

        .orders-list {
            flex: 1;
            overflow-y: auto;
        }

        .order-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-family: 'Roboto Mono', monospace;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            align-items: center;
            min-height: 36px;
        }

        .order-row:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .order-row.ask {
            background: linear-gradient(90deg, rgba(246, 70, 93, 0.05), transparent);
        }

        .order-row.bid {
            background: linear-gradient(90deg, rgba(14, 203, 129, 0.05), transparent);
        }

        .order-price {
            font-weight: 600;
        }

        .order-row.ask .order-price {
            color: var(--loss-color);
        }

        .order-row.bid .order-price {
            color: var(--profit-color);
        }

        .order-amount {
            text-align: center;
            color: var(--text-primary);
        }

        .order-total {
            text-align: right;
            color: var(--text-primary);
            font-weight: 500;
        }

        .spread-info {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-color);
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }

        .spread-label {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .spread-value {
            color: var(--text-primary);
            font-weight: 700;
            font-family: 'Roboto Mono', monospace;
        }

        .trade-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-input {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: var(--background-dark);
            color: var(--text-primary);
            font-size: 1rem;
        }

        .trade-side-selector {
            display: flex;
            gap: 0.5rem;
        }

        .trade-side-btn {
            flex: 1;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .trade-side-btn:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .trade-side-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .trade-summary {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .summary-row.total {
            font-weight: 600;
            color: var(--text-primary);
            border-top: 1px solid var(--border-color);
            padding-top: 0.75rem;
            margin-top: 0.25rem;
            font-size: 1rem;
        }

        .btn-trade {
            width: 100%;
            padding: 0.875rem;
            border-radius: 0.5rem;
            border: none;
            background: var(--primary-color);
            color: var(--background-dark);
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-trade:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(240, 185, 11, 0.2);
        }

        .btn-trade:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .investment-item {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
            border-left: 3px solid var(--primary-color);
        }

        .investment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .investment-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .investment-status {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .investment-status.active {
            background: rgba(14, 203, 129, 0.2);
            color: var(--profit-color);
        }

        .investment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            font-size: 0.85rem;
        }

        .investment-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .investment-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        .investment-value.profit {
            color: var(--profit-color);
        }

        .notification-item {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            border-left: 3px solid;
            background: rgba(255, 255, 255, 0.03);
        }

        .notification-item.success {
            border-left-color: var(--profit-color);
            background: rgba(14, 203, 129, 0.1);
        }

        .notification-item.warning {
            border-left-color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
        }

        .notification-item.info {
            border-left-color: var(--primary-color);
            background: rgba(240, 185, 11, 0.1);
        }

        .notification-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .notification-message {
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .activity-icon.deposit {
            background: rgba(23, 144, 255, 0.2);
            color: #1790ff;
        }

        .activity-icon.profit {
            background: rgba(14, 203, 129, 0.2);
            color: var(--profit-color);
        }

        .activity-icon.investment {
            background: rgba(240, 185, 11, 0.2);
            color: var(--primary-color);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.125rem;
            font-size: 0.9rem;
        }

        .activity-details {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .sidebar-column {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }

            .trading-chart-section,
            .order-book-section {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 768px) {
            .dashboard-main {
                padding: 1rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .trading-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .sidebar-column {
                grid-template-columns: 1fr;
            }
            
            .order-book {
                grid-template-columns: 1fr;
                height: 800px;
            }
            
            .order-side.asks {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
        }

        .scrollbar {
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
        }

        .scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 2px;
        }

        .scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <nav class="main-nav">
            <div class="nav-content">
                <a href="<?= BASE_URL ?>/home" class="logo">
                    <div class="logo-icon">C</div>
                    <div class="logo-text">COMCV Trading</div>
                </a>
                
                <div class="nav-links">
                    <a href="<?= BASE_URL ?>/dashboard" class="nav-link active">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>/investments" class="nav-link">
                        <i class="fas fa-chart-pie"></i> Investments
                    </a>
                    <a href="<?= BASE_URL ?>/wallet" class="nav-link">
                        <i class="fas fa-wallet"></i> Wallet
                    </a>
                    <a href="<?= BASE_URL ?>/trade" class="nav-link">
                        <i class="fas fa-exchange-alt"></i> Trade
                    </a>
                    <a href="<?= BASE_URL ?>/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <main class="dashboard-main">
            <div class="balance-card fade-in">
                <div class="balance-header">
                    <div class="balance-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="balance-info">
                        <div class="balance-label">Total Balance</div>
                        <div class="balance-amount">$<?= number_format($user['balance'] ?? 0, 2) ?></div>
                        <div class="balance-change <?= $totalProfits >= 0 ? 'positive' : 'negative' ?>">
                            <i class="fas fa-arrow-<?= $totalProfits >= 0 ? 'up' : 'down' ?>"></i>
                            $<?= number_format($totalProfits, 2) ?> (<?= number_format($roi, 2) ?>%)
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="sidebar-column">
                    <div class="card fade-in">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line"></i> Active Investments
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if ($hasActiveInvestments): ?>
                                <?php foreach (array_slice($activeInvestments, 0, 3) as $investment): ?>
                                <div class="investment-item">
                                    <div class="investment-header">
                                        <span class="investment-name"><?= htmlspecialchars($investment['plan_name'] ?? 'Investment', ENT_QUOTES, 'UTF-8') ?></span>
                                        <span class="investment-status active">ACTIVE</span>
                                    </div>
                                    <div class="investment-details">
                                        <div>
                                            <div class="investment-label">Amount</div>
                                            <div class="investment-value">$<?= number_format($investment['amount'], 2) ?></div>
                                        </div>
                                        <div>
                                            <div class="investment-label">Profit</div>
                                            <div class="investment-value profit">+$<?= number_format($investment['total_profit'] ?? 0, 2) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if ($activeInvestmentsCount > 3): ?>
                                <a href="<?= BASE_URL ?>/investments" class="btn-trade mt-2">
                                    View All (<?= $activeInvestmentsCount ?>)
                                </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No active investments</p>
                                    <a href="<?= BASE_URL ?>/investments" class="btn-trade mt-2">Start Investing</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card fade-in">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar"></i> Statistics
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-label">Total Invested</div>
                                    <div class="stat-value">$<?= number_format($totalInvested, 2) ?></div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">Total Profits</div>
                                    <div class="stat-value profit">+$<?= number_format($totalProfits, 2) ?></div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">Active Plans</div>
                                    <div class="stat-value"><?= $activeInvestmentsCount ?></div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">ROI</div>
                                    <div class="stat-value"><?= number_format($roi, 2) ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card fade-in">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bell"></i> Notifications
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="notifications-list">
                                <?php if ($hasRecentProfits): ?>
                                <div class="notification-item success">
                                    <div class="notification-title">Profits Generated!</div>
                                    <div class="notification-message">
                                        Your investments earned $<?= number_format($totalProfits, 2) ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($hasLowBalance): ?>
                                <div class="notification-item warning">
                                    <div class="notification-title">Low Balance</div>
                                    <div class="notification-message">
                                        Deposit at least $50 to start investing
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($hasActiveInvestments): ?>
                                <div class="notification-item info">
                                    <div class="notification-title">Active Investments</div>
                                    <div class="notification-message">
                                        You have <?= $activeInvestmentsCount ?> active investment plans
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!$hasActiveInvestments && !$hasLowBalance && !$hasRecentProfits): ?>
                                <div class="notification-item info">
                                    <div class="notification-title">Welcome!</div>
                                    <div class="notification-message">
                                        Start by making a deposit and creating your first investment
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-content">
                    <div class="trading-chart-section">
                        <div class="card fade-in">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-line"></i> Trading Chart
                                </h3>
                                <div class="trading-controls">
                                    <select class="symbol-select" id="tradingSymbol">
                                        <option value="BTCUSDT">BTC/USDT</option>
                                        <option value="ETHUSDT">ETH/USDT</option>
                                        <option value="BNBUSDT">BNB/USDT</option>
                                        <option value="XRPUSDT">XRP/USDT</option>
                                    </select>
                                    <div class="timeframe-tabs">
                                        <button class="timeframe-tab active" data-interval="1">1m</button>
                                        <button class="timeframe-tab" data-interval="5">5m</button>
                                        <button class="timeframe-tab" data-interval="15">15m</button>
                                        <button class="timeframe-tab" data-interval="60">1h</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="tradingview_chart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="order-book-section">
                        <div class="card fade-in">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-book"></i> Order Book
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="order-book-container">
                                    <div class="order-book">
                                        <div class="order-side asks">
                                            <div class="order-side-title">ASKS (SELL)</div>
                                            <div class="order-header">
                                                <span>Price</span>
                                                <span>Amount</span>
                                                <span>Total</span>
                                            </div>
                                            <div class="orders-list scrollbar" id="asksList"></div>
                                        </div>
                                        <div class="order-side bids">
                                            <div class="order-side-title">BIDS (BUY)</div>
                                            <div class="order-header">
                                                <span>Price</span>
                                                <span>Amount</span>
                                                <span>Total</span>
                                            </div>
                                            <div class="orders-list scrollbar" id="bidsList"></div>
                                        </div>
                                    </div>
                                    <div class="spread-info">
                                        <span class="spread-label">Spread:</span>
                                        <span class="spread-value" id="spreadValue">$0.00 (0.00%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($chartData['labels'] ?? null) && !empty($chartData['values'] ?? null)): ?>
                    <div class="card fade-in">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-area"></i> Portfolio Performance
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="portfolioChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="sidebar-column">
                    <div class="card fade-in">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exchange-alt"></i> Quick Trade
                            </h3>
                        </div>
                        <div class="card-body">
                            <form id="tradeForm" class="trade-form" method="POST" action="<?= BASE_URL ?>/trade/execute">
                                <!-- Token CSRF -->
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                
                                <div class="form-group">
                                    <label class="form-label">Symbol</label>
                                    <select class="form-input" id="tradeSymbol" name="pair">
                                        <option value="BTCUSDT">BTC/USDT</option>
                                        <option value="ETHUSDT">ETH/USDT</option>
                                        <option value="BNBUSDT">BNB/USDT</option>
                                    </select>
                                </div>
                                
                                <!-- Sélecteur d'ordre (Market / Limit) -->
                                <div class="form-group">
                                    <label class="form-label">Order Type</label>
                                    <div class="trade-side-selector">
                                        <button type="button" class="trade-side-btn active" id="orderTypeMarket">
                                            <i class="fas fa-bolt"></i> Market
                                        </button>
                                        <button type="button" class="trade-side-btn" id="orderTypeLimit">
                                            <i class="fas fa-tag"></i> Limit
                                        </button>
                                    </div>
                                    <input type="hidden" id="orderTypeInput" name="order_type" value="market">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" id="amountLabel">Amount (USDT)</label>
                                    <input type="number" 
                                           class="form-input" 
                                           id="tradeAmount" 
                                           name="amount"
                                           step="0.01" 
                                           min="10" 
                                           max="<?= htmlspecialchars($user['balance'] ?? 0, ENT_QUOTES, 'UTF-8') ?>" 
                                           placeholder="0.00"
                                           value="100">
                                </div>

                                <!-- Champ de prix : modifiable uniquement en mode Limit -->
                                <div class="form-group" id="priceGroup" style="display: none;">
                                    <label class="form-label">Limit Price (USDT)</label>
                                    <input type="number" 
                                           class="form-input" 
                                           id="tradePriceInput" 
                                           name="limit_price"
                                           step="0.01" 
                                           min="0.01" 
                                           placeholder="0.00">
                                </div>
                                
                                <!-- Prix du marché (lecture seule, affiché même en mode Limit) -->
                                <div class="form-group" id="marketPriceGroup">
                                    <label class="form-label">Market Price</label>
                                    <input type="text" 
                                           class="form-input" 
                                           id="tradePriceDisplay" 
                                           readonly
                                           placeholder="Loading...">
                                    <input type="hidden" id="tradePrice" name="price" value="0">
                                </div>
                                
                                <div class="trade-summary">
                                    <div class="summary-row">
                                        <span>Amount:</span>
                                        <span id="displayAmount">$0.00</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Fee (0.1%):</span>
                                        <span id="tradeFee">$0.10</span>
                                    </div>
                                    <div class="summary-row total">
                                        <span>Total:</span>
                                        <span id="tradeTotal">$100.10</span>
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        class="btn-trade" 
                                        id="submitTrade"
                                        <?= ($user['balance'] ?? 0) < 10 ? 'disabled' : '' ?>>
                                    <i class="fas fa-bolt"></i> Quick Trade
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card fade-in">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history"></i> Recent Activity
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recentActivity)): ?>
                                <?php foreach (array_slice($recentActivity, 0, 5) as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?= htmlspecialchars($activity['type'] ?? 'investment', ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="fas fa-<?= htmlspecialchars($activity['icon'] ?? 'chart-line', ENT_QUOTES, 'UTF-8') ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><?= htmlspecialchars($activity['title'] ?? 'Activity', ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="activity-details">
                                            <span><?= htmlspecialchars($activity['time'] ?? 'Just now', ENT_QUOTES, 'UTF-8') ?></span>
                                            <span class="<?= ($activity['amount'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                                <?= ($activity['amount'] ?? 0) >= 0 ? '+' : '-' ?>$<?= number_format(abs($activity['amount'] ?? 0), 2) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No recent activity</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    (function() {
        'use strict';

        let currentSymbol = 'BTCUSDT';
        let currentInterval = '1';
        let currentPrice = 45000;
        let tradingViewWidget = null;
        let orderType = 'market'; // 'market' ou 'limit'

        // Initialisation TradingView
        function initTradingView() {
            if (tradingViewWidget !== null) {
                try { tradingViewWidget.remove(); } catch(e) {}
            }
            
            if (typeof TradingView === 'undefined') {
                console.error('TradingView library not loaded');
                return;
            }

            tradingViewWidget = new TradingView.widget({
                container_id: "tradingview_chart",
                width: "100%",
                height: 400,
                symbol: `BINANCE:${currentSymbol}`,
                interval: currentInterval,
                timezone: "Etc/UTC",
                theme: "dark",
                style: "1",
                locale: "en",
                toolbar_bg: "#181a20",
                enable_publishing: false,
                hide_side_toolbar: false,
                allow_symbol_change: true,
                studies: ["MACD@tv-basicstudies", "RSI@tv-basicstudies", "Volume@tv-basicstudies"]
            });
            
            updateMarketData();
        }

        // Met à jour le prix simulé et l'interface
        function updateMarketData() {
            const basePrice = {
                'BTCUSDT': 45000,
                'ETHUSDT': 3000,
                'BNBUSDT': 400,
                'XRPUSDT': 0.85
            }[currentSymbol] || 45000;
            
            const variation = (Math.random() * 4 - 2) / 100;
            currentPrice = basePrice * (1 + variation);
            
            updatePriceDisplay();
            updateOrderBook();
        }

        function updatePriceDisplay() {
            const formattedPrice = currentPrice.toFixed(2);
            const displayField = document.getElementById('tradePriceDisplay');
            if (displayField) displayField.value = formattedPrice;
            
            const priceInput = document.getElementById('tradePriceInput');
            if (priceInput) {
                priceInput.value = formattedPrice;
                priceInput.placeholder = formattedPrice;
            }
            
            const tradePriceHidden = document.getElementById('tradePrice');
            if (tradePriceHidden) tradePriceHidden.value = formattedPrice;
            
            calculateTradeTotal();
        }

        function calculateTradeTotal() {
            const amount = parseFloat(document.getElementById('tradeAmount').value) || 0;
            let price;
            
            if (orderType === 'market') {
                price = currentPrice;
            } else {
                price = parseFloat(document.getElementById('tradePriceInput').value) || currentPrice;
            }
            
            const total = amount * price;
            const fee = total * 0.001;
            
            document.getElementById('displayAmount').textContent = '$' + total.toFixed(2);
            document.getElementById('tradeFee').textContent = '$' + fee.toFixed(2);
            document.getElementById('tradeTotal').textContent = '$' + (total + fee).toFixed(2);
            
            const userBalance = <?= json_encode($user['balance'] ?? 0) ?>;
            const submitButton = document.getElementById('submitTrade');
            
            if ((total + fee) > userBalance) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Insufficient Balance';
            } else {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-bolt"></i> Quick Trade';
            }
        }

        function updateOrderBook() {
            const asksList = document.getElementById('asksList');
            const bidsList = document.getElementById('bidsList');
            const spreadValue = document.getElementById('spreadValue');
            
            if (!asksList || !bidsList) return;
            
            const asks = [];
            const bids = [];
            
            let askPrice = currentPrice * 1.001;
            for (let i = 0; i < 8; i++) {
                const price = askPrice + (i * currentPrice * 0.0002);
                const amount = (Math.random() * 5 + 0.5).toFixed(4);
                const total = (price * amount).toFixed(2);
                asks.push({ price, amount, total });
            }
            
            let bidPrice = currentPrice * 0.999;
            for (let i = 0; i < 8; i++) {
                const price = bidPrice - (i * currentPrice * 0.0002);
                if (price <= 0) break;
                const amount = (Math.random() * 5 + 0.5).toFixed(4);
                const total = (price * amount).toFixed(2);
                bids.push({ price, amount, total });
            }
            
            asks.sort((a, b) => a.price - b.price);
            bids.sort((a, b) => b.price - a.price);
            
            asksList.innerHTML = '';
            asks.forEach(order => {
                const row = document.createElement('div');
                row.className = 'order-row ask';
                row.innerHTML = `
                    <div class="order-price">$${order.price.toFixed(2)}</div>
                    <div class="order-amount">${parseFloat(order.amount).toLocaleString('en-US', {minimumFractionDigits: 4, maximumFractionDigits: 4})}</div>
                    <div class="order-total">$${parseFloat(order.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                `;
                asksList.appendChild(row);
            });
            
            bidsList.innerHTML = '';
            bids.forEach(order => {
                const row = document.createElement('div');
                row.className = 'order-row bid';
                row.innerHTML = `
                    <div class="order-price">$${order.price.toFixed(2)}</div>
                    <div class="order-amount">${parseFloat(order.amount).toLocaleString('en-US', {minimumFractionDigits: 4, maximumFractionDigits: 4})}</div>
                    <div class="order-total">$${parseFloat(order.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                `;
                bidsList.appendChild(row);
            });
            
            if (asks.length > 0 && bids.length > 0) {
                const highestBid = bids[0].price;
                const lowestAsk = asks[0].price;
                const spread = lowestAsk - highestBid;
                const spreadPercent = (spread / highestBid * 100).toFixed(2);
                spreadValue.textContent = `$${spread.toFixed(2)} (${spreadPercent}%)`;
            }
        }

        // --- Gestionnaires d'événements ---
        
        document.getElementById('tradingSymbol')?.addEventListener('change', function() {
            currentSymbol = this.value;
            document.getElementById('tradeSymbol').value = currentSymbol;
            initTradingView();
        });

        document.querySelectorAll('.timeframe-tab').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.timeframe-tab').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentInterval = this.dataset.interval;
                initTradingView();
            });
        });

        document.getElementById('tradeSymbol')?.addEventListener('change', function() {
            currentSymbol = this.value;
            document.getElementById('tradingSymbol').value = currentSymbol;
            initTradingView();
        });

        document.getElementById('tradeAmount')?.addEventListener('input', calculateTradeTotal);
        document.getElementById('tradePriceInput')?.addEventListener('input', calculateTradeTotal);

        const marketBtn = document.getElementById('orderTypeMarket');
        const limitBtn = document.getElementById('orderTypeLimit');
        const priceGroup = document.getElementById('priceGroup');
        const marketPriceGroup = document.getElementById('marketPriceGroup');
        const amountLabel = document.getElementById('amountLabel');
        const orderTypeInput = document.getElementById('orderTypeInput');

        if (marketBtn && limitBtn) {
            marketBtn.addEventListener('click', function() {
                marketBtn.classList.add('active');
                limitBtn.classList.remove('active');
                orderType = 'market';
                orderTypeInput.value = 'market';
                priceGroup.style.display = 'none';
                marketPriceGroup.style.display = 'block';
                amountLabel.textContent = 'Amount (USDT)';
                calculateTradeTotal();
            });

            limitBtn.addEventListener('click', function() {
                limitBtn.classList.add('active');
                marketBtn.classList.remove('active');
                orderType = 'limit';
                orderTypeInput.value = 'limit';
                priceGroup.style.display = 'block';
                marketPriceGroup.style.display = 'block';
                amountLabel.textContent = 'Amount (Base)';
                calculateTradeTotal();
            });
        }

        // Soumission réelle du formulaire - ne pas empêcher la soumission par défaut
        document.getElementById('tradeForm')?.addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('tradeAmount').value);
            const symbol = document.getElementById('tradeSymbol').value;
            
            if (!amount || amount < 10) {
                e.preventDefault();
                alert('Minimum trade amount is $10');
                return;
            }
            
            if (orderType === 'limit') {
                const limitPrice = parseFloat(document.getElementById('tradePriceInput').value);
                if (isNaN(limitPrice) || limitPrice <= 0) {
                    e.preventDefault();
                    alert('Please enter a valid limit price');
                    return;
                }
            }
            
            // Si validation OK, le formulaire est soumis normalement
        });

        <?php if (!empty($chartData['labels'] ?? null) && !empty($chartData['values'] ?? null)): ?>
        const ctx = document.getElementById('portfolioChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($chartData['labels'] ?? []) ?>,
                    datasets: [{
                        label: 'Portfolio Value',
                        data: <?= json_encode($chartData['values'] ?? []) ?>,
                        borderColor: '#f0b90b',
                        backgroundColor: 'rgba(240, 185, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#848e9c' } },
                        y: {
                            position: 'right',
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: '#848e9c', callback: value => '$' + value }
                        }
                    }
                }
            });
        }
        <?php endif; ?>

        initTradingView();
        updateOrderBook();
        calculateTradeTotal();
        
        setInterval(updateOrderBook, 3000);
        setInterval(() => {
            updateMarketData();
        }, 10000);
    })();
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>