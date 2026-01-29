<?php
$title = 'Parrainage | HYIP Invest';
ob_start();
?>

<h1>Parrainage</h1>

<div class="card">
    <p>Votre lien de parrainage :</p>
    <strong><?= BASE_URL ?>/register?ref=XXXX</strong>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
