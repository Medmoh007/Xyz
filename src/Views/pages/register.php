<?php
$auth_page = true;
$title = 'Créer un compte | COMCV';
ob_start();
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <h1 class="auth-title">Créer un compte</h1>
                <p class="auth-subtitle">Rejoignez la plateforme de trading COMCV</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="auth-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="name" placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@exemple.com" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="password_confirm" placeholder="••••••••" required>
                </div>

                <button class="btn-primary" type="submit">
                    Créer le compte
                </button>
            </form>

            <div class="auth-footer">
                Déjà un compte ?
                <a href="<?= BASE_URL ?>/login">Se connecter</a>
            </div>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
