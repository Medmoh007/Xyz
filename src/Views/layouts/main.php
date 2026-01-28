<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? config('site_name') ?></title>

    <link rel="stylesheet" href="<?= config('base_url') ?>/assets/css/main.css">
    <?php if (!empty($extra_css)) echo $extra_css; ?>
</head>
<body>

<?= $content ?>

<script src="<?= config('base_url') ?>/assets/js/main.js"></script>
<?php if (!empty($extra_js)) echo $extra_js; ?>
</body>
</html>
