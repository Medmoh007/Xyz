<nav class="main-nav">
    <div class="container">
        <div class="nav-content">
            <div class="nav-links">
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item <?= (isset($dashboard_page) && $dashboard_page) ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="<?= BASE_URL ?>/investments" class="nav-item <?= (isset($investments_page) && $investments_page) ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Investments
                </a>
                <a href="<?= BASE_URL ?>/wallet" class="nav-item <?= (isset($wallet_page) && $wallet_page) ? 'active' : '' ?>">
                    <i class="fas fa-wallet"></i> Wallet
                </a>
                <a href="<?= BASE_URL ?>/trade" class="nav-item <?= (isset($trade_page) && $trade_page) ? 'active' : '' ?>">
                    <i class="fas fa-exchange-alt"></i> Trade
                </a>
                <a href="<?= BASE_URL ?>/transactions" class="nav-item">
                    <i class="fas fa-history"></i> Historique
                </a>
                <a href="<?= BASE_URL ?>/support" class="nav-item">
                    <i class="fas fa-question-circle"></i> Support
                </a>
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin" class="nav-item">
                    <i class="fas fa-shield-alt"></i> Admin
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<style>
.main-nav {
    background: rgba(24, 26, 32, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-color);
    position: sticky;
    top: 70px;
    z-index: 999;
}
.nav-content {
    padding: 10px 0;
}
.nav-links {
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
@media (max-width: 768px) {
    .nav-links {
        flex-wrap: wrap;
        gap: 15px;
    }
}
</style>