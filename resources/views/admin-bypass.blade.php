<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access Granted - {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon-maintenance.svg">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #fff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .container { width: 100%; max-width: 520px; text-align: center; background: rgba(255,255,255,0.06); padding: 48px 40px; border-radius: 24px; backdrop-filter: blur(20px); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05), inset 0 1px 0 rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.08); }
        .success-icon { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #22c55e, #16a34a); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 0 30px rgba(34,197,94,0.4); }
        .success-icon svg { width: 40px; height: 40px; stroke: white; }
        h1 { font-size: 32px; font-weight: 700; margin-bottom: 12px; }
        .subtitle { color: #94a3b8; margin-bottom: 32px; font-size: 16px; }
        .info-box { text-align: left; background: rgba(255,255,255,0.04); border-radius: 12px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(34,197,94,0.2); }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94a3b8; font-size: 13px; }
        .info-value { color: #86efac; font-weight: 600; font-family: monospace; font-size: 13px; word-break: break-all; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #0f172a; font-weight: 600; text-decoration: none; border-radius: 10px; transition: all 0.2s; border: none; cursor: pointer; font-size: 14px; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(56,189,248,0.4); }
        .btn-secondary { background: rgba(255,255,255,0.08); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); }
        .btn-secondary:hover { background: rgba(255,255,255,0.15); }
        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .cookie-notice { font-size: 12px; color: #64748b; margin-top: 20px; padding: 12px; background: rgba(56,189,248,0.1); border-radius: 8px; border: 1px solid rgba(56,189,248,0.2); }
        .cookie-notice strong { color: #38bdf8; }
        @media (max-width: 640px) { .container { padding: 32px 24px; } h1 { font-size: 28px; } .btn-group { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h1>Admin Access Granted</h1>
        <p class="subtitle">Maintenance mode bypass activated successfully</p>
        <div class="info-box">
            <div class="info-row"><span class="info-label">Status</span><span class="info-value">Active</span></div>
            <div class="info-row"><span class="info-label">Bypass Token</span><span class="info-value">Validated</span></div>
            <div class="info-row"><span class="info-label">Cookie Set</span><span class="info-value">12 hours (lax, secure, httponly)</span></div>
            <div class="info-row"><span class="info-label">Maintenance Mode</span><span class="info-value">{{ app()->isDownForMaintenance() ? 'Active' : 'Inactive' }}</span></div>
        </div>
        <div class="btn-group">
            <a href="/" class="btn">Visit Site</a>
            <a href="/health" class="btn btn-secondary">Health Check</a>
        </div>
        <div class="cookie-notice">
            <strong>Note:</strong> A secure <code>laravel_maintenance</code> cookie has been set. You can now browse the site normally during maintenance. The cookie expires in 12 hours.
        </div>
    </div>
</body>
</html>