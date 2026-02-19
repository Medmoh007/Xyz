<?php
$history = $history ?? [
    'deposits' => [],
    'withdrawals' => [],
    'trades' => [],
    'investments' => []
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique | COMCV Trading</title>
    
    <style>
        .history-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            background: var(--background-darker);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .filter-btn:hover:not(.active) {
            border-color: var(--text-secondary);
        }

        .history-section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), #ff9900);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .section-title {
            font-size: 1.3rem;
            color: var(--text-primary);
            margin: 0;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: var(--background-darker);
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        /* Table responsive */
        .table-responsive {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: var(--background-darker);
            padding: 15px;
            text-align: left;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: rgba(14, 203, 129, 0.1);
            color: var(--profit-color);
        }

        .badge-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .badge-danger {
            background: rgba(246, 70, 93, 0.1);
            color: var(--loss-color);
        }

        .badge-info {
            background: rgba(240, 185, 11, 0.1);
            color: var(--primary-color);
        }

        .type-buy {
            color: var(--profit-color);
            font-weight: 600;
        }

        .type-sell {
            color: var(--loss-color);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="/" class="nav-brand">
                <div class="nav-brand-logo">C</div>
                <div class="nav-brand-text">COMCV Trading</div>
            </a>
            
            <div class="nav-menu">
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?= BASE_URL ?>/investments" class="nav-item">
                    <i class="fas fa-chart-line"></i> Investments
                </a>
                <a href="<?= BASE_URL ?>/wallet" class="nav-item">
                    <i class="fas fa-wallet"></i> Wallet
                </a>
                <a href="<?= BASE_URL ?>/trade" class="nav-item">
                    <i class="fas fa-exchange-alt"></i> Trade
                </a>
                <a href="<?= BASE_URL ?>/login" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="history-container">
        <h1 style="color: var(--text-primary); margin-bottom: 30px; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-history"></i> Historique Complet
        </h1>

        <!-- FILTRES -->
        <div class="filters">
            <button class="filter-btn active" data-filter="all">Tout</button>
            <button class="filter-btn" data-filter="deposits">Dépôts</button>
            <button class="filter-btn" data-filter="withdrawals">Retraits</button>
            <button class="filter-btn" data-filter="trades">Trades</button>
            <button class="filter-btn" data-filter="investments">Investissements</button>
        </div>

        <!-- SECTION DÉPÔTS -->
        <div class="history-section fade-in" id="depositsSection">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <h2 class="section-title">Dépôts</h2>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">
                    <?= count($history['deposits']) ?> transaction(s)
                </span>
            </div>

            <?php if (empty($history['deposits'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 10px;">Aucun dépôt</h3>
                    <p style="color: var(--text-secondary);">Vous n'avez effectué aucun dépôt.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history['deposits'] as $deposit): ?>
                            <tr>
                                <td>#<?= $deposit['id'] ?></td>
                                <td>$<?= number_format($deposit['amount'], 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($deposit['created_at'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $deposit['status'] === 'approved' ? 'success' : 
                                        ($deposit['status'] === 'pending' ? 'warning' : 'danger')
                                    ?>">
                                        <?= $deposit['status'] === 'pending' ? 'En attente' : 
                                            ($deposit['status'] === 'approved' ? 'Approuvé' : 'Rejeté') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($deposit['screenshot']): ?>
                                        <a href="<?= $deposit['screenshot'] ?>" target="_blank" 
                                           style="color: var(--primary-color); text-decoration: none;">
                                            <i class="fas fa-eye"></i> Preuve
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECTION RETRAITS -->
        <div class="history-section fade-in" id="withdrawalsSection">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <h2 class="section-title">Retraits</h2>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">
                    <?= count($history['withdrawals']) ?> transaction(s)
                </span>
            </div>

            <?php if (empty($history['withdrawals'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 10px;">Aucun retrait</h3>
                    <p style="color: var(--text-secondary);">Vous n'avez effectué aucun retrait.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Montant</th>
                                <th>Adresse</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history['withdrawals'] as $withdrawal): ?>
                            <tr>
                                <td>#<?= $withdrawal['id'] ?></td>
                                <td>$<?= number_format($withdrawal['amount'], 2) ?></td>
                                <td>
                                    <code style="font-size: 0.8rem; color: var(--text-secondary);">
                                        <?= substr($withdrawal['address'], 0, 8) ?>...<?= substr($withdrawal['address'], -8) ?>
                                    </code>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($withdrawal['created_at'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $withdrawal['status'] === 'approved' ? 'success' : 
                                        ($withdrawal['status'] === 'pending' ? 'warning' : 'danger')
                                    ?>">
                                        <?= $withdrawal['status'] === 'pending' ? 'En attente' : 
                                            ($withdrawal['status'] === 'approved' ? 'Approuvé' : 'Rejeté') ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECTION TRADES -->
        <div class="history-section fade-in" id="tradesSection">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h2 class="section-title">Trades</h2>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">
                    <?= count($history['trades']) ?> trade(s)
                </span>
            </div>

            <?php if (empty($history['trades'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 10px;">Aucun trade</h3>
                    <p style="color: var(--text-secondary);">Vous n'avez effectué aucun trade.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paire</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Prix</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history['trades'] as $trade): ?>
                            <tr>
                                <td>#<?= $trade['id'] ?></td>
                                <td><?= $trade['pair'] ?></td>
                                <td>
                                    <span class="<?= $trade['type'] === 'buy' ? 'type-buy' : 'type-sell' ?>">
                                        <?= $trade['type'] === 'buy' ? 'Achat' : 'Vente' ?>
                                    </span>
                                </td>
                                <td>$<?= number_format($trade['amount'], 2) ?></td>
                                <td>$<?= number_format($trade['price'], 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($trade['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECTION INVESTISSEMENTS -->
        <div class="history-section fade-in" id="investmentsSection">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="section-title">Investissements</h2>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">
                    <?= count($history['investments']) ?> investissement(s)
                </span>
            </div>

            <?php if (empty($history['investments'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 10px;">Aucun investissement</h3>
                    <p style="color: var(--text-secondary);">Vous n'avez aucun investissement actif.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Plan</th>
                                <th>Montant</th>
                                <th>Profit</th>
                                <th>Date de début</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history['investments'] as $investment): ?>
                            <tr>
                                <td>#<?= $investment['id'] ?></td>
                                <td><?= htmlspecialchars($investment['plan_name']) ?></td>
                                <td>$<?= number_format($investment['amount'], 2) ?></td>
                                <td style="color: var(--profit-color);">+$<?= number_format($investment['total_profit'], 2) ?></td>
                                <td><?= date('d/m/Y', strtotime($investment['start_date'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $investment['status'] === 'active' ? 'success' : 
                                        ($investment['status'] === 'pending' ? 'warning' : 'info')
                                    ?>">
                                        <?= $investment['status'] === 'active' ? 'Actif' : 
                                            ($investment['status'] === 'pending' ? 'En attente' : 'Terminé') ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrage des sections
        const filters = document.querySelectorAll('.filter-btn');
        const sections = document.querySelectorAll('.history-section');
        
        filters.forEach(filter => {
            filter.addEventListener('click', function() {
                // Retirer la classe active de tous les filtres
                filters.forEach(f => f.classList.remove('active'));
                // Ajouter la classe active au filtre cliqué
                this.classList.add('active');
                
                const filterValue = this.dataset.filter;
                
                // Afficher/masquer les sections
                sections.forEach(section => {
                    if (filterValue === 'all') {
                        section.style.display = 'block';
                        setTimeout(() => {
                            section.style.opacity = '1';
                            section.style.transform = 'translateY(0)';
                        }, 10);
                    } else if (section.id.includes(filterValue)) {
                        section.style.display = 'block';
                        setTimeout(() => {
                            section.style.opacity = '1';
                            section.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        section.style.opacity = '0';
                        section.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            section.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Animation de fade-in pour les sections
        sections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                section.style.transition = 'all 0.5s ease';
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
    </script>
</body>
</html>