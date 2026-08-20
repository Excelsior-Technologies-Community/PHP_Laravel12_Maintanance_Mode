<?php

use App\Http\Middleware\AdminMaintenanceBypass;
use App\Http\Middleware\DebugMaintenanceMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

PreventRequestsDuringMaintenance::except([
    'health',
    'up',
    'admin-bypass',
    'admin-maintenance',
    'admin-maintenance/toggle',
    'admin-maintenance/status',
    'admin-maintenance/config',
]);

VerifyCsrfToken::except([
    '/admin-maintenance/toggle',
    '/admin-maintenance/config',
]);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(AdminMaintenanceBypass::class);
        $middleware->prepend(DebugMaintenanceMode::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
