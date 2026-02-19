<?php
// src/Views/pages/home.php
$title = 'Accueil | COMCV Trading';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ============================================
           HOME PAGE STYLES - MÊME DESIGN QUE DASHBOARD
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

        /* Image de fond - chemin CORRIGÉ (plus de double /public) */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?= BASE_URL ?>/assets/image/img1.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            opacity: 0.7;
            filter: brightness(0.8) contrast(1.1);
            z-index: -1;
        }

        /* Overlay sombre */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(11, 14, 17, 0.85) 0%, rgba(11, 14, 17, 0.7) 100%);
            z-index: -2;
        }

        /* ============ NAVIGATION IDENTIQUE AU DASHBOARD ============ */
        .main-nav {
            background: rgba(24, 26, 32, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-brand-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--background-dark);
            font-size: 1.2rem;
        }

        .nav-brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary-color), #ff9900);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-item {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 0;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item:hover {
            color: var(--text-primary);
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        /* Menu hamburger pour mobile */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
        }

        /* ============ SECTION HERO ============ */
        .hero {
            min-height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px 20px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(
                circle at center, 
                transparent 0%, 
                rgba(11, 14, 17, 0.92) 70%,
                rgba(11, 14, 17, 0.98) 100%
            );
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(90deg, var(--primary-color), var(--profit-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-primary);
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
        }

        /* ============ ACTIONS ============ */
        .actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            min-width: 180px;
            backdrop-filter: blur(10px);
        }

        .btn.primary {
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            color: #000;
            box-shadow: 0 6px 20px rgba(240, 185, 11, 0.4);
        }

        .btn.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(240, 185, 11, 0.6);
            color: #000;
            text-decoration: none;
        }

        .btn.outline {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            backdrop-filter: blur(15px);
        }

        .btn.outline:hover {
            background: var(--primary-color);
            color: #000;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(240, 185, 11, 0.4);
            text-decoration: none;
        }

        /* ============ FEATURES SECTION ============ */
        .features {
            padding: 100px 20px;
            background: rgba(11, 14, 17, 0.85);
            position: relative;
            backdrop-filter: blur(5px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .section-title p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: rgba(30, 32, 38, 0.9);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px rgba(240, 185, 11, 0.25);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--profit-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
            color: #000;
            box-shadow: 0 6px 20px rgba(240, 185, 11, 0.4);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* ============ STATS SECTION ============ */
        .stats {
            padding: 100px 20px;
            background: linear-gradient(135deg, rgba(24, 26, 32, 0.9), rgba(26, 28, 43, 0.9));
            position: relative;
            backdrop-filter: blur(5px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .stat-card {
            text-align: center;
            padding: 40px 20px;
            background: rgba(30, 32, 38, 0.9);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .stat-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(240, 185, 11, 0.25);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
            line-height: 1;
            text-shadow: 0 4px 8px rgba(240, 185, 11, 0.4);
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ============ CTA SECTION ============ */
        .cta {
            padding: 100px 20px;
            background: rgba(11, 14, 17, 0.9);
            text-align: center;
            position: relative;
            backdrop-filter: blur(5px);
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 20px;
        }

        .cta p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        /* ============ FOOTER ============ */
        .footer {
            background: rgba(24, 26, 32, 0.95);
            border-top: 1px solid var(--border-color);
            padding: 60px 20px 30px;
            backdrop-filter: blur(10px);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            color: var(--text-primary);
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-section p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.9rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
            
            .nav-menu {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                background: rgba(24, 26, 32, 0.98);
                flex-direction: column;
                padding: 20px;
                gap: 15px;
                border-bottom: 1px solid var(--border-color);
                transform: translateY(-100%);
                opacity: 0;
                transition: all 0.3s ease;
                backdrop-filter: blur(15px);
            }
            
            .nav-menu.active {
                transform: translateY(0);
                opacity: 1;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .feature-card, .stat-card {
                padding: 30px 20px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .features, .stats, .cta {
                padding: 60px 20px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .btn {
                padding: 14px 24px;
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .feature-card h3 {
                font-size: 1.3rem;
            }
        }

        /* ============ ANIMATIONS ============ */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        /* ============ SCROLL ANIMATION ============ */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- NAVIGATION COMPLÈTE (à décommenter si besoin) 
    <nav class="main-nav">
        <div class="nav-container">
            <a href="<?= BASE_URL ?>/home" class="nav-brand">
                <div class="nav-brand-logo">C</div>
                <div class="nav-brand-text">COMCV Trading</div>
            </a>
            
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="nav-menu" id="navMenu">
                <a href="<?= BASE_URL ?>/home" class="nav-item active">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="<?= BASE_URL ?>/#features" class="nav-item">
                    <i class="fas fa-crown"></i> Fonctionnalités
                </a>
                <a href="<?= BASE_URL ?>/#stats" class="nav-item">
                    <i class="fas fa-chart-line"></i> Statistiques
                </a>
                <a href="<?= BASE_URL ?>/support" class="nav-item">
                    <i class="fas fa-headset"></i> Support
                </a>
                <a href="<?= BASE_URL ?>/login" class="nav-item">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
                <a href="<?= BASE_URL ?>/register" class="nav-item" style="background: var(--primary-color); color: #000; padding: 8px 20px; border-radius: 8px;">
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
            </div>
        </div>
    </nav> -->

    <!-- SECTION HERO -->
    <section class="hero">
        <div class="hero-content fade-in">
            <h1>Investissez comme sur Binance</h1>
            <p>Plateforme professionnelle de trading avec interface avancée, sécurité maximale et rendements optimisés.</p>
            
            <div class="actions">
                <a href="<?= BASE_URL ?>/register" class="btn primary">
                    <i class="fas fa-rocket"></i> Créer un compte gratuit
                </a>
                <a href="<?= BASE_URL ?>/login" class="btn outline">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION FONCTIONNALITÉS -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title fade-in">
                <h2><i class="fas fa-crown"></i> Pourquoi choisir COMCV Trading ?</h2>
                <p>Découvrez les fonctionnalités qui font de notre plateforme la meilleure solution d'investissement</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card fade-in delay-1">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Sécurité Maximale</h3>
                    <p>Protection avancée des fonds avec chiffrement bancaire, authentification à deux facteurs et audits de sécurité réguliers.</p>
                </div>
                
                <div class="feature-card fade-in delay-2">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Trading Avancé</h3>
                    <p>Interface professionnelle avec graphiques en temps réel, ordres avancés et outils d'analyse technique complets.</p>
                </div>
                
                <div class="feature-card fade-in delay-3">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Exécution Rapide</h3>
                    <p>Exécution des ordres en millisecondes avec des serveurs à haute fréquence pour des transactions instantanées.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION STATISTIQUES -->
    <section class="stats" id="stats">
        <div class="container">
            <div class="section-title fade-in">
                <h2><i class="fas fa-trophy"></i> Nos Chiffres</h2>
                <p>Une plateforme fiable avec des résultats prouvés</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card animate-on-scroll">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Utilisateurs actifs</div>
                </div>
                
                <div class="stat-card animate-on-scroll">
                    <div class="stat-number">$50M+</div>
                    <div class="stat-label">Volume échangé</div>
                </div>
                
                <div class="stat-card animate-on-scroll">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Temps de service</div>
                </div>
                
                <div class="stat-card animate-on-scroll">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support client</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CTA -->
    <section class="cta">
        <div class="cta-content fade-in">
            <h2>Commencez votre voyage d'investissement aujourd'hui</h2>
            <p>Rejoignez des milliers d'investisseurs qui font croître leur capital avec notre plateforme sécurisée et performante.</p>
            
            <div class="actions">
                <a href="<?= BASE_URL ?>/register" class="btn primary">
                    <i class="fas fa-user-plus"></i> S'inscrire gratuitement
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>COMCV Trading</h3>
                <p>Plateforme professionnelle de trading et d'investissement avec des outils avancés pour maximiser vos rendements.</p>
            </div>
                        
            <div class="footer-section">
                <h3>Support</h3>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/support#help"><i class="fas fa-chevron-right"></i> Centre d'aide</a></li>
                    <li><a href="<?= BASE_URL ?>/support#terms"><i class="fas fa-chevron-right"></i> Conditions d'utilisation</a></li>
                    <li><a href="<?= BASE_URL ?>/support#privacy"><i class="fas fa-chevron-right"></i> Politique de confidentialité</a></li>
                    <li><a href="<?= BASE_URL ?>/support#contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> COMCV Trading. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript optimisé -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Menu mobile
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        
        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                const icon = menuToggle.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
            
            // Fermer le menu après clic sur un lien
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
        }
        
        // Animation au scroll
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        animateElements.forEach(el => observer.observe(el));
        
        // Smooth scroll pour ancres internes
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    // Fermer le menu mobile si ouvert
                    if (navMenu && navMenu.classList.contains('active')) {
                        navMenu.classList.remove('active');
                        const icon = menuToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });
        });
    });
    </script>
</body>
</html>