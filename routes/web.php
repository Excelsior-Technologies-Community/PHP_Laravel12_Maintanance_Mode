<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Helper: safely write key=value to .env
function updateEnv(array $updates): void
{
    $envPath = base_path('.env');
    $envContent = file_get_contents($envPath);

    foreach ($updates as $key => $value) {
        $value = (string) $value;
        // Use single quotes for values containing double quotes (e.g. JSON)
        if (str_contains($value, '"')) {
            $escaped = str_replace("'", "\\'", $value);
            $replacement = "{$key}='{$escaped}'";
        } else {
            $replacement = "{$key}=\"{$value}\"";
        }
        $pattern = "/^{$key}=.*$/m";
        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n{$replacement}";
        }
    }

    file_put_contents($envPath, $envContent);
}

// Helper: write except paths into down file
function writeDownFile(string $secret): void
{
    $downPath = storage_path('framework/down');
    if (!file_exists($downPath)) return;

    $data = json_decode(file_get_contents($downPath), true) ?? [];
    $data['except'] = [
        'health', 'up', 'admin-bypass',
        'admin-maintenance',
        'admin-maintenance/toggle',
        'admin-maintenance/status',
        'admin-maintenance/config',
    ];
    $data['template'] = null;
    $data['secret'] = $secret;
    file_put_contents($downPath, json_encode($data, JSON_PRETTY_PRINT));
}

// ─── Home ────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ─── Health Check ─────────────────────────────────────────────────────────────
Route::get('/health', function () {
    $maintenance = app()->isDownForMaintenance();
    $data = [
        'status'      => $maintenance ? 'maintenance' : 'ok',
        'maintenance' => $maintenance,
        'timestamp'   => now()->toISOString(),
        'version'     => config('app.version', '1.0.0'),
        'message'     => $maintenance ? 'Application is in maintenance mode' : 'Application is running normally',
    ];

    if (request()->expectsJson()) {
        return response()->json($data)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    return view('health', $data);
})->name('health');

// ─── Admin Bypass Cookie ──────────────────────────────────────────────────────
Route::get('/admin-bypass', function () {
    $token      = request()->query('token');
    $adminToken = config('app.maintenance_admin_token');

    if (!$adminToken || !hash_equals($adminToken, (string) $token)) {
        abort(403, 'Invalid or missing admin token');
    }

    $expiresAt = time() + (12 * 60 * 60);
    $payload   = [
        'expires_at' => $expiresAt,
        'mac'        => hash_hmac('sha256', (string) $expiresAt, $adminToken),
    ];

    return response()
        ->view('admin-bypass')
        ->withCookie(cookie(
            'laravel_maintenance',
            base64_encode(json_encode($payload)),
            720, null, null,
            request()->isSecure(), true, false, 'lax', false
        ));
})->name('admin.bypass');

// ─── Admin Panel UI ───────────────────────────────────────────────────────────
Route::get('/admin-maintenance', function () {
    $token      = request()->query('token');
    $adminToken = config('app.maintenance_admin_token');

    if (!$adminToken || !hash_equals($adminToken, (string) $token)) {
        abort(403, 'Invalid or missing admin token');
    }

    return view('admin-maintenance');
})->name('admin.maintenance');

// ─── API: Get Status ──────────────────────────────────────────────────────────
Route::get('/admin-maintenance/status', function () {
    $adminToken     = config('app.maintenance_admin_token');
    $providedToken  = request()->query('token');

    if (!$adminToken || !hash_equals($adminToken, (string) $providedToken)) {
        return response()->json(['success' => false, 'message' => 'Invalid token'], 403);
    }

    return response()->json([
        'success'       => true,
        'maintenance'   => app()->isDownForMaintenance(),
        'message'       => config('app.maintenance_message', ''),
        'type'          => config('app.maintenance_type', 'general'),
        'progress'      => (int) config('app.maintenance_progress', 0),
        'start_time'    => config('app.maintenance_start_time', ''),
        'end_time'      => config('app.maintenance_end_time', ''),
        'contact_email' => config('app.maintenance_contact_email', ''),
        'updates'       => config('app.maintenance_status_updates', []),
        'updated_at'    => now()->toISOString(),
    ]);
})->name('admin.maintenance.status');

// ─── API: Toggle Maintenance ──────────────────────────────────────────────────
Route::post('/admin-maintenance/toggle', function () {
    $adminToken    = config('app.maintenance_admin_token');
    $providedToken = request()->query('token') ?? request()->input('token');

    if (!$adminToken || !$providedToken || !hash_equals($adminToken, (string) $providedToken)) {
        return response()->json(['success' => false, 'message' => 'Invalid token'], 403);
    }

    $enable = filter_var(request()->query('maintenance') ?? request()->input('maintenance'), FILTER_VALIDATE_BOOLEAN);

    if ($enable) {
        Artisan::call('down', ['--secret' => $adminToken, '--retry' => 60, '--status' => 503]);
        writeDownFile($adminToken);
        updateEnv(['MAINTENANCE_START_TIME' => now()->format('Y-m-d H:i:s')]);
    } else {
        Artisan::call('up');
        updateEnv(['MAINTENANCE_START_TIME' => '', 'MAINTENANCE_END_TIME' => '']);
    }

    Artisan::call('config:clear');

    return response()->json([
        'success'     => true,
        'maintenance' => $enable,
        'message'     => $enable ? 'Maintenance mode enabled' : 'Maintenance mode disabled',
    ]);
})->name('admin.maintenance.toggle');

// ─── API: Save Config ─────────────────────────────────────────────────────────
Route::post('/admin-maintenance/config', function () {
    $adminToken    = config('app.maintenance_admin_token');
    $providedToken = request()->query('token') ?? request()->input('token');

    if (!$adminToken || !$providedToken || !hash_equals($adminToken, (string) $providedToken)) {
        return response()->json(['success' => false, 'message' => 'Invalid token'], 403);
    }

    $updates = [];

    if (request()->has('message'))       $updates['MAINTENANCE_MESSAGE']        = request()->input('message');
    if (request()->has('type'))          $updates['MAINTENANCE_TYPE']           = request()->input('type');
    if (request()->has('progress'))      $updates['MAINTENANCE_PROGRESS']       = (int) request()->input('progress');
    if (request()->has('start_time'))    $updates['MAINTENANCE_START_TIME']     = request()->input('start_time') ?? '';
    if (request()->has('end_time'))      $updates['MAINTENANCE_END_TIME']       = request()->input('end_time') ?? '';
    if (request()->has('contact_email')) $updates['MAINTENANCE_CONTACT_EMAIL']  = request()->input('contact_email');
    if (request()->has('updates'))       $updates['MAINTENANCE_STATUS_UPDATES'] = json_encode(request()->input('updates', []));

    if (!empty($updates)) {
        updateEnv($updates);
    }

    Artisan::call('config:clear');

    return response()->json(['success' => true, 'message' => 'Configuration updated']);
})->name('admin.maintenance.config');
