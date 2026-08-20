<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Home</title>
    <link rel="icon" type="image/x-icon" href="/favicon-maintenance.svg">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #fff; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px 20px;
        }
        .logo { font-size: 48px; margin-bottom: 16px; }
        h1 { font-size: 36px; font-weight: 700; margin-bottom: 12px; text-align: center; }
        .subtitle { font-size: 16px; color: #94a3b8; margin-bottom: 40px; text-align: center; }
        .status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 100px; font-size: 13px; font-weight: 600; text-transform: uppercase; margin-bottom: 40px; }
        .status-ok { background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3); }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; animation: pulse 1.5s infinite; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; width: 100%; max-width: 800px; margin-bottom: 32px; }
        .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; text-align: center; text-decoration: none; color: #fff; transition: all 0.2s; }
        .card:hover { background: rgba(255,255,255,0.1); transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
        .card-icon { font-size: 32px; margin-bottom: 12px; }
        .card-title { font-size: 15px; font-weight: 600; margin-bottom: 6px; }
        .card-desc { font-size: 12px; color: #64748b; }
        .footer { font-size: 12px; color: #475569; margin-top: 16px; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
        @media (max-width: 640px) { h1 { font-size: 28px; } .card-grid { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>
    @php $adminToken = config('app.maintenance_admin_token'); @endphp

    <div class="logo">🚀</div>
    <h1>{{ config('app.name', 'Laravel') }}</h1>
    <p class="subtitle">Laravel 12 Maintenance Mode Project</p>

    <div class="status-badge status-ok">
        <span class="dot"></span>
        Site is Online
    </div>

    <div class="card-grid">
        <a href="/health" class="card">
            <div class="card-icon">💚</div>
            <div class="card-title">Health Check</div>
            <div class="card-desc">View system status & toggle maintenance</div>
        </a>

        @if($adminToken)
        <a href="/admin-maintenance?token={{ $adminToken }}" class="card">
            <div class="card-icon">🔧</div>
            <div class="card-title">Admin Panel</div>
            <div class="card-desc">Manage maintenance mode & config</div>
        </a>
        @endif

        <a href="/admin-bypass?token={{ $adminToken }}" class="card">
            <div class="card-icon">🔑</div>
            <div class="card-title">Admin Bypass</div>
            <div class="card-desc">Set bypass cookie for maintenance access</div>
        </a>

        <div class="card" style="cursor:default;" onclick="runArtisanDown()">
            <div class="card-icon">🔴</div>
            <div class="card-title">Enable Maintenance</div>
            <div class="card-desc">Put site in maintenance mode via API</div>
        </div>
    </div>

    <div class="footer">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All Rights Reserved.</div>

    <div style="position:fixed;bottom:24px;right:24px;padding:14px 22px;border-radius:12px;font-weight:500;font-size:14px;z-index:1000;transform:translateX(120%);transition:transform 0.3s;" id="toast"></div>

    <script>
        const adminToken = '{{ $adminToken ?? '' }}';

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.style.background = type === 'success' ? 'linear-gradient(135deg,#22c55e,#16a34a)' : 'linear-gradient(135deg,#ef4444,#dc2626)';
            t.style.color = '#fff';
            t.style.transform = 'translateX(0)';
            setTimeout(() => t.style.transform = 'translateX(120%)', 3500);
        }

        async function runArtisanDown() {
            if (!adminToken) { showToast('No admin token configured', 'error'); return; }
            try {
                const res = await fetch(`/admin-maintenance/toggle?token=${encodeURIComponent(adminToken)}&maintenance=true`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    showToast('🔴 Maintenance enabled!');
                    setTimeout(() => location.href = '/health', 1500);
                } else {
                    showToast(data.message || 'Failed', 'error');
                }
            } catch(e) { showToast('Error: ' + e.message, 'error'); }
        }
    </script>
</body>
</html>
