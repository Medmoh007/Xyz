<?php
$title = 'Créer un compte | HYIP Invest';
$extra_css = '<link rel="stylesheet" href="' . config('base_url') . '/assets/css/auth.css">';
$extra_js  = '<script src="' . config('base_url') . '/assets/js/auth.js" defer></script>';

ob_start();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= config('base_url') ?>/assets/img/logo.svg" alt="HYIP" class="auth-logo">
            <h1>Créer un compte</h1>
            <p>Rejoignez la plateforme d'investissement</p>
        </div>

        <form method="post" class="auth-form">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

            <div class="field">
                <label>Nom complet</label>
                <input type="text" name="name" required placeholder="John Doe">
            </div>

            <div class="field">
                <label>Email</label>
                <input type="email" name="email" required placeholder="email@exemple.com">
            </div>

            <div class="field password-field">
                <label>Mot de passe</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <div class="field password-field">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary">
                Créer mon compte
            </button>

            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </form>

        <div class="auth-footer">
            <span>Déjà inscrit ?</span>
            <a href="<?= config('base_url') ?>/login">Connexion</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>