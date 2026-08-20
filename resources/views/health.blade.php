<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Check - {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon-maintenance.svg">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #fff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .container { width: 100%; max-width: 620px; }
        .card { background: rgba(255,255,255,0.06); padding: 40px; border-radius: 24px; backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); text-align: center; }
        .status-badge { display: inline-flex; align-items: center; gap: 10px; padding: 10px 22px; border-radius: 100px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
        .status-ok { background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3); }
        .status-maintenance { background: rgba(249,115,22,0.15); color: #fed7aa; border: 1px solid rgba(249,115,22,0.3); }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-ok { background: #22c55e; animation: pulse 1.5s infinite; }
        .dot-maintenance { background: #f97316; animation: blink 1.5s infinite; }
        h1 { font-size: 30px; font-weight: 700; margin-bottom: 8px; }
        .subtitle { color: #94a3b8; font-size: 15px; margin-bottom: 28px; }
        .details { text-align: left; background: rgba(255,255,255,0.04); border-radius: 14px; padding: 20px; margin-bottom: 24px; border: 1px solid rgba(255,255,255,0.06); }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 11px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #94a3b8; font-size: 13px; font-weight: 500; }
        .detail-value { color: #e2e8f0; font-weight: 600; font-family: monospace; font-size: 13px; }
        .detail-value.ok { color: #4ade80; }
        .detail-value.warn { color: #fb923c; }
        /* Maintenance info box */
        .maintenance-info { background: rgba(249,115,22,0.08); border: 1px solid rgba(249,115,22,0.2); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; text-align: left; }
        .maintenance-info-title { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #fb923c; letter-spacing: 0.5px; margin-bottom: 10px; }
        .maintenance-info-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid rgba(249,115,22,0.1); }
        .maintenance-info-row:last-child { border-bottom: none; }
        .maintenance-info-row span:first-child { color: #94a3b8; }
        .maintenance-info-row span:last-child { color: #fed7aa; font-weight: 600; max-width: 60%; text-align: right; word-break: break-word; }
        /* Buttons */
        .btn-group { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 20px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 22px; border-radius: 10px; font-weight: 600; font-size: 13px; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #0f172a; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(56,189,248,0.4); }
        .btn-danger { background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(249,115,22,0.4); }
        .btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(34,197,94,0.4); }
        .btn-secondary { background: rgba(255,255,255,0.08); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); }
        .btn-secondary:hover { background: rgba(255,255,255,0.15); }
        /* JSON toggle */
        .json-section { margin-top: 4px; }
        .json-toggle { background: rgba(56,189,248,0.08); border: 1px solid rgba(56,189,248,0.2); color: #38bdf8; padding: 8px 18px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .json-toggle:hover { background: rgba(56,189,248,0.15); }
        pre { display: none; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 16px; text-align: left; overflow-x: auto; font-size: 12px; color: #86efac; margin-top: 12px; }
        pre.show { display: block; }
        /* Toast */
        .toast { position: fixed; bottom: 24px; right: 24px; padding: 14px 22px; border-radius: 12px; font-weight: 500; font-size: 14px; z-index: 1000; transform: translateX(120%); transition: transform 0.3s; }
        .toast.show { transform: translateX(0); }
        .toast-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
        .spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.8s linear infinite; display: inline-block; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 640px) { .card { padding: 28px 20px; } h1 { font-size: 24px; } .btn-group { flex-direction: column; align-items: center; } }
    </style>
</head>
<body>
    @php
        $isMaintenance = $maintenance ?? false;
        $adminToken = config('app.maintenance_admin_token');
    @endphp

    <div class="container">
        <div class="card">
            {{-- Status Badge --}}
            <div class="status-badge {{ $isMaintenance ? 'status-maintenance' : 'status-ok' }}">
                <span class="dot {{ $isMaintenance ? 'dot-maintenance' : 'dot-ok' }}"></span>
                {{ $isMaintenance ? 'Maintenance Mode' : 'System Online' }}
            </div>

            <h1>Health Check</h1>
            <p class="subtitle">{{ $message ?? 'Application status overview' }}</p>

            {{-- Core Details --}}
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value {{ $isMaintenance ? 'warn' : 'ok' }}">{{ $isMaintenance ? 'MAINTENANCE' : 'OK' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Maintenance Mode</span>
                    <span class="detail-value {{ $isMaintenance ? 'warn' : 'ok' }}">{{ $isMaintenance ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Timestamp</span>
                    <span class="detail-value">{{ $timestamp ?? now()->toISOString() }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Version</span>
                    <span class="detail-value">{{ $version ?? config('app.version', '1.0.0') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Environment</span>
                    <span class="detail-value">{{ config('app.env', 'production') }}</span>
                </div>
            </div>

            {{-- Maintenance Details (only when active) --}}
            @if($isMaintenance)
            <div class="maintenance-info">
                <div class="maintenance-info-title">⚠️ Maintenance Details</div>
                @if(config('app.maintenance_message'))
                <div class="maintenance-info-row"><span>Message</span><span>{{ config('app.maintenance_message') }}</span></div>
                @endif
                @if(config('app.maintenance_type'))
                <div class="maintenance-info-row"><span>Type</span><span>{{ ucfirst(config('app.maintenance_type')) }}</span></div>
                @endif
                @if(config('app.maintenance_progress') !== null)
                <div class="maintenance-info-row"><span>Progress</span><span>{{ config('app.maintenance_progress') }}%</span></div>
                @endif
                @if(config('app.maintenance_start_time'))
                <div class="maintenance-info-row"><span>Started</span><span>{{ config('app.maintenance_start_time') }}</span></div>
                @endif
                @if(config('app.maintenance_end_time'))
                <div class="maintenance-info-row"><span>ETA</span><span>{{ config('app.maintenance_end_time') }}</span></div>
                @endif
                @if(config('app.maintenance_contact_email'))
                <div class="maintenance-info-row"><span>Support</span><span>{{ config('app.maintenance_contact_email') }}</span></div>
                @endif
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="btn-group">
                @if($adminToken)
                    @if($isMaintenance)
                    <button class="btn btn-success" onclick="toggleMaintenance(false)" id="disableBtn">
                        🟢 Disable Maintenance
                    </button>
                    @else
                    <button class="btn btn-danger" onclick="toggleMaintenance(true)" id="enableBtn">
                        🔴 Enable Maintenance
                    </button>
                    @endif
                    <a href="/admin-maintenance?token={{ $adminToken }}" class="btn btn-primary">🔧 Admin Panel</a>
                @endif
                <a href="/" class="btn btn-secondary">🏠 Home</a>
            </div>

            {{-- JSON Toggle --}}
            <div class="json-section">
                <button class="json-toggle" onclick="toggleJson()">Show Raw JSON</button>
                <pre id="jsonOutput">{{ json_encode([
                    'status' => $status ?? ($isMaintenance ? 'maintenance' : 'ok'),
                    'maintenance' => $isMaintenance,
                    'timestamp' => $timestamp ?? now()->toISOString(),
                    'version' => $version ?? config('app.version', '1.0.0'),
                    'message' => $message ?? '',
                    'maintenance_type' => config('app.maintenance_type'),
                    'maintenance_progress' => config('app.maintenance_progress'),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        const adminToken = '{{ $adminToken ?? '' }}';

        function toggleJson() {
            const pre = document.getElementById('jsonOutput');
            const btn = document.querySelector('.json-toggle');
            pre.classList.toggle('show');
            btn.textContent = pre.classList.contains('show') ? 'Hide Raw JSON' : 'Show Raw JSON';
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = 'toast toast-' + type + ' show';
            setTimeout(() => t.classList.remove('show'), 3500);
        }

        async function toggleMaintenance(enable) {
            const btnId = enable ? 'enableBtn' : 'disableBtn';
            const btn = document.getElementById(btnId);
            if (!btn) return;
            const orig = btn.innerHTML;
            btn.innerHTML = '<span class="spinner"></span> Processing...';
            btn.disabled = true;

            try {
                const res = await fetch(`/admin-maintenance/toggle?token=${encodeURIComponent(adminToken)}&maintenance=${enable}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    showToast(enable ? '🔴 Maintenance enabled! Reloading...' : '🟢 Maintenance disabled! Reloading...');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Failed', 'error');
                    btn.innerHTML = orig;
                    btn.disabled = false;
                }
            } catch (e) {
                showToast('Error: ' + e.message, 'error');
                btn.innerHTML = orig;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
