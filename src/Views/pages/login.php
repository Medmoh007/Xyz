<?php
// src/Views/pages/login.php
$auth_page = true;
$title = 'Connexion | COMCV Trading';
ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ============================================
           AUTH PAGE STYLES - CORRIGÉ
           ============================================ */

        :root {
            --primary-color: #f0b90b;
            --profit-color: #0ecb81;
            --loss-color: #f6465d;
            --background-dark: #181a20;
            --background-darker: #0b0e11;
            --border-color: #2b3139;
            --text-primary: #eaecef;
            --text-secondary: #848e9c;
            --card-bg: #1e2026;
        }

        body {
            background-color: var(--background-darker);
            color: var(--text-primary);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

        /* Image de fond - chemin dynamique */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?= BASE_URL ?>/assets/image/img.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            opacity: 0.3;
            filter: brightness(0.8) contrast(1.1);
            z-index: -1;
        }

        /* Overlay sombre */
        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(11, 14, 17, 0.92) 0%,
                rgba(11, 14, 17, 0.85) 100%
            );
            z-index: -2;
        }

        /* AUTH GLOBAL */
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            background: rgba(24, 26, 32, 0.95);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(43, 49, 57, 0.5);
            backdrop-filter: blur(10px);
        }

        /* HEADER */
        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-title {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(90deg, var(--primary-color), #ff9900);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin-top: 4px;
        }

        /* FORM */
        .auth-form .form-group {
            margin-bottom: 20px;
        }

        .auth-form label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .auth-form input {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: rgba(11, 14, 17, 0.8);
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .auth-form input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(240, 185, 11, 0.2);
        }

        .auth-form input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        /* BUTTON */
        .btn-primary {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            color: #000;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(240, 185, 11, 0.4);
            opacity: 0.95;
        }

        /* FOOTER */
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: var(--text-secondary);
            padding-top: 20px;
            border-top: 1px solid rgba(43, 49, 57, 0.3);
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            margin-left: 4px;
        }

        .auth-footer a:hover {
            color: #ff9900;
            text-decoration: underline;
        }

        /* ERROR */
        .auth-error {
            background: rgba(246, 70, 93, 0.1);
            border: 1px solid rgba(246, 70, 93, 0.3);
            color: var(--loss-color);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* SUCCESS */
        .auth-success {
            background: rgba(14, 203, 129, 0.1);
            border: 1px solid rgba(14, 203, 129, 0.3);
            color: var(--profit-color);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        /* LOGO */
        .auth-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .auth-logo .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--background-darker);
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .auth-logo .logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary-color), #ff9900);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }

        /* REMEMBER ME */
        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
        }

        .remember-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #ff9900;
            text-decoration: underline;
        }

        /* RESPONSIVE */
        @media (max-width: 480px) {
            .auth-card {
                padding: 24px;
            }
            
            .auth-title {
                font-size: 24px;
            }
            
            .auth-container {
                padding: 10px;
            }
            
            .remember-me {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        /* ADDITIONAL LINKS */
        .auth-links {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            font-size: 13px;
        }

        .auth-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-logo">
                <div class="logo-icon">C</div>
                <div class="logo-text">COMCV Trading</div>
            </div>

            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title">Connexion</h1>
                    <p class="auth-subtitle">Accédez à votre espace de trading</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="auth-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="auth-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/login" class="auth-form" id="loginForm">
                    <!-- Token CSRF généré par le contrôleur -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="email@exemple.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="remember-me">
                        <div class="remember-checkbox">
                            <input type="checkbox" id="remember" name="remember" value="1">
                            <label for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="<?= BASE_URL ?>/forgot-password" class="forgot-password">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <button class="btn-primary" type="submit">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>

                <div class="auth-links">
                    <a href="<?= BASE_URL ?>/">← Retour à l'accueil</a>
                    <a href="<?= BASE_URL ?>/support">Support</a>
                </div>

                <div class="auth-footer">
                    Pas encore de compte ?
                    <a href="<?= BASE_URL ?>/register">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        // Focus sur le champ email au chargement
        emailInput.focus();
        
        // Validation du formulaire
        form.addEventListener('submit', function(e) {
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            
            if (!email) {
                e.preventDefault();
                showError('Veuillez saisir votre email');
                emailInput.focus();
                return false;
            }
            
            if (!password) {
                e.preventDefault();
                showError('Veuillez saisir votre mot de passe');
                passwordInput.focus();
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showError('Veuillez saisir un email valide');
                emailInput.focus();
                return false;
            }
            
            return true;
        });
        
        function showError(message) {
            let errorDiv = document.querySelector('.auth-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'auth-error';
                const card = document.querySelector('.auth-card');
                const header = document.querySelector('.auth-header');
                card.insertBefore(errorDiv, header.nextSibling);
            }
            errorDiv.textContent = message;
            
            errorDiv.style.animation = 'none';
            setTimeout(() => {
                errorDiv.style.animation = 'shake 0.5s ease-in-out';
            }, 10);
        }
        
        // Compte démo
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('demo') === '1') {
            emailInput.value = 'demo@comcv.com';
            passwordInput.value = 'demo123';
        }
        
        // Se souvenir de l'email
        const savedEmail = localStorage.getItem('rememberedEmail');
        if (savedEmail) {
            emailInput.value = savedEmail;
            document.getElementById('remember').checked = true;
        }
        
        form.addEventListener('submit', function() {
            if (document.getElementById('remember').checked) {
                localStorage.setItem('rememberedEmail', emailInput.value);
            } else {
                localStorage.removeItem('rememberedEmail');
            }
        });
    });
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>