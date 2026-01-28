<?php
require __DIR__ . '/../../vendor/autoload.php';

use App\Models\UserModel;

$user = new UserModel();
$user->create([
    'name' => 'Admin',
    'email' => 'admin@hyip.edu',
    'password' => password_hash('admin123', PASSWORD_BCRYPT),
    'role' => 'admin'
]);

echo "✔ Données fake générées\n";
