<?php
// src/Views/pages/trade.php
// Variables attendues du contrôleur :
// - $user, $trades, $tradeStats, $positionStats, $pairs, $marketPrices, $openPositions
// - $success, $error (messages)

$trades = $trades ?? [];
$tradeStats = $tradeStats ?? [];
$positionStats = $positionStats ?? [];
$user = $user ?? ['balance' => 0, 'username' => 'Trader'];
$pairs = $pairs ?? [];
$marketPrices = $marketPrices ?? [];
$openPositions = $openPositions ?? [];
$success = $success ?? $_SESSION['success'] ?? null;
$error = $error ?? $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$title = $title ?? 'Trade | COMCV Trading';
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
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    
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

        .trade-container {
            max-width: 1400px;
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

        .form-control {
            background: var(--background-dark);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 12px;
            border-radius: 8px;
        }

        .btn-buy {
            background: var(--profit-color);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-sell {
            background: var(--loss-color);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .type-buy { color: var(--profit-color); font-weight: 600; }
        .type-sell { color: var(--loss-color); font-weight: 600; }

        .position-profit { color: var(--profit-color); }
        .position-loss { color: var(--loss-color); }

        .badge-profit { background: rgba(14, 203, 129, 0.2); color: var(--profit-color); }
        .badge-loss { background: rgba(246, 70, 93, 0.2); color: var(--loss-color); }

        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .alert-success { background: rgba(14,203,129,0.1); border: 1px solid var(--profit-color); color: var(--profit-color); }
        .alert-danger  { background: rgba(246,70,93,0.1); border: 1px solid var(--loss-color); color: var(--loss-color); }
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
                <a href="<?= BASE_URL ?>/investments" class="nav-item">
                    <i class="fas fa-briefcase"></i> Investments
                </a>
                <a href="<?= BASE_URL ?>/wallet" class="nav-item">
                    <i class="fas fa-wallet"></i> Wallet
                </a>
                <a href="<?= BASE_URL ?>/trade" class="nav-item active">
                    <i class="fas fa-exchange-alt"></i> Trade
                </a>
                <a href="<?= BASE_URL ?>/logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="trade-container">
        <!-- Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle me-3"></i>
                <div><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3"></i>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Statistiques -->
            <div class="col-12">
                <div class="card balance-card">
                    <div class="row">
                        <div class="col-md-3 text-center border-end">
                            <h6 class="text-muted">SOLDE DISPONIBLE</h6>
                            <h3 class="mt-2">$<?= number_format($user['balance'] ?? 0, 2) ?></h3>
                        </div>
                        <div class="col-md-3 text-center border-end">
                            <h6 class="text-muted">POSITIONS OUVERTES</h6>
                            <h3 class="mt-2"><?= (int)($positionStats['open_positions'] ?? 0) ?></h3>
                        </div>
                        <div class="col-md-3 text-center border-end">
                            <h6 class="text-muted">TRADES EXÉCUTÉS</h6>
                            <h3 class="mt-2"><?= (int)($tradeStats['total_trades'] ?? 0) ?></h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h6 class="text-muted">VOLUME TOTAL</h6>
                            <h3 class="mt-2">$<?= number_format($tradeStats['total_volume'] ?? 0, 2) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique TradingView -->
            <div class="col-lg-8">
                <div class="card">
                    <div id="tradingview_chart" style="height: 500px;"></div>
                </div>
            </div>

            <!-- Formulaire de Trade -->
            <div class="col-lg-4">
                <div class="card">
                    <h4 class="mb-3"><i class="fas fa-exchange-alt me-2"></i> Passer un ordre</h4>
                    
                    <form id="tradeForm" method="POST" action="<?= BASE_URL ?>/trade/execute">
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                        <div class="mb-3">
                            <label for="pair" class="form-label">Paire de trading</label>
                            <select class="form-control" id="pair" name="pair" required>
                                <?php foreach ($pairs as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" 
                                            data-price="<?= $marketPrices[$value] ?? 0 ?>">
                                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (USDT)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="price" 
                                   name="price"
                                   step="0.0001"
                                   required>
                            <small class="text-muted">Prix actuel: <span id="currentPrice">$0</span></small>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Quantité</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="amount" 
                                   name="amount"
                                   step="0.0001"
                                   min="0.0001"
                                   required>
                            <small class="text-muted">Minimum: 0.0001</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Type d'ordre</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-buy flex-fill py-2" id="btnBuy">
                                    <i class="fas fa-arrow-up me-1"></i> ACHETER
                                </button>
                                <button type="button" class="btn-sell flex-fill py-2" id="btnSell">
                                    <i class="fas fa-arrow-down me-1"></i> VENDRE
                                </button>
                            </div>
                            <input type="hidden" id="side" name="side" value="buy">
                        </div>

                        <!-- Calcul -->
                        <div class="p-3 bg-dark rounded mb-3">
                            <h6 class="mb-2">Calcul</h6>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Quantité:</span>
                                <span id="displayAmount">0.0000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Prix unitaire:</span>
                                <span id="displayPrice">$0.00</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total (USDT):</span>
                                <span id="totalAmount" class="text-warning">$0.00</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2 fw-bold">
                            <i class="fas fa-bolt me-1"></i> EXÉCUTER L'ORDRE
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Positions Ouvertes -->
        <?php if (!empty($openPositions)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <h4 class="mb-3"><i class="fas fa-chart-line me-2"></i> Positions Ouvertes</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>PAIRE</th>
                                    <th>ENTRÉE</th>
                                    <th>PRIX ACTUEL</th>
                                    <th>QUANTITÉ</th>
                                    <th>VALEUR</th>
                                    <th>PNL FLOTTANT</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($openPositions as $pos): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($pos['pair'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td>$<?= number_format($pos['entry_price'] ?? 0, 2) ?></td>
                                    <td>$<?= number_format($pos['current_price'] ?? 0, 2) ?></td>
                                    <td><?= number_format($pos['amount'] ?? 0, 4) ?></td>
                                    <td>$<?= number_format(($pos['current_price'] ?? 0) * ($pos['amount'] ?? 0), 2) ?></td>
                                    <td>
                                        <span class="<?= ($pos['floating_pnl'] ?? 0) >= 0 ? 'position-profit' : 'position-loss' ?>">
                                            $<?= number_format($pos['floating_pnl'] ?? 0, 2) ?>
                                            <small>(<?= number_format($pos['pnl_percent'] ?? 0, 2) ?>%)</small>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger close-position" 
                                                data-pair="<?= htmlspecialchars($pos['pair'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-amount="<?= $pos['amount'] ?? 0 ?>">
                                            <i class="fas fa-times"></i> Fermer
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Historique des Trades -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <h4 class="mb-3"><i class="fas fa-history me-2"></i> Historique des Trades</h4>
                    
                    <?php if (empty($trades)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun trade effectué</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>PAIRE</th>
                                        <th>TYPE</th>
                                        <th>QUANTITÉ</th>
                                        <th>PRIX</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trades as $trade): ?>
                                    <tr>
                                        <td><?= date('d/m H:i', strtotime($trade['created_at'] ?? 'now')) ?></td>
                                        <td><?= htmlspecialchars($trade['symbol'] ?? $trade['pair'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <span class="type-<?= htmlspecialchars($trade['side'] ?? $trade['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                <?= strtoupper(htmlspecialchars($trade['side'] ?? $trade['type'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($trade['amount'] ?? 0, 4) ?></td>
                                        <td>$<?= number_format($trade['price'] ?? 0, 2) ?></td>
                                        <td>$<?= number_format(($trade['amount'] ?? 0) * ($trade['price'] ?? 0), 2) ?></td>
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
            // TradingView Widget
            if (typeof TradingView !== 'undefined') {
                new TradingView.widget({
                    "container_id": "tradingview_chart",
                    "width": "100%",
                    "height": 500,
                    "symbol": "BINANCE:BTCUSDT",
                    "interval": "5",
                    "timezone": "Etc/UTC",
                    "theme": "dark",
                    "style": "1",
                    "locale": "fr",
                    "toolbar_bg": "#181a20",
                    "enable_publishing": false,
                    "withdateranges": true,
                    "hide_side_toolbar": false,
                    "allow_symbol_change": true
                });
            }

            // Éléments DOM
            const pairSelect = document.getElementById('pair');
            const priceInput = document.getElementById('price');
            const amountInput = document.getElementById('amount');
            const btnBuy = document.getElementById('btnBuy');
            const btnSell = document.getElementById('btnSell');
            const sideInput = document.getElementById('side');
            const currentPriceSpan = document.getElementById('currentPrice');
            const displayAmount = document.getElementById('displayAmount');
            const displayPrice = document.getElementById('displayPrice');
            const totalAmount = document.getElementById('totalAmount');

            // Prix actuels depuis PHP
            const marketPrices = <?= json_encode($marketPrices ?: []) ?>;

            function updateCurrentPrice() {
                const pair = pairSelect.value;
                const price = marketPrices[pair] || 0;
                currentPriceSpan.textContent = '$' + price.toFixed(2);
                priceInput.value = price.toFixed(2);
                updateCalculations();
            }

            function updateCalculations() {
                const amount = parseFloat(amountInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = amount * price;

                displayAmount.textContent = amount.toFixed(4);
                displayPrice.textContent = '$' + price.toFixed(2);
                totalAmount.textContent = '$' + total.toFixed(2);

                // Indicateur de solde (achat)
                if (sideInput.value === 'buy') {
                    const userBalance = <?= json_encode($user['balance'] ?? 0) ?>;
                    if (total > userBalance) {
                        amountInput.classList.add('border-danger');
                    } else {
                        amountInput.classList.remove('border-danger');
                    }
                } else {
                    amountInput.classList.remove('border-danger');
                }
            }

            // Gestion des boutons Acheter/Vendre
            btnBuy.addEventListener('click', function() {
                btnBuy.classList.add('active');
                btnSell.classList.remove('active');
                sideInput.value = 'buy';
                updateCalculations();
            });

            btnSell.addEventListener('click', function() {
                btnSell.classList.add('active');
                btnBuy.classList.remove('active');
                sideInput.value = 'sell';
                updateCalculations();
            });

            // Fermeture de position
            document.querySelectorAll('.close-position').forEach(btn => {
                btn.addEventListener('click', function() {
                    const pair = this.dataset.pair;
                    const amount = this.dataset.amount;
                    const price = marketPrices[pair] || 0;
                    
                    if (confirm(`Fermer la position ${pair} au prix $${price.toFixed(2)} ?`)) {
                        pairSelect.value = pair;
                        priceInput.value = price.toFixed(2);
                        amountInput.value = amount;
                        sideInput.value = 'sell';
                        btnSell.click();
                        document.getElementById('tradeForm').submit();
                    }
                });
            });

            // Écouteurs
            pairSelect.addEventListener('change', updateCurrentPrice);
            priceInput.addEventListener('input', updateCalculations);
            amountInput.addEventListener('input', updateCalculations);

            // Initialisation
            updateCurrentPrice();
            btnBuy.classList.add('active');
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();
echo $content;
?>