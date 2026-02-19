<?php
// src/Views/pages/wallet.php
// Variables attendues du contrôleur :
// - $wallet : array (balance, address, network)
// - $transactions : array
// - $success : string|null
// - $error : string|null
// - $title : string

$wallet = $wallet ?? ['balance' => 0, 'address' => 'Non disponible', 'network' => 'TRC20'];
$transactions = $transactions ?? [];
$success = $success ?? $_SESSION['success'] ?? null;
$error = $error ?? $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$title = $title ?? 'Mon Wallet | COMCV Trading';
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

        .session-message {
            max-width: 1400px;
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

        .wallet-dashboard {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .wallet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .wallet-header h1 {
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wallet-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--profit-color);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
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

        .wallet-card {
            text-align: center;
        }

        .balance-display {
            margin-bottom: 30px;
        }

        .balance-label {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .balance-amount {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .currency {
            color: var(--primary-color);
        }

        .wallet-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 30px;
        }

        .action-btn {
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .action-btn:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 20px rgba(240, 185, 11, 0.1);
            color: var(--text-primary);
            text-decoration: none;
        }

        .action-btn i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .action-btn.deposit i {
            color: var(--profit-color);
        }

        .action-btn.withdraw i {
            color: var(--loss-color);
        }

        .wallet-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .info-item {
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
        }

        .info-label {
            display: block;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .address-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wallet-address {
            flex: 1;
            font-family: 'Roboto Mono', monospace;
            color: var(--text-primary);
            word-break: break-all;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .copy-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            font-weight: 600;
        }

        .copy-btn:hover {
            background: #f5c542;
            transform: translateY(-2px);
        }

        .copy-btn.copied {
            background: var(--profit-color);
        }

        .network-badge, .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .network-badge {
            background: rgba(240, 185, 11, 0.1);
            color: var(--primary-color);
        }

        .status-badge.active {
            background: rgba(14, 203, 129, 0.1);
            color: var(--profit-color);
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: var(--background-darker);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .qr-container {
            display: inline-block;
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin: 15px 0;
        }

        .qr-note {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .wallet-warnings {
            margin-top: 30px;
        }

        .warning {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid rgba(246, 70, 93, 0.3);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .warning i {
            color: var(--loss-color);
            font-size: 1.2rem;
            margin-top: 3px;
        }

        .warning strong {
            color: var(--text-primary);
            display: block;
            margin-bottom: 5px;
        }

        .warning p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.9rem;
        }

        .modal {
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

        .modal.show {
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .amount-input {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .currency-prefix {
            background: var(--background-darker);
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: 6px 0 0 6px;
            color: var(--text-primary);
        }

        .form-input {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 0 6px 6px 0;
            background: var(--background-darker);
            color: var(--text-primary);
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .amount-info {
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
            font-size: 0.9rem;
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

        .fee-calculation {
            background: var(--background-darker);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .fee-row.total {
            border-top: 1px solid var(--border-color);
            padding-top: 10px;
            font-weight: bold;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel {
            flex: 1;
            padding: 12px;
            border: 1px solid var(--border-color);
            background: none;
            border-radius: 6px;
            color: var(--text-primary);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--border-color);
        }

        .btn-confirm {
            flex: 1;
            padding: 12px;
            border: none;
            background: var(--primary-color);
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: #f5c542;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @media (max-width: 768px) {
            .wallet-actions {
                grid-template-columns: 1fr;
            }
            .wallet-info {
                grid-template-columns: 1fr;
            }
            .address-container {
                flex-direction: column;
                align-items: stretch;
            }
            .copy-btn {
                width: 100%;
                justify-content: center;
            }
            .nav-menu {
                display: none;
            }
            .balance-amount {
                font-size: 2.5rem;
            }
            .modal-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .wallet-dashboard {
                padding: 10px;
            }
            .card {
                padding: 15px;
            }
            .wallet-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
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
                <a href="<?= BASE_URL ?>/wallet" class="nav-item active">
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

    <div class="wallet-dashboard">
        <!-- En-tête -->
        <div class="wallet-header fade-in">
            <h1><i class="fas fa-wallet"></i> Mon Wallet</h1>
            <div class="wallet-status">
                <span class="status-indicator"></span>
                <span>Wallet Actif</span>
            </div>
        </div>

        <!-- Carte solde -->
        <div class="card wallet-card fade-in">
            <div class="wallet-overview">
                <div class="balance-display">
                    <div class="balance-label">Solde Total</div>
                    <div class="balance-amount">
                        <span class="currency">$</span>
                        <span class="amount"><?= number_format($wallet['balance'], 2) ?></span>
                    </div>
                </div>
                
                <div class="wallet-actions">
                    <a href="<?= BASE_URL ?>/deposit" class="action-btn deposit">
                        <i class="fas fa-arrow-down"></i>
                        <span>Deposit</span>
                    </a>
                    <a href="<?= BASE_URL ?>/investments" class="action-btn invest">
                        <i class="fas fa-chart-line"></i>
                        <span>Investir</span>
                    </a>
                    <a href="<?= BASE_URL ?>/withdrawal" class="action-btn withdraw">
                        <i class="fas fa-arrow-up"></i>
                        <span>Withdrawal</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Adresse et QR -->
        <div class="card fade-in">
            <h2><i class="fas fa-qrcode"></i> Adresse de Dépôt</h2>
            
            <div class="wallet-info">
                <div class="info-item">
                    <span class="info-label">Adresse:</span>
                    <div class="address-container">
                        <code class="wallet-address"><?= htmlspecialchars($wallet['address'], ENT_QUOTES, 'UTF-8') ?></code>
                        <button class="copy-btn" id="copyAddressBtn">
                            <i class="fas fa-copy"></i> Copier
                        </button>
                    </div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Réseau:</span>
                    <div class="network-badge">
                        <i class="fas fa-globe"></i>
                        <span><?= htmlspecialchars($wallet['network'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Statut:</span>
                    <div class="status-badge active">
                        <i class="fas fa-check-circle"></i>
                        <span>Actif</span>
                    </div>
                </div>
            </div>
            
            <div class="qr-section">
                <h3><i class="fas fa-qrcode"></i> QR Code</h3>
                <div class="qr-container" id="qrContainer"></div>
                <p class="qr-note">
                    <i class="fas fa-info-circle"></i>
                    Scannez ce QR code pour envoyer des fonds vers votre wallet
                </p>
            </div>
            
            <div class="wallet-warnings">
                <div class="warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>⚠️ Important</strong>
                        <p>Envoyez uniquement des USDT via le réseau TRC20. Les fonds envoyés via un autre réseau seront perdus.</p>
                    </div>
                </div>
                <div class="warning">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>⏱️ Délais de traitement</strong>
                        <p>Les dépôts sont généralement crédités dans les 13 minutes après confirmation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Retrait (lien vers la page dédiée, pas de modal) -->
    <!-- Note: le modal de retrait a été retiré car la gestion des retraits se fait sur la page dédiée /withdrawal -->
    <!-- Si vous souhaitez conserver un modal, il faudrait l'adapter avec CSRF et action correcte, mais mieux vaut rediriger vers la page de retrait. -->

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Copie d'adresse
        const copyBtn = document.getElementById("copyAddressBtn");
        const addressElement = document.querySelector(".wallet-address");
        
        if (copyBtn && addressElement) {
            copyBtn.addEventListener("click", function () {
                const address = addressElement.textContent.trim();
                if (!address || address === "Non disponible") {
                    showNotification("Aucune adresse trouvée", "error");
                    return;
                }
                navigator.clipboard.writeText(address)
                    .then(() => {
                        const originalHTML = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-check"></i> Copié!';
                        this.classList.add("copied");
                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                            this.classList.remove("copied");
                        }, 2000);
                        showNotification("Adresse copiée dans le presse-papier!", "success");
                    })
                    .catch(() => {
                        const textArea = document.createElement("textarea");
                        textArea.value = address;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand("copy");
                        document.body.removeChild(textArea);
                        showNotification("Adresse copiée!", "success");
                    });
            });
        }

        // Génération QR Code
        const addressEl = document.querySelector(".wallet-address");
        if (addressEl) {
            const address = addressEl.textContent.trim();
            if (address && address !== "Non disponible") {
                generateQRCode(address);
            }
        }

        function generateQRCode(address) {
            const qrContainer = document.getElementById("qrContainer");
            if (!qrContainer) return;
            const qrSize = 180;
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${qrSize}x${qrSize}&data=${encodeURIComponent(address)}&format=png`;
            const qrImg = document.createElement("img");
            qrImg.src = qrUrl;
            qrImg.alt = "QR Code Wallet";
            qrImg.style.width = "100%";
            qrImg.style.height = "100%";
            qrImg.style.objectFit = "contain";
            qrContainer.innerHTML = "";
            qrContainer.appendChild(qrImg);
        }

        function showNotification(message, type = "success") {
            const notification = document.createElement("div");
            notification.className = "notification";
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--background-dark);
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
                z-index: 9999;
                animation: slideIn 0.3s ease;
                max-width: 300px;
                border-left: 4px solid ${type === "success" ? "var(--profit-color)" : "var(--loss-color)"};
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            notification.innerHTML = `
                <i class="fas fa-${type === "success" ? "check-circle" : "exclamation-circle"}" 
                   style="color: ${type === "success" ? "var(--profit-color)" : "var(--loss-color)"}; font-size: 1.2rem;"></i>
                <div><small>${message}</small></div>
            `;
            const style = document.createElement("style");
            style.textContent = `
                @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
                @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
            `;
            document.head.appendChild(style);
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.animation = "slideOut 0.3s ease";
                setTimeout(() => { notification.remove(); style.remove(); }, 300);
            }, 5000);
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();
echo $content;
?>