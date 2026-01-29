<?php
$title = 'Retraits | HYIP Invest';
ob_start();
?>

<h1>Retraits</h1>

<div class="card">
    <p>Historique et demande de retrait</p>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
