<?php
// src/config/config.php

define('APP_NAME', 'QuantumInvest');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost:8000');

// Investment plans
define('PLANS', [
    'starter' => [
        'min' => 50,
        'max' => 499,
        'daily' => 1.5,
        'duration' => 30
    ],
    'professional' => [
        'min' => 500,
        'max' => 4999,
        'daily' => 2.2,
        'duration' => 30
    ],
    'premium' => [
        'min' => 5000,
        'max' => 50000,
        'daily' => 3.5,
        'duration' => 30
    ]
]);

// Commission rates
define('REFERRAL_BONUS', 10); // 10%
define('WITHDRAWAL_FEE', 5); // 5%

// Session & Security
define('SESSION_TIMEOUT', 3600); // 1 hour
define('CSRF_TOKEN_LIFE', 1800); // 30 minutes

// File uploads
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'png', 'jpeg', 'pdf']);

?>

