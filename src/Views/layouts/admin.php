<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>Administration</h2>
    <?php require __DIR__ . '/../partials/flash.php'; ?>
    <?= $content ?? '' ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
