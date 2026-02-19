#!/usr/bin/env php
<?php
require_once __DIR__ . '/../config/config.php'; // ajuste selon ton bootstrap
require_once __DIR__ . '/../vendor/autoload.php'; // si tu utilises Composer

use App\Models\DepositModel;

$depositModel = new DepositModel();
$count = $depositModel->autoApprovePending(10); // approuve les dépôts en attente depuis >10 min

echo "[CRON] " . date('Y-m-d H:i:s') . " - $count dépôt(s) approuvé(s) automatiquement.\n";