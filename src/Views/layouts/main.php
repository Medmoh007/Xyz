<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'COMCV Trading') ?></title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS – CHEMINS ABSOLUS TEMPORAIRES -->
    <link rel="stylesheet" href="/x/public/assets/css/main.css">
    <link rel="stylesheet" href="/x/public/assets/css/auth.css">
    <link rel="stylesheet" href="/x/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/x/public/assets/css/investments.css">
    <link rel="stylesheet" href="/x/public/assets/css/wallet.css">
    <link rel="stylesheet" href="/x/public/assets/css/trade.css">
    <link rel="stylesheet" href="/x/public/assets/css/support.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- TradingView -->
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <?php include __DIR__ . '/../partials/nav.php'; ?>
    <?php include __DIR__ . '/../partials/flash.php'; ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <!-- JS – CHEMINS ABSOLUS TEMPORAIRES -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/x/public/assets/js/main.js"></script>
    <script src="/x/public/assets/js/auth.js"></script>
    <script src="/x/public/assets/js/dashboard.js"></script>
    <script src="/x/public/assets/js/investments.js"></script>
    <script src="/x/public/assets/js/wallet.js"></script>
    <script src="/x/public/assets/js/trade.js"></script>
    <script src="/x/public/assets/js/support.js"></script>
</body>
</html>