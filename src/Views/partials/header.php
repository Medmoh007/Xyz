<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'HYIP Invest' ?></title>

    <!-- BASE URL ABSOLUE -->
    <link rel="stylesheet" href="/x/public/assets/css/main.css">
    <link rel="stylesheet" href="/x/public/assets/css/animations.css">

    <!-- BOOTSTRAP (OBLIGATOIRE car tu utilises row / col / btn) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME (icônes utilisées) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <?= $extra_css ?? '' ?>
</head>

<body class="dark-theme">
