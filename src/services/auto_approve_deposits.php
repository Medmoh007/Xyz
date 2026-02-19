#!/usr/bin/env php
<?php
/**
 * Script CLI : Approuve automatiquement les dépôts en attente depuis plus de 20 minutes.
 *
 * Exécution recommandée : toutes les minutes via cron.
 */

// ------------------------------------------------------------
// 1. Charger l'environnement de l'application
// ------------------------------------------------------------
// Adapte le chemin selon ton architecture (ex: public/index.php, config/bootstrap.php)
require_once __DIR__ . '/../config/config.php';      // Contient les constantes, autoloader, etc.
// Si tu n'as pas de fichier de bootstrap central, charge les classes nécessaires :
// require_once __DIR__ . '/../app/Lib/Database.php';
// require_once __DIR__ . '/../app/Models/DepositModel.php';

use App\Models\DepositModel;

// ------------------------------------------------------------
// 2. Exécution
// ------------------------------------------------------------
try {
    $depositModel = new DepositModel();
    $minutes = 20; // Délai d'attente avant approbation

    echo "[" . date('Y-m-d H:i:s') . "] Recherche des dépôts en attente depuis > $minutes minutes...\n";

    $pendingDeposits = $depositModel->getPendingOlderThan($minutes);

    if (empty($pendingDeposits)) {
        echo "Aucun dépôt à approuver.\n";
        exit(0);
    }

    $approvedCount = 0;
    $errorCount = 0;

    foreach ($pendingDeposits as $deposit) {
        try {
            $depositModel->approve($deposit['id']);
            echo "✓ Dépôt ID {$deposit['id']} (utilisateur {$deposit['user_id']}, montant {$deposit['amount']}) approuvé et wallet crédité.\n";
            $approvedCount++;
        } catch (\Exception $e) {
            echo "✗ Erreur dépôt ID {$deposit['id']} : " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }

    echo "Terminé : $approvedCount approuvé(s), $errorCount erreur(s).\n";
    exit(0);
} catch (\Exception $e) {
    echo "ERREUR GLOBALE : " . $e->getMessage() . "\n";
    exit(1);
}