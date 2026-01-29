<?php /*
// Variables de contrôle de layout
$auth_page = $auth_page ?? false;
$dashboard_page = $dashboard_page ?? false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'COMCV Trading' ?></title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Global (toujours chargé) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">

    <!-- CSS Auth (uniquement sur login/register) -->
    <?php if ($auth_page): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
    <?php endif; ?>

    <!-- CSS Dashboard (uniquement sur dashboard) -->
    <?php if ($dashboard_page): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <?php endif; ?>
</head>
<body class="<?= $dashboard_page ? 'dashboard-body' : '' ?>">

<?php if ($dashboard_page): ?>
<!-- =====================
     LAYOUT DASHBOARD
===================== -->
<div class="dashboard-layout">
    
    <!-- Sidebar fixe (si tu en veux une globale) -->
    <?php if (file_exists(__DIR__ . '/../partials/sidebar.php')): ?>
        <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-content-wrapper">
        <!-- Navbar globale (optionnelle) -->
        <?php if (file_exists(__DIR__ . '/../partials/nav.php')): ?>
            <?php require __DIR__ . '/../partials/nav.php'; ?>
        <?php endif; ?>

        <!-- Contenu de la page -->
        <main class="main-content">
            <?= $content ?>
        </main>
    </div>

</div>

<?php else: ?>
<!-- =====================
     LAYOUT PUBLIC/AUTH
===================== -->
<?= $content ?>
<?php endif; ?>

<!-- JS Global -->
<script src="<?= BASE_URL ?>/assets/js/interactions.js"></script>

<!-- JS Dashboard -->
<?php if ($dashboard_page): ?>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
<?php endif; ?>

<!-- JS Auth -->
<?php if ($auth_page): ?>
    <script src="<?= BASE_URL ?>/assets/js/auth.js"></script>
<?php endif; ?>

</body>
</html>*/


// Variables de contrôle de layout
$auth_page = $auth_page ?? false;
$dashboard_page = $dashboard_page ?? false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'COMCV Trading' ?></title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">

    <!-- CSS Auth -->
    <?php if ($auth_page): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
    <?php endif; ?>

    <!-- CSS Dashboard -->
    <?php if ($dashboard_page): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <?php endif; ?>
</head>
<body class="<?= $dashboard_page ? 'dashboard-body' : '' ?>">

<?php if ($dashboard_page): ?>
<div class="dashboard-layout">
    <!-- Sidebar -->
    <?php if (file_exists(__DIR__ . '/../partials/sidebar.php')): ?>
        <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <?php endif; ?>

    <div class="main-content-wrapper">
        <!-- Navbar -->
        <?php if (file_exists(__DIR__ . '/../partials/nav.php')): ?>
            <?php require __DIR__ . '/../partials/nav.php'; ?>
        <?php endif; ?>

        <!-- Contenu -->
        <main class="main-content">
            <?= $content ?>
        </main>
    </div>
</div>

<?php else: ?>
    <!-- Layout Auth / Public -->
    <?= $content ?>
<?php endif; ?>

<!-- JS Global -->
<script src="<?= BASE_URL ?>/assets/js/interactions.js"></script>

<?php if ($dashboard_page): ?>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
<?php endif; ?>

<?php if ($auth_page): ?>
    <script src="<?= BASE_URL ?>/assets/js/auth.js"></script>
<?php endif; ?>

</body>
</html>
