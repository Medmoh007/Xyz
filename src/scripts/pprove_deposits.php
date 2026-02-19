<?php

require __DIR__ . '/../../vendor/autoload.php';

use App\Models\DepositModel;
use App\Models\UserModel;

$depositModel = new DepositModel();
$userModel = new UserModel();

/**
 * Dépôts pending depuis +13 minutes
 */
$pendingDeposits = $depositModel->getPendingOlderThan(13);

foreach ($pendingDeposits as $deposit) {

    // 1. Crédit du solde utilisateur
    $userModel->creditBalance(
        (int) $deposit['user_id'],
        (float) $deposit['amount']
    );

    // 2. Validation du dépôt
    $depositModel->approve((int) $deposit['id']);

    echo "✔ Dépôt #{$deposit['id']} approuvé\n";
}
