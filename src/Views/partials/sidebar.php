<aside class="admin-sidebar">
    <div class="admin-logo">
        <div class="logo-icon">C</div>
        <div class="logo-text">COMCV Admin</div>
    </div>
    <ul class="admin-nav">
        <li>
            <a href="<?= BASE_URL ?>/admin" class="admin-nav-item <?= (isset($admin_page) && $admin_page === 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/deposits/pending" class="admin-nav-item <?= (isset($admin_page) && $admin_page === 'deposits') ? 'active' : '' ?>">
                <i class="fas fa-arrow-down"></i> <span>Dépôts en attente</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/withdrawals/pending" class="admin-nav-item">
                <i class="fas fa-arrow-up"></i> <span>Retraits en attente</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/users" class="admin-nav-item">
                <i class="fas fa-users"></i> <span>Utilisateurs</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/plans" class="admin-nav-item">
                <i class="fas fa-chart-line"></i> <span>Plans</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/logs" class="admin-nav-item">
                <i class="fas fa-file-alt"></i> <span>Logs</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/dashboard" class="admin-nav-item">
                <i class="fas fa-arrow-left"></i> <span>Retour au site</span>
            </a>
        </li>
    </ul>
</aside>

<style>
.admin-sidebar {
    width: 280px;
    background: var(--background-dark);
    border-right: 1px solid var(--border-color);
    padding: 30px 0;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    overflow-y: auto;
    z-index: 1000;
}
.admin-logo {
    padding: 0 20px 20px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.admin-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}
.admin-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}
.admin-nav-item:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
}
.admin-nav-item.active {
    background: rgba(240, 185, 11, 0.1);
    color: var(--primary-color);
    border-left-color: var(--primary-color);
}
.admin-nav-item i { width: 20px; text-align: center; }
@media (max-width: 992px) {
    .admin-sidebar { width: 80px; }
    .admin-sidebar .logo-text,
    .admin-sidebar .admin-nav-item span { display: none; }
    .admin-nav-item { justify-content: center; }
}
</style>