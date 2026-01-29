<?php
$dashboard_page = true;
$title = 'Dashboard | COMCV Trading';
ob_start();
?>

<div class="dashboard">
    <!-- NAVBAR SUPÉRIEURE -->
    <nav class="top-nav">
        <div class="nav-left">
            <div class="logo">
                <span>C</span>
                <span>COMCV</span>
            </div>
            
            <div class="nav-links">
                <a href="<?= BASE_URL ?>/dashboard" class="nav-link active">Dashboard</a>
                <a href="<?= BASE_URL ?>/trade" class="nav-link">Trade</a>
                <a href="<?= BASE_URL ?>/wallet" class="nav-link">Wallet</a>
                <a href="<?= BASE_URL ?>/orders" class="nav-link">Orders</a>
                <a href="<?= BASE_URL ?>/history" class="nav-link">History</a>
            </div>
        </div>
        
        <div class="nav-right">
            <div class="balance-card">
                <div class="balance-label">Total Balance</div>
                <div class="balance-value">$ 42,689.50</div>
                <div class="balance-change positive">+2.4%</div>
            </div>
        </div>
    </nav>

    <!-- GRID PRINCIPALE -->
    <div class="dashboard-grid">
        <!-- SIDEBAR GAUCHE - MARKETS -->
        <div class="sidebar">
            <div class="market-list">
                <div class="sidebar-title">Markets</div>
                
                <div class="market-filters">
                    <button class="filter-btn active">FAV</button>
                    <button class="filter-btn">USDT</button>
                    <button class="filter-btn">BTC</button>
                </div>
                
                <div class="market-search">
                    <input type="text" placeholder="Search pair..." id="market-search">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="market-pairs">
                    <?php
                    $pairs = [
                        ['symbol' => 'BTC/USDT', 'price' => '43,110.33', 'change' => '+0.12%', 'trend' => 'up', 'volume' => '24.5B'],
                        ['symbol' => 'ETH/USDT', 'price' => '2,907.35', 'change' => '+0.04%', 'trend' => 'up', 'volume' => '12.3B'],
                        ['symbol' => 'SOL/USDT', 'price' => '121.99', 'change' => '-0.66%', 'trend' => 'down', 'volume' => '2.1B'],
                        ['symbol' => 'BNB/USDT', 'price' => '244.98', 'change' => '-0.74%', 'trend' => 'down', 'volume' => '1.8B'],
                        ['symbol' => 'XRP/USDT', 'price' => '0.57', 'change' => '+0.26%', 'trend' => 'up', 'volume' => '800M'],
                    ];
                    
                    foreach ($pairs as $pair):
                    ?>
                    <div class="market-pair">
                        <div class="pair-info">
                            <div class="pair-symbol"><?= $pair['symbol'] ?></div>
                            <div class="pair-volume">Vol: <?= $pair['volume'] ?></div>
                        </div>
                        <div>
                            <div class="pair-price">$ <?= $pair['price'] ?></div>
                            <div class="pair-change <?= $pair['trend'] ?>"><?= $pair['change'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ZONE CHART PRINCIPALE -->
        <main class="main-chart">
            <div class="chart-header">
                <div class="chart-title">
                    <h2>BTC/USDT</h2>
                    <div class="price-display">
                        <div class="chart-price">$ 42,689.50</div>
                        <div class="chart-change positive">+2.4%</div>
                        <div class="chart-highlow">H: 43,200.00 L: 42,100.00</div>
                    </div>
                </div>
                
                <div class="chart-intervals">
                    <button class="interval-btn">1m</button>
                    <button class="interval-btn">5m</button>
                    <button class="interval-btn active">1h</button>
                    <button class="interval-btn">4h</button>
                    <button class="interval-btn">1d</button>
                    <button class="interval-btn">1w</button>
                </div>
            </div>
            
            <!-- GRAPHIQUE AVEC BARRES VISIBLES IMMÉDIATEMENT -->
            <div class="chart-container">
                <div class="chart-placeholder" id="chart-container">
                    <?php
                    // Barres par défaut visibles immédiatement
                    $heights = [65, 45, 85, 30, 70, 55, 90, 35, 60, 40, 75, 50, 80, 45, 65, 55, 70, 60, 85, 40];
                    $trends = ['up', 'down', 'up', 'down', 'up', 'up', 'down', 'up', 'down', 'up', 'down', 'up', 'down', 'up', 'down', 'up', 'down', 'up', 'down', 'up'];
                    
                    for($i = 0; $i < 20; $i++):
                        $price = 42689 + rand(-500, 500);
                    ?>
                    <div class="chart-bar <?= $trends[$i] ?>" 
                         style="height: <?= $heights[$i] ?>%"
                         data-price="$ <?= number_format($price, 2) ?>"
                         data-change="<?= $trends[$i] === 'up' ? '+' : '-' ?>">
                    </div>
                    <?php endfor; ?>
                </div>
                
                <!-- LÉGENDE DU GRAPHIQUE -->
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color up"></div>
                        <span>Hausse</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color down"></div>
                        <span>Baisse</span>
                    </div>
                </div>
            </div>
            
            <!-- FORMULAIRE DE TRADING -->
            <div class="order-form">
                <div class="order-tabs">
                    <button class="order-tab active" data-side="buy">Buy</button>
                    <button class="order-tab" data-side="sell">Sell</button>
                </div>
                
                <form class="trade-form" id="trade-form">
                    <div class="form-group">
                        <label for="amount-input">Amount (BTC)</label>
                        <input type="number" id="amount-input" placeholder="0.00" step="0.001" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="price-input">Price (USDT)</label>
                        <div style="display: flex; gap: 4px; margin-bottom: 8px;">
                            <button type="button" class="price-option" data-multiplier="0.99">-1%</button>
                            <button type="button" class="price-option" data-multiplier="1.00">Market</button>
                            <button type="button" class="price-option" data-multiplier="1.01">+1%</button>
                        </div>
                        <input type="number" id="price-input" value="42689.50" step="0.01" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="total-input">Total (USDT)</label>
                        <input type="number" id="total-input" value="0.00" readonly>
                    </div>
                    
                    <button type="submit" class="btn-trade" id="submit-trade">Buy BTC</button>
                </form>
            </div>
        </main>

        <!-- SIDEBAR DROITE -->
        <div class="right-sidebar">
            <!-- ORDER BOOK -->
            <div class="order-book">
                <div class="sidebar-title">Order Book</div>
                
                <div class="book-header">
                    <span>Price (USDT)</span>
                    <span>Amount (BTC)</span>
                    <span>Total</span>
                </div>
                
                <div class="book-asks">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <div class="book-row ask">
                        <span class="price">42,6<?= 90 - $i ?>.00</span>
                        <span>0.1<?= $i ?></span>
                        <span><?= 4269 - ($i * 10) ?></span>
                    </div>
                    <?php endfor; ?>
                </div>
                
                <div class="book-spread">
                    <span class="spread-price">$ 42,689.50</span>
                    <span class="spread-text">Spread: 0.12%</span>
                </div>
                
                <div class="book-bids">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <div class="book-row bid">
                        <span class="price">42,6<?= 85 - $i ?>.00</span>
                        <span>0.0<?= 9 - $i ?></span>
                        <span><?= 4268 - ($i * 10) ?></span>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- RECENT TRADES -->
            <div class="recent-trades">
                <div class="sidebar-title">Recent Trades</div>
                
                <div class="trades-header">
                    <span>Price</span>
                    <span>Amount</span>
                    <span>Time</span>
                </div>
                
                <div class="trades-list">
                    <?php
                    $trades = [
                        ['price' => '42,690.50', 'amount' => '0.125', 'side' => 'buy', 'time' => '12:45:23'],
                        ['price' => '42,689.00', 'amount' => '0.250', 'side' => 'sell', 'time' => '12:45:21'],
                        ['price' => '42,691.00', 'amount' => '0.075', 'side' => 'buy', 'time' => '12:45:18'],
                        ['price' => '42,688.50', 'amount' => '0.420', 'side' => 'sell', 'time' => '12:45:15'],
                    ];
                    
                    foreach ($trades as $trade):
                    ?>
                    <div class="trade-row <?= $trade['side'] ?>">
                        <span class="price">$ <?= $trade['price'] ?></span>
                        <span><?= $trade['amount'] ?></span>
                        <span><?= $trade['time'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- WIDGETS DU BAS -->
    <div class="bottom-widgets">
        <!-- OPEN ORDERS -->
        <div class="widget">
            <h3>Open Orders</h3>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Side</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BTC/USDT</td>
                        <td class="side-buy">Buy</td>
                        <td>$ 42,600.00</td>
                        <td>0.05 BTC</td>
                        <td>$ 2,130.00</td>
                    </tr>
                    <tr>
                        <td>ETH/USDT</td>
                        <td class="side-sell">Sell</td>
                        <td>$ 2,900.00</td>
                        <td>2.0 ETH</td>
                        <td>$ 5,800.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- PORTFOLIO -->
        <div class="widget">
            <h3>Portfolio</h3>
            
            <div class="portfolio-stats">
                <div class="stat-card">
                    <div class="stat-label">Total Value</div>
                    <div class="stat-value">$ 42,689.50</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">24h Change</div>
                    <div class="stat-value" style="color: #0ecb81;">+ $ 1,024.55</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Available</div>
                    <div class="stat-value">$ 12,450.00</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// DEBUG - Force le chargement du JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard chargé - Mode debug');
    
    // Initialise les tooltips du graphique
    initChartTooltips();
    
    // Affiche les barres chargées
    const bars = document.querySelectorAll('.chart-bar');
    console.log('Barres chargées:', bars.length);
    
    bars.forEach(bar => {
        bar.addEventListener('mouseenter', showChartTooltip);
        bar.addEventListener('mouseleave', hideChartTooltip);
    });
});

// Fonctions de tooltip simplifiées
function showChartTooltip(e) {
    const bar = e.currentTarget;
    const price = bar.getAttribute('data-price');
    const change = bar.getAttribute('data-change');
    const isUp = bar.classList.contains('up');
    
    const tooltip = document.createElement('div');
    tooltip.className = 'chart-tooltip';
    tooltip.innerHTML = `
        <div style="color: ${isUp ? '#0ecb81' : '#f6465d'}; font-weight: 600;">
            ${price}
        </div>
        <div style="font-size: 11px; color: #9fa6b2; margin-top: 2px;">
            ${isUp ? '↗ Hausse' : '↘ Baisse'}
        </div>
    `;
    
    tooltip.style.cssText = `
        position: absolute;
        background: #181a20;
        border: 1px solid ${isUp ? '#0ecb81' : '#f6465d'};
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        pointer-events: none;
        z-index: 1000;
        transform: translate(-50%, -100%);
        margin-top: -10px;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    
    const rect = bar.getBoundingClientRect();
    const containerRect = bar.parentElement.getBoundingClientRect();
    const left = rect.left + rect.width / 2 - containerRect.left;
    
    tooltip.style.left = left + 'px';
    tooltip.style.top = '0';
    
    // Supprimer les anciens tooltips
    document.querySelectorAll('.chart-tooltip').forEach(t => t.remove());
    
    bar.parentElement.appendChild(tooltip);
}

function hideChartTooltip() {
    document.querySelectorAll('.chart-tooltip').forEach(tooltip => tooltip.remove());
}

function initChartTooltips() {
    const bars = document.querySelectorAll('.chart-bar');
    bars.forEach(bar => {
        bar.addEventListener('mouseenter', showChartTooltip);
        bar.addEventListener('mouseleave', hideChartTooltip);
    });
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>