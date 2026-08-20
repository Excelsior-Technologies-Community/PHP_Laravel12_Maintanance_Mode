<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Http\Request;

PreventRequestsDuringMaintenance::except(['/health', '/up', '/admin-bypass', '/admin-maintenance', '/admin-maintenance/*']);

$m = new PreventRequestsDuringMaintenance($app);

$paths = ['/health', '/up', '/admin-bypass', '/admin-maintenance', '/admin-maintenance/xxx', '/', '/foo'];
foreach ($paths as $path) {
    $r = new Illuminate\Http\Request();
    $r->server->set('REQUEST_URI', $path);
    $result = $m->inExceptArray($r) ? 'EXCLUDED' : 'BLOCKED';
    echo "$path => $result" . PHP_EOL;
}
