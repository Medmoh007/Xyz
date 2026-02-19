<header class="site-header">
    <div class="container">
        <div class="header-content">
            <a href="<?= BASE_URL ?>" class="logo">
                <div class="logo-icon">C</div>
                <div class="logo-text">COMCV Trading</div>
            </a>
            <div class="user-info">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="welcome">Bienvenue, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Utilisateur') ?></span>
                    <span class="balance">$<?= number_format($_SESSION['user']['balance'] ?? 0, 2) ?></span>
                    <a href="<?= BASE_URL ?>/logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<style>
.site-header {
    background: var(--background-dark);
    border-bottom: 1px solid var(--border-color);
    padding: 15px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}
.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}
.logo-icon {
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
.logo-text {
    font-size: 1.3rem;
    font-weight: 700;
    background: linear-gradient(90deg, var(--primary-color), #ff9900);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.user-info {
    display: flex;
    align-items: center;
    gap: 20px;
    color: var(--text-secondary);
}
.welcome { font-weight: 500; }
.balance { color: var(--profit-color); font-weight: 600; }
.btn-logout {
    color: var(--text-secondary);
    transition: color 0.3s ease;
}
.btn-logout:hover { color: var(--loss-color); }
</style>