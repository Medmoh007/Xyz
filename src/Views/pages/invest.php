<?php
$title = 'Investir | HYIP Invest';
ob_start();
?>

<h1>Investir</h1>

<div class="card">
    <p>Choisissez un plan d’investissement (UI à connecter plus tard)</p>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
