<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Render the maintenance template
$html = view('errors.503')->render();

// Update the down file with the rendered template
$downPath = storage_path('framework/down');
$downData = json_decode(file_get_contents($downPath), true);
$downData['template'] = $html;
file_put_contents($downPath, json_encode($downData, JSON_PRETTY_PRINT));

echo "Maintenance template rendered and saved!\n";
echo "Template length: " . strlen($html) . "\n";