<?php
/**
 * CRON — Crédit automatique des profits d'investissement
 * Exécution : toutes les 48h
 */

declare(strict_types=1);

use App\Models\InvestmentModel;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/utils/helpers.php';

echo "[CRON] Investment profit started\n";

$investmentModel = new InvestmentModel();

/**
 * Intervalle en heures (48h)
 */
$INTERVAL_HOURS = 48;

/**
 * 1️⃣ Récupérer les investissements éligibles
 */
$investments = $investmentModel->getActiveInvestmentsReadyForProfit(
    $INTERVAL_HOURS
);

echo "[CRON] Found " . count($investments) . " eligible investments\n";

/**
 * 2️⃣ Traitement
 */
foreach ($investments as $investment) {

    $investmentId = (int) $investment['id'];
    $userId       = (int) $investment['user_id'];
    $amount       = (float) $investment['amount'];
    $rate         = (float) $investment['rate'];

    /**
     * 💰 Calcul du profit
     * ex: 100 × 2% = 2
     */
    $profit = round($amount * ($rate / 100), 2);

    if ($profit <= 0) {
        continue;
    }

    try {
        /**
         * 3️⃣ Crédit via procédure SQL
         */
        $investmentModel->creditProfit(
            $userId,
            $investmentId,
            $profit
        );

        /**
         * 4️⃣ Clôture si terminé
         */
        $investmentModel->closeIfEnded($investmentId);

        echo "[OK] Investment #$investmentId credited: $profit\n";

    } catch (Throwable $e) {
        /**
         * 🧯 Log d’erreur (prod-ready)
         */
        error_log(
            '[CRON ERROR] Investment #' . $investmentId .
            ' — ' . $e->getMessage()
        );
    }
}

echo "[CRON] Finished\n";
