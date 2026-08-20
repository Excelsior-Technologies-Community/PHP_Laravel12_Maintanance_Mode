<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$adminToken = config('app.maintenance_admin_token');
echo "Admin token from config: [$adminToken]\n";
echo "Token length: " . strlen($adminToken) . "\n";

$providedToken = "your-secret-admin-token-here";
echo "Provided token: [$providedToken]\n";
echo "Provided length: " . strlen($providedToken) . "\n";

echo "Hash equals: " . (hash_equals($adminToken, (string) $providedToken) ? 'YES' : 'NO') . "\n";
echo "Simple equals: " . ($adminToken === $providedToken ? 'YES' : 'NO') . "\n";