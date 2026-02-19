<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f0f0f0; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-card { flex: 1; background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-value { font-size: 24px; font-weight: bold; color: #007bff; }
        .stat-label { color: #6c757d; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Programme de Parrainage</h1>
            <p>Bienvenue dans votre espace de parrainage</p>
        </div>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?= $user['referral_balance'] ?>€</div>
                <div class="stat-label">Solde disponible</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_referrals'] ?></div>
                <div class="stat-label">Filleuls totaux</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_commissions'] ?>€</div>
                <div class="stat-label">Total gagné</div>
            </div>
        </div>
        
        <h2>Votre lien de parrainage</h2>
        <input type="text" value="<?= $referral_link ?>" style="width: 100%; padding: 10px; margin-bottom: 20px;" readonly>
        
        <h2>Vos filleuls</h2>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Date d'inscription</th>
                <th>Investissement</th>
                <th>Niveau</th>
            </tr>
            <?php foreach ($referrals as $referral): ?>
            <tr>
                <td><?= htmlspecialchars($referral['name']) ?></td>
                <td><?= htmlspecialchars($referral['email']) ?></td>
                <td><?= htmlspecialchars($referral['registration_date']) ?></td>
                <td><?= $referral['investment_amount'] ?>€</td>
                <td>Niveau <?= $referral['level'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>Demande de retrait</h2>
        <form method="POST" action="<?= BASE_URL ?>/referral/withdrawal">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="number" name="amount" placeholder="Montant en €" step="0.01" min="10" required>
            <button type="submit">Demander le retrait</button>
        </form>
        
        <p><a href="<?= BASE_URL ?>/referral/export">Exporter mes commissions (CSV)</a></p>
    </div>
</body>
</html>