<?php
// src/Views/pages/support.php
$title = 'Support & Informations | COMCV Trading';
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
           SUPPORT PAGE STYLES - CORRIGÉ
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

        /* Image de fond - chemin dynamique */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?= BASE_URL ?>/public/assets/images/img2.png');
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
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(11, 14, 17, 0.85) 0%,
                rgba(11, 14, 17, 0.7) 100%
            );
            z-index: -2;
        }

        /* ============ NAVIGATION ============ */
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

        /* ============ MAIN CONTENT ============ */
        .support-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .support-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .support-header h1 {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            background: linear-gradient(90deg, var(--primary-color), var(--profit-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .support-header p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* ============ SUPPORT LAYOUT ============ */
        .support-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 40px;
        }

        /* SIDEBAR */
        .support-sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .support-nav {
            background: rgba(30, 32, 38, 0.9);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .support-nav-title {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .support-nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .support-nav-links li {
            margin-bottom: 10px;
        }

        .support-nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .support-nav-links a:hover,
        .support-nav-links a.active {
            background: rgba(240, 185, 11, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        /* CONTENT */
        .support-content {
            background: rgba(30, 32, 38, 0.9);
            border-radius: 16px;
            padding: 40px;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .section-block {
            margin-bottom: 50px;
            padding-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-block:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .section-title {
            color: var(--text-primary);
            font-size: 1.8rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-title i {
            color: var(--primary-color);
        }

        /* CATEGORY BLOCKS */
        .category-block {
            background: rgba(11, 14, 17, 0.6);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .category-block:hover {
            border-color: var(--primary-color);
        }

        .category-title {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(43, 49, 57, 0.5);
        }

        /* FAQ ITEMS */
        .faq-item {
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(11, 14, 17, 0.4);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .faq-question {
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .faq-question i {
            color: var(--primary-color);
            margin-top: 3px;
        }

        .faq-answer {
            color: var(--text-secondary);
            line-height: 1.7;
            padding-left: 26px;
        }

        /* REFERRAL STATS */
        .referral-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .referral-stat {
            text-align: center;
            padding: 20px;
            background: rgba(240, 185, 11, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(240, 185, 11, 0.3);
        }

        .referral-stat .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .referral-stat .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* CONTACT FORM */
        .contact-form {
            background: rgba(11, 14, 17, 0.6);
            border-radius: 12px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: rgba(11, 14, 17, 0.8);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(240, 185, 11, 0.2);
            outline: none;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            color: #000;
            border: none;
            border-radius: 8px;
            padding: 14px 32px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(240, 185, 11, 0.4);
        }

        /* FOOTER */
        .support-footer {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .support-layout {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .support-sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .support-container {
                padding: 20px 15px;
            }
            
            .support-header h1 {
                font-size: 2.2rem;
            }
            
            .support-content {
                padding: 25px;
            }
            
            .section-title {
                font-size: 1.5rem;
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
        }

        @media (max-width: 480px) {
            .support-header h1 {
                font-size: 1.8rem;
            }
            
            .support-content {
                padding: 20px;
            }
            
            .category-block {
                padding: 20px;
            }
            
            .faq-item {
                padding: 15px;
            }
            
            .referral-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- NAVIGATION COMPLÈTE -->
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
                <a href="<?= BASE_URL ?>/home" class="nav-item">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="<?= BASE_URL ?>/home#features" class="nav-item">
                    <i class="fas fa-crown"></i> Fonctionnalités
                </a>
                <a href="<?= BASE_URL ?>/home#stats" class="nav-item">
                    <i class="fas fa-chart-line"></i> Statistiques
                </a>
                <a href="<?= BASE_URL ?>/support" class="nav-item active">
                    <i class="fas fa-headset"></i> Support
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard" class="nav-item">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>/logout" class="nav-item">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="nav-item">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="<?= BASE_URL ?>/register" class="nav-item" style="background: var(--primary-color); color: #000; padding: 8px 20px; border-radius: 8px;">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="support-container">
        <!-- HEADER -->
        <div class="support-header">
            <h1>Support & Informations</h1>
            <p>Trouvez toutes les informations nécessaires pour utiliser notre plateforme en toute confiance</p>
        </div>

        <div class="support-layout">
            <!-- SIDEBAR NAVIGATION -->
            <div class="support-sidebar">
                <div class="support-nav">
                    <h3 class="support-nav-title">
                        <i class="fas fa-bars"></i> Navigation
                    </h3>
                    <ul class="support-nav-links">
                        <li><a href="#help" class="active"><i class="fas fa-question-circle"></i> Centre d'aide</a></li>
                        <li><a href="#terms"><i class="fas fa-file-contract"></i> Conditions</a></li>
                        <li><a href="#privacy"><i class="fas fa-shield-alt"></i> Confidentialité</a></li>
                        <li><a href="#contact"><i class="fas fa-envelope"></i> Contact</a></li>
                    </ul>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="support-content">
                <!-- SECTION 1: CENTRE D'AIDE -->
                <section id="help" class="section-block">
                    <h2 class="section-title"><i class="fas fa-question-circle"></i> Centre d'aide</h2>
                    
                    <!-- CATÉGORIE: ACCOUNT -->
                    <div class="category-block">
                        <h3 class="category-title"><i class="fas fa-user-circle"></i> Account</h3>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-question"></i>
                                Comment créer un compte sur COMCV Trading ?
                            </div>
                            <div class="faq-answer">
                                Pour créer un compte : 1. Cliquez sur "Inscription" 2. Complissez le formulaire 3. Validez votre email 4. Activez la 2FA 5. Commencez à trader.
                            </div>
                        </div>
                    </div>

                    <!-- CATÉGORIE: PAYMENTS -->
                    <div class="category-block">
                        <h3 class="category-title"><i class="fas fa-credit-card"></i> Payments</h3>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-question"></i>
                                Comment déposer des fonds sur mon compte ?
                            </div>
                            <div class="faq-answer">
                                Plusieurs méthodes : Carte bancaire (instantanée), Virement (1-3 jours), Crypto (instantané), PayPal. Minimum : 50€.
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-question"></i>
                                Quels sont les délais de retrait ?
                            </div>
                            <div class="faq-answer">
                                Virement bancaire : 1-3 jours ouvrables<br>
                                Carte bancaire : 2-5 jours<br>
                                Crypto-monnaies : 1-24 heures<br>
                                PayPal : 1-2 jours
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-question"></i>
                                Mon dépôt n'est pas arrivé, que faire ?
                            </div>
                            <div class="faq-answer">
                                1. Vérifiez votre historique de transactions<br>
                                2. Consultez vos emails de confirmation<br>
                                3. Contactez votre banque si nécessaire<br>
                                4. Contactez notre support si le problème persiste après 24h
                            </div>
                        </div>
                    </div>

                    <!-- CATÉGORIE: REFERRAL -->
                    <div class="category-block">
                        <h3 class="category-title"><i class="fas fa-users"></i> Referral</h3>
                        
                        <div class="referral-stats">
                            <div class="referral-stat">
                                <div class="stat-number">10%</div>
                                <div class="stat-label">Niveau 1</div>
                            </div>
                            <div class="referral-stat">
                                <div class="stat-number">5%</div>
                                <div class="stat-label">Niveau 2</div>
                            </div>
                            <div class="referral-stat">
                                <div class="stat-number">2%</div>
                                <div class="stat-label">Niveau 3</div>
                            </div>
                            <div class="referral-stat">
                                <div class="stat-number">+8.25%</div>
                                <div class="stat-label">Moyenne</div>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-question"></i>
                                Comment fonctionne le programme de parrainage ?
                            </div>
                            <div class="faq-answer">
                                Niveau 1: 10%, Niveau 2: 5%, Niveau 3: 2%. Commissions payées instantanément sur chaque dépôt de vos filleuls.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 2: CONDITIONS -->
                <section id="terms" class="section-block">
                    <h2 class="section-title"><i class="fas fa-file-contract"></i> Conditions d'utilisation</h2>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <i class="fas fa-gavel"></i> Acceptation des conditions
                        </div>
                        <div class="faq-answer">
                            En utilisant notre plateforme, vous acceptez nos conditions générales. Vous devez avoir au moins 18 ans et résider dans un pays autorisé.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <i class="fas fa-exclamation-triangle"></i> Avertissement sur les risques
                        </div>
                        <div class="faq-answer">
                            Le trading comporte des risques de perte en capital. Investissez uniquement ce que vous pouvez vous permettre de perdre.
                        </div>
                    </div>
                </section>

                <!-- SECTION 3: CONFIDENTIALITÉ -->
                <section id="privacy" class="section-block">
                    <h2 class="section-title"><i class="fas fa-shield-alt"></i> Politique de confidentialité</h2>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <i class="fas fa-database"></i> Données collectées
                        </div>
                        <div class="faq-answer">
                            Nous collectons uniquement les données nécessaires pour fournir nos services : informations d'identification, données de transaction, et informations KYC.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <i class="fas fa-lock"></i> Protection des données
                        </div>
                        <div class="faq-answer">
                            Vos données sont protégées par un chiffrement de niveau bancaire et stockées sur des serveurs sécurisés conformes au RGPD.
                        </div>
                    </div>
                </section>

                <!-- SECTION 4: CONTACT -->
                <section id="contact" class="section-block">
                    <h2 class="section-title"><i class="fas fa-envelope"></i> Contact</h2>
                    
                    <div class="contact-form">
                        <form id="supportForm" method="POST" action="<?= BASE_URL ?>/support/contact">
                            <!-- Token CSRF -->
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nom complet</label>
                                        <input type="text" id="name" name="name" placeholder="Votre nom" required
                                               value="<?= htmlspecialchars($_SESSION['user_name'] ?? $_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" placeholder="email@exemple.com" required
                                               value="<?= htmlspecialchars($_SESSION['user_email'] ?? $_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Sujet</label>
                                <input type="text" id="subject" name="subject" placeholder="Objet de votre message" required
                                       value="<?= htmlspecialchars($_POST['subject'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" placeholder="Décrivez votre problème ou question..." required><?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i> Envoyer le message
                            </button>
                        </form>
                    </div>
                    
                    <!-- INFORMATIONS DE CONTACT -->
                    <div style="margin-top: 30px; padding: 20px; background: rgba(11, 14, 17, 0.4); border-radius: 10px;">
                        <h4 style="color: var(--primary-color); margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Informations de contact</h4>
                        <div style="color: var(--text-secondary);">
                            <p><i class="fas fa-globe"></i> <strong>Support & Informations | COMCV Trading</strong></p>
                            <p><i class="fas fa-server"></i> <strong>Base de données :</strong> hyip_db</p>
                            <p><i class="fas fa-link"></i> <strong>URLs :</strong> localhost / 127.0.0.1</p>
                            <p><i class="fas fa-envelope"></i> <strong>Email :</strong> support@comcv.com</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="support-footer">
            <p>© <?= date('Y') ?> COMCV Trading. Tous droits réservés.</p>
            <p><i class="fas fa-exclamation-triangle"></i> Le trading comporte des risques de perte en capital.</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
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
        
        // Smooth scroll pour les ancres
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                    
                    // Mettre à jour l'état actif dans la navigation
                    document.querySelectorAll('.support-nav-links a').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
        
        // Gestion du formulaire de contact
        const supportForm = document.getElementById('supportForm');
        if (supportForm) {
            supportForm.addEventListener('submit', function(e) {
                // Ne pas empêcher la soumission réelle, juste un feedback
                const submitBtn = this.querySelector('.submit-btn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
                submitBtn.disabled = true;
                
                // Le formulaire sera soumis normalement
            });
        }
        
        // Suivi de la section active au scroll
        const sections = document.querySelectorAll('.section-block');
        const navLinks = document.querySelectorAll('.support-nav-links a');
        
        function setActiveNav() {
            let current = '';
            const scrollPosition = window.scrollY + 150;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionBottom = sectionTop + section.offsetHeight;
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        }
        
        window.addEventListener('scroll', setActiveNav);
        setActiveNav(); // Initial call
        
        // Animation d'apparition des sections
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        // Appliquer l'animation aux sections
        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(section);
        });
    });
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>