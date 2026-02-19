<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>COMCV Trading</h3>
                <p>Plateforme professionnelle de trading et d'investissement.</p>
            </div>
            <div class="footer-section">
                <h4>Liens utiles</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>/about">À propos</a></li>
                    <li><a href="<?= BASE_URL ?>/support">Support</a></li>
                    <li><a href="<?= BASE_URL ?>/terms">Conditions</a></li>
                    <li><a href="<?= BASE_URL ?>/privacy">Confidentialité</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p><i class="fas fa-envelope"></i> support@comcv.com</p>
                <p><i class="fas fa-globe"></i> www.comcv.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> COMCV Trading. Tous droits réservés.</p>
            <p class="risk-warning"><i class="fas fa-exclamation-triangle"></i> Le trading comporte des risques de perte en capital.</p>
        </div>
    </div>
</footer>

<style>
.site-footer {
    background: rgba(24, 26, 32, 0.95);
    border-top: 1px solid var(--border-color);
    padding: 50px 0 20px;
    margin-top: 50px;
}
.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}
.footer-section h3,
.footer-section h4 {
    color: var(--text-primary);
    margin-bottom: 15px;
}
.footer-section p,
.footer-section ul {
    color: var(--text-secondary);
    line-height: 1.6;
}
.footer-section ul {
    list-style: none;
    padding: 0;
}
.footer-section ul li {
    margin-bottom: 10px;
}
.footer-section ul li a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
}
.footer-section ul li a:hover {
    color: var(--primary-color);
}
.footer-bottom {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
    color: var(--text-secondary);
}
.risk-warning {
    font-size: 0.85rem;
    margin-top: 10px;
    color: var(--loss-color);
}
@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
</style>