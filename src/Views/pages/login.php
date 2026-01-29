<?php
$auth_page = true; // active le CSS auth
$title = 'Connexion | HYIP Invest';
ob_start();
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">

            <!-- HEADER -->
            <div class="auth-header">
                <div class="auth-title">Connexion</div>
                <div class="auth-subtitle">Accédez à votre espace d’investissement</div>
            </div>

            <!-- ERREUR -->
            <?php if (!empty($error ?? null)): ?>
                <div class="auth-alert error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- FORMULAIRE LOGIN -->
            <form method="post" class="auth-form">

                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="votre@email.com"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    Se connecter
                </button>
            </form>

            <!-- FOOTER -->
            <div class="auth-footer">
                Pas encore de compte ?
                <a href="<?= BASE_URL ?>/register">Créer un compte</a>
            </div>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
