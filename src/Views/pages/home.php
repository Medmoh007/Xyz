<?php
$title = 'HYIP Invest - Plateforme d\'Investissement Futuriste';
$extra_css = '<link rel="stylesheet" href="/assets/css/main.css">';
$extra_js = '<script src="/assets/js/main.js"></script>';
ob_start();
?>

<!-- Hero Section avec effet parallaxe -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-8 mx-auto text-center">
                <!-- Logo Animé -->
                <div class="logo-hero mb-4">
                    <span class="logo-glow-hero">HYIP</span>
                    <span class="logo-text-hero">INVEST</span>
                    <div class="logo-orbits">
                        <div class="orbit-1"></div>
                        <div class="orbit-2"></div>
                        <div class="orbit-3"></div>
                    </div>
                </div>
                
                <h1 class="display-3 fw-bold mb-4 neon-title">
                    <span class="neon-text">L'Investissement</span>
                    <span class="neon-accent">Redéfini</span>
                </h1>
                
                <p class="lead mb-5 text-light-sub">
                    Plateforme éducative de cybersécurité - Simulez, apprenez et maîtrisez 
                    les mécanismes d'investissement dans un environnement contrôlé et sécurisé
                </p>
                
                <!-- CTA Buttons -->
                <div class="cta-buttons">
                    <a href="<?= config('base_url') ?>/register" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i>Commencer Maintenant
                    </a>
                    <a href="<?= config('base_url') ?>/about" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-info-circle me-2"></i>En Savoir Plus
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="stat-number" data-count="5000">0</h3>
                    <p class="stat-label">Investisseurs Actifs</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="stat-number" data-count="2.5">0</h3>
                    <p class="stat-label">Million € Investis</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <h3 class="stat-number" data-count="98">0</h3>
                    <p class="stat-label">Taux de Satisfaction</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="stat-number" data-count="100">0</h3>
                    <p class="stat-label">% Sécurisé</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Pourquoi Nous Choisir ?</h2>
            <p class="section-subtitle">Une expérience d'investissement unique et sécurisée</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="feature-card glass-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Transactions Instantanées</h4>
                    <p>Dépôts et retraits traités en quelques secondes grâce à notre technologie blockchain avancée.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-card glass-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4>Sécurité Maximale</h4>
                    <p>Protection de niveau bancaire avec chiffrement AES-256 et authentification à deux facteurs.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-card glass-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Support 24/7</h4>
                    <p>Notre équipe d'experts est disponible à tout moment pour vous assister dans vos investissements.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Login Preview Section -->
<section class="login-preview py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="preview-card glass-card">
                    <h3>Prêt à Commencer ?</h3>
                    <p>Rejoignez notre communauté d'investisseurs et accédez à des opportunités uniques.</p>
                    <div class="mt-4">
                        <a href="<?= config('base_url') ?>/login" class="btn btn-primary me-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </a>
                        <a href="<?= config('base_url') ?>/register" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Créer un Compte
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard-preview">
                    <div class="preview-screen glass-card">
                        <div class="screen-header">
                            <div class="dots">
                                <span class="dot red"></span>
                                <span class="dot yellow"></span>
                                <span class="dot green"></span>
                            </div>
                        </div>
                        <div class="screen-content">
                            <div class="chart-preview"></div>
                            <div class="stats-preview">
                                <div class="stat-mini">
                                    <small>Balance</small>
                                    <strong>$10,250.00</strong>
                                </div>
                                <div class="stat-mini">
                                    <small>Profit</small>
                                    <strong class="text-success">+$450.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>