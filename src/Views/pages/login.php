<?php
$title = 'Connexion | HYIP Invest';
$extra_css = '<link rel="stylesheet" href="'.config('base_url').'/assets/css/auth.css">';
$extra_js  = '<script src="'.config('base_url').'/assets/js/auth.js" defer></script>';

ob_start();
?>

<div class="auth-container">
  <div class="auth-card">

    <div class="auth-header">
      <img src="<?= config('base_url') ?>/assets/img/logo.svg" alt="HYIP" class="auth-logo">
      <h1>Connexion</h1>
      <p>Accédez à votre compte en toute sécurité</p>
    </div>

    <form method="post" class="auth-form">
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

      <div class="field">
        <label>Email</label>
        <input type="email" name="email" placeholder="email@exemple.com" required>
      </div>

      <div class="field password-field">
        <label>Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <button type="button" id="togglePassword">
          <i class="fas fa-eye"></i>
        </button>
      </div>

      <div class="form-row">
        <label class="remember">
          <input type="checkbox" name="remember">
          Se souvenir de moi
        </label>

        <a href="/forgot-password" class="forgot">Mot de passe oublié ?</a>
      </div>

      <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <button class="btn-primary" type="submit">Se connecter</button>
    </form>

    <div class="auth-footer">
      <span>Pas encore de compte ?</span>
      <a href="<?= config('base_url') ?>/register">Créer un compte</a>
    </div>

  </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>