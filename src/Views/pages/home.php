<?php
$title = 'Accueil | HYIP Invest';
ob_start();
?>

<section class="hero">
    <h1>Investissez comme sur Binance</h1>
    <p>Plateforme éducative HYIP – interface pro, sécurité maximale</p>

    <div class="actions">
        <a href="<?= BASE_URL ?>/register" class="btn primary">Créer un compte</a>
        <a href="<?= BASE_URL ?>/login" class="btn outline">Connexion</a>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
