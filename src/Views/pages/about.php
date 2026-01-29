<?php
$title = 'À propos | HYIP Invest';
ob_start();
?>

<h1>À propos</h1>

<div class="card">
    <p>
        HYIP Invest est une plateforme éducative inspirée
        des interfaces professionnelles de trading.
    </p>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
