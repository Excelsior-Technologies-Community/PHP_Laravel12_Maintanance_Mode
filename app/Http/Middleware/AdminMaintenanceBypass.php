<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMaintenanceBypass
{
    protected $exceptPaths = [
        '/health',
        '/up',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        $adminToken = config('app.maintenance_admin_token');

        if (!$adminToken) {
            return $next($request);
        }

        $providedToken = $request->query('maintenance_bypass');
        $hasValidToken = hash_equals($adminToken, (string) $providedToken);

        if (!$hasValidToken) {
            return $next($request);
        }

        // Valid token - set laravel_maintenance cookie for persistence and allow access
        $response = $next($request);

        $expiresAt = time() + (60 * 24 * 60); // 12 hours
        $payload = [
            'expires_at' => $expiresAt,
            'mac' => hash_hmac('sha256', (string) $expiresAt, $adminToken),
        ];

        $secure = $request->isSecure();

        // Debug: add header to verify middleware runs
        $response->headers->set('X-Admin-Bypass', 'active');

        $response->cookie('laravel_maintenance', base64_encode(json_encode($payload)), 60 * 24, null, null, $secure, true, false, 'lax');

        return $response;
    }

    protected function shouldPassThrough(Request $request): bool
    {
        $path = $request->path();

        foreach ($this->exceptPaths as $except) {
            if ($path === ltrim($except, '/')) {
                return true;
            }
        }

        return false;
    }
}