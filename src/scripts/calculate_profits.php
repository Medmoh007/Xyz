<?php

require __DIR__ . '/../../vendor/autoload.php';

use App\Models\InvestmentModel;
use App\Models\UserModel;

$investmentModel = new InvestmentModel();
$userModel = new UserModel();

echo "=== Début du calcul des profits ===\n";

// Récupérer les investissements actifs qui n'ont pas eu de profit depuis 48h
$activeInvestments = $investmentModel->getActiveInvestmentsOlderThan(48);

foreach ($activeInvestments as $investment) {
    echo "Traitement de l'investissement #{$investment['id']}...\n";
    
    // Calculer le profit pour 2 jours (0.2% * 2 = 0.4%)
    $profit = $investment['amount'] * ($investment['rate'] / 100) * 2;
    $profit = round($profit, 2);
    
    echo "  Montant: {$investment['amount']}$\n";
    echo "  Taux: {$investment['rate']}%\n";
    echo "  Profit calculé: {$profit}$\n";
    
    // Créditer le profit à l'utilisateur
    $userModel->creditBalance(
        (int) $investment['user_id'],
        $profit
    );
    
    // Mettre à jour l'investissement
    $investmentModel->updateProfit(
        (int) $investment['id'],
        $profit
    );
    
    echo "  ✅ Profit crédité\n";
    
    // Vérifier si l'investissement est terminé
    $investmentModel->markAsCompleted((int) $investment['id']);
}

echo "=== Calcul terminé. " . count($activeInvestments) . " investissements traités ===\n";