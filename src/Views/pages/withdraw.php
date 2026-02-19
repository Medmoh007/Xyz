<?php
// Variables passées par le contrôleur
$withdrawals = $withdrawals ?? [];
$user = $user ?? ['balance' => 0];
$wallet = $wallet ?? ['address' => ''];
$config = $config ?? [
    'min_amount' => 10,
    'fee_trc20' => 1,
    'fee_erc20' => 10,
    'networks' => []
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait | COMCV Trading</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
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
            background: var(--background-darker);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navigation identique au dashboard */
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
            background-clip: text;
            color: transparent;
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

        /* Contenu principal */
        .withdraw-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .balance-card {
            background: linear-gradient(135deg, var(--card-bg), #1a1c2b);
            border-color: var(--primary-color);
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .form-control {
            background: var(--background-dark);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 12px;
            border-radius: 8px;
        }

        .form-control:focus {
            background: var(--background-dark);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(240, 185, 11, 0.25);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #f5c542;
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
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

        .status-cancelled {
            background: rgba(132, 142, 156, 0.1);
            color: var(--text-secondary);
        }

        .calculation-box {
            background: var(--background-dark);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .calculation-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .calculation-row.total {
            border-bottom: none;
            border-top: 2px solid var(--border-color);
            padding-top: 10px;
            margin-top: 10px;
            font-weight: 600;
        }

        .warning-box {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid rgba(246, 70, 93, 0.3);
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(14, 203, 129, 0.1);
            border: 1px solid var(--profit-color);
            color: var(--profit-color);
        }

        .alert-danger {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid var(--loss-color);
            color: var(--loss-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="/" class="nav-brand">
                <div class="nav-brand-logo">C</div>
                <div class="nav-brand-text">COMCV Trading</div>
            </a>
            
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
    </nav>

    <!-- Messages -->
    <div class="withdraw-container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle me-3"></i>
                <div><?= htmlspecialchars($_SESSION['success']) ?></div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3"></i>
                <div><?= htmlspecialchars($_SESSION['error']) ?></div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row">
            <!-- Formulaire de retrait -->
            <div class="col-lg-8">
                <div class="card balance-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Solde disponible</h4>
                            <div class="balance-amount">$<?= number_format($user['balance'], 2) ?></div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted mb-1">Adresse du wallet</div>
                            <code class="text-primary"><?= htmlspecialchars($wallet['address']) ?></code>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h4 class="mb-4"><i class="fas fa-arrow-up me-2"></i> Effectuer un retrait</h4>
                    
                    <form id="withdrawForm" method="POST" action="/withdraw/store">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Montant (USDT)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="amount" 
                                           name="amount"
                                           step="0.01"
                                           min="<?= $config['min_amount'] ?>"
                                           max="<?= $user['balance'] ?>"
                                           placeholder="100.00"
                                           required>
                                </div>
                                <div class="form-text">Minimum: $<?= $config['min_amount'] ?></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="network" class="form-label">Réseau</label>
                                <select class="form-control" id="network" name="network" required>
                                    <?php foreach ($config['networks'] as $value => $label): ?>
                                        <option value="<?= $value ?>" 
                                                data-fee="<?= $value === 'TRC20' ? $config['fee_trc20'] : $config['fee_erc20'] ?>">
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse de destination</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="address" 
                                   name="address"
                                   placeholder="T..."
                                   pattern="^T[a-zA-Z0-9]{33}$"
                                   title="Adresse TRC20 valide commençant par T"
                                   required>
                            <div class="form-text">Assurez-vous que l'adresse correspond au réseau sélectionné</div>
                        </div>

                        <!-- Calcul des frais -->
                        <div class="calculation-box">
                            <h6 class="mb-3">Calcul du retrait</h6>
                            <div class="calculation-row">
                                <span>Montant du retrait:</span>
                                <span id="displayAmount">$0.00</span>
                            </div>
                            <div class="calculation-row">
                                <span>Frais de réseau:</span>
                                <span id="feeAmount">$<?= $config['fee_trc20'] ?>.00</span>
                            </div>
                            <div class="calculation-row total">
                                <span>Total débité:</span>
                                <span id="totalAmount" class="text-primary">$0.00</span>
                            </div>
                        </div>

                        <div class="warning-box">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Les retraits sont traités manuellement dans un délai de 24 heures. 
                            Vérifiez bien l'adresse avant de soumettre.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i> Soumettre la demande
                        </button>
                    </form>
                </div>
            </div>

            <!-- Historique des retraits -->
            <div class="col-lg-4">
                <div class="card">
                    <h4 class="mb-4"><i class="fas fa-history me-2"></i> Historique des retraits</h4>
                    
                    <?php if (empty($withdrawals)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun retrait effectué</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($withdrawals as $withdrawal): ?>
                                    <tr>
                                        <td>
                                            <small><?= date('d/m', strtotime($withdrawal['created_at'])) ?></small>
                                        </td>
                                        <td>$<?= number_format($withdrawal['amount'], 2) ?></td>
                                        <td>
                                            <span class="status-badge status-<?= $withdrawal['status'] ?>">
                                                <?= strtoupper($withdrawal['status']) ?>
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
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const networkSelect = document.getElementById('network');
            const displayAmount = document.getElementById('displayAmount');
            const feeAmount = document.getElementById('feeAmount');
            const totalAmount = document.getElementById('totalAmount');
            const submitBtn = document.getElementById('submitBtn');

            function updateCalculations() {
                const amount = parseFloat(amountInput.value) || 0;
                const selectedOption = networkSelect.options[networkSelect.selectedIndex];
                const fee = parseFloat(selectedOption.dataset.fee) || 0;
                const total = amount + fee;

                displayAmount.textContent = '$' + amount.toFixed(2);
                feeAmount.textContent = '$' + fee.toFixed(2);
                totalAmount.textContent = '$' + total.toFixed(2);

                // Vérifier si le solde est suffisant
                const userBalance = <?= json_encode($user['balance']) ?>;
                if (total > userBalance) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Solde insuffisant';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Soumettre la demande';
                }
            }

            amountInput.addEventListener('input', updateCalculations);
            networkSelect.addEventListener('change', updateCalculations);
            
            // Initialiser les calculs
            updateCalculations();
        });
    </script>
</body>
</html>