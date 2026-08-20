<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Maintenance Manager</title>
    <link rel="icon" type="image/x-icon" href="/favicon-maintenance.svg">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #fff; min-height: 100vh; padding: 20px;
        }
        .container { max-width: 900px; margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        h1 { font-size: 28px; font-weight: 700; }
        .status-badge { display: inline-flex; align-items: center; gap: 10px; padding: 10px 20px; border-radius: 100px; font-size: 13px; font-weight: 600; text-transform: uppercase; }
        .status-active { background: rgba(249,115,22,0.15); color: #fed7aa; border: 1px solid rgba(249,115,22,0.3); }
        .status-inactive { background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3); }
        .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; margin-bottom: 24px; backdrop-filter: blur(20px); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-title { font-size: 18px; font-weight: 600; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 500; color: #94a3b8; margin-bottom: 8px; }
        input, select, textarea { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #fff; font-size: 14px; font-family: inherit; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56,189,248,0.1); }
        input::placeholder, textarea::placeholder { color: #64748b; }
        select option { background: #1e293b; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #0f172a; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(56,189,248,0.4); }
        .btn-danger { background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(249,115,22,0.4); }
        .btn-secondary { background: rgba(255,255,255,0.08); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); }
        .btn-secondary:hover { background: rgba(255,255,255,0.15); }
        .btn-group { display: flex; gap: 12px; flex-wrap: wrap; }
        /* Toggle Switch */
        .toggle-wrapper { display: flex; align-items: center; gap: 16px; }
        .toggle-switch { position: relative; width: 64px; height: 34px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
        .toggle-track { position: absolute; inset: 0; background: rgba(255,255,255,0.1); border-radius: 17px; cursor: pointer; transition: background 0.3s; border: 1px solid rgba(255,255,255,0.15); }
        .toggle-track::before { content: ''; position: absolute; top: 4px; left: 4px; width: 24px; height: 24px; background: #fff; border-radius: 50%; transition: transform 0.3s; box-shadow: 0 2px 6px rgba(0,0,0,0.3); }
        .toggle-switch input:checked + .toggle-track { background: linear-gradient(135deg, #f97316, #ea580c); border-color: rgba(249,115,22,0.4); }
        .toggle-switch input:checked + .toggle-track::before { transform: translateX(30px); }
        .toggle-info { display: flex; flex-direction: column; gap: 4px; }
        .toggle-label { font-size: 15px; font-weight: 600; color: #e2e8f0; }
        .toggle-sublabel { font-size: 12px; color: #64748b; }
        /* Grid */
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94a3b8; font-size: 13px; }
        .info-value { color: #e2e8f0; font-weight: 600; font-family: monospace; font-size: 13px; word-break: break-all; text-align: right; max-width: 60%; }
        .info-value.active { color: #fb923c; }
        .info-value.inactive { color: #4ade80; }
        /* Toast */
        .toast { position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; border-radius: 12px; font-weight: 500; z-index: 1000; transform: translateX(120%); transition: transform 0.3s; max-width: 320px; }
        .toast.show { transform: translateX(0); }
        .toast-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
        /* Spinner */
        .spinner { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.8s linear infinite; display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }
        /* Dot pulse */
        .dot-pulse { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .dot-active { background: #f97316; animation: blink 1.5s infinite; }
        .dot-inactive { background: #22c55e; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.4} }
        /* Nav links */
        .nav-links { display: flex; gap: 12px; align-items: center; }
        .nav-link { color: #64748b; font-size: 13px; text-decoration: none; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); transition: all 0.2s; }
        .nav-link:hover { color: #e2e8f0; background: rgba(255,255,255,0.06); }
        /* Error state */
        .error-state { text-align: center; padding: 40px; color: #ef4444; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } header { flex-direction: column; gap: 16px; text-align: center; } .nav-links { flex-wrap: wrap; justify-content: center; } }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔧 Maintenance Manager</h1>
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <div class="nav-links">
                    <a href="/health?token={{ request()->query('token') }}" class="nav-link">Health Check</a>
                    <a href="/" class="nav-link">Home</a>
                </div>
                <span class="status-badge status-inactive" id="statusBadge">
                    <span class="dot-pulse dot-inactive" id="statusDot"></span>
                    <span id="statusText">Loading...</span>
                </span>
            </div>
        </header>

        {{-- Maintenance Toggle Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Maintenance Mode Control</h2>
            </div>
            <div class="toggle-wrapper">
                <label class="toggle-switch">
                    <input type="checkbox" id="maintenanceToggle" onchange="toggleMaintenance()">
                    <span class="toggle-track"></span>
                </label>
                <div class="toggle-info">
                    <span class="toggle-label" id="toggleLabel">Loading...</span>
                    <span class="toggle-sublabel" id="toggleSublabel">Fetching current status...</span>
                </div>
            </div>
        </div>

        {{-- Configuration Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Configuration</h2>
            </div>
            <div class="grid">
                <div class="form-group">
                    <label>Maintenance Message</label>
                    <textarea id="messageInput" rows="3" placeholder="We are currently performing scheduled maintenance..."></textarea>
                </div>
                <div class="form-group">
                    <label>Maintenance Type</label>
                    <select id="typeInput">
                        <option value="general">General</option>
                        <option value="security">Security Update</option>
                        <option value="feature">Feature Deploy</option>
                        <option value="bugfix">Bug Fixes</option>
                        <option value="deploy">Deployment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Progress (%)</label>
                    <input type="number" id="progressInput" min="0" max="100" value="0">
                </div>
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="datetime-local" id="startTimeInput">
                </div>
                <div class="form-group">
                    <label>End Time (ETA)</label>
                    <input type="datetime-local" id="endTimeInput">
                </div>
                <div class="form-group">
                    <label>Support Email</label>
                    <input type="email" id="emailInput" placeholder="support@example.com">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Status Updates (JSON Array)</label>
                    <textarea id="updatesInput" rows="5" placeholder='[{"time": "10:15", "type": "info", "message": "Database migration started"}]'></textarea>
                </div>
            </div>
            <div class="btn-group">
                <button class="btn btn-primary" id="saveBtn" onclick="saveConfig()">💾 Save Configuration</button>
                <button class="btn btn-secondary" onclick="loadConfig()">🔄 Reload</button>
            </div>
        </div>

        {{-- Current Status Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Current Status</h2>
            </div>
            <div class="grid">
                <div>
                    <div class="info-row"><span class="info-label">Maintenance Mode</span><span class="info-value" id="currentStatus">-</span></div>
                    <div class="info-row"><span class="info-label">Message</span><span class="info-value" id="currentMessage">-</span></div>
                    <div class="info-row"><span class="info-label">Type</span><span class="info-value" id="currentType">-</span></div>
                    <div class="info-row"><span class="info-label">Progress</span><span class="info-value" id="currentProgress">-</span></div>
                </div>
                <div>
                    <div class="info-row"><span class="info-label">Start Time</span><span class="info-value" id="currentStart">-</span></div>
                    <div class="info-row"><span class="info-label">End Time</span><span class="info-value" id="currentEnd">-</span></div>
                    <div class="info-row"><span class="info-label">Support Email</span><span class="info-value" id="currentEmail">-</span></div>
                    <div class="info-row"><span class="info-label">Last Updated</span><span class="info-value" id="lastUpdated">-</span></div>
                </div>
            </div>
        </div>

        {{-- Quick Actions Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="btn-group">
                <button class="btn btn-danger" onclick="quickEnable()">🔴 Enable Maintenance Now</button>
                <button class="btn btn-primary" onclick="quickDisable()">🟢 Disable Maintenance Now</button>
                <a href="/health" target="_blank" class="btn btn-secondary">📊 View Health Page</a>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        const API_BASE = '/admin-maintenance';
        const adminToken = new URLSearchParams(window.location.search).get('token') || '';

        if (!adminToken) {
            document.querySelector('.container').innerHTML = '<div class="error-state"><h2>⚠️ Access Denied</h2><p style="margin-top:12px;color:#94a3b8;">No admin token provided. Add <code>?token=YOUR_TOKEN</code> to the URL.</p></div>';
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + (type === 'success' ? 'toast-success' : 'toast-error') + ' show';
            setTimeout(() => toast.classList.remove('show'), 3500);
        }

        async function fetchStatus() {
            try {
                const res = await fetch(`${API_BASE}/status?token=${encodeURIComponent(adminToken)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    showToast(err.message || 'Failed to fetch status', 'error');
                    return;
                }
                const data = await res.json();
                updateUI(data);
            } catch (e) {
                showToast('Network error: ' + e.message, 'error');
            }
        }

        function updateUI(data) {
            const isMaintenance = data.maintenance;

            // Header badge
            const badge = document.getElementById('statusBadge');
            const dot = document.getElementById('statusDot');
            const statusText = document.getElementById('statusText');
            badge.className = 'status-badge ' + (isMaintenance ? 'status-active' : 'status-inactive');
            dot.className = 'dot-pulse ' + (isMaintenance ? 'dot-active' : 'dot-inactive');
            statusText.textContent = isMaintenance ? 'Maintenance Active' : 'Site Online';

            // Toggle
            const toggle = document.getElementById('maintenanceToggle');
            toggle.checked = isMaintenance;
            document.getElementById('toggleLabel').textContent = isMaintenance ? '🔴 Maintenance is ON' : '🟢 Maintenance is OFF';
            document.getElementById('toggleSublabel').textContent = isMaintenance
                ? 'Click toggle to bring site back online'
                : 'Click toggle to put site in maintenance mode';

            // Status info
            const statusEl = document.getElementById('currentStatus');
            statusEl.textContent = isMaintenance ? 'ACTIVE' : 'INACTIVE';
            statusEl.className = 'info-value ' + (isMaintenance ? 'active' : 'inactive');

            document.getElementById('currentMessage').textContent = data.message || '-';
            document.getElementById('currentType').textContent = data.type || '-';
            document.getElementById('currentProgress').textContent = (data.progress ?? 0) + '%';
            document.getElementById('currentStart').textContent = data.start_time || '-';
            document.getElementById('currentEnd').textContent = data.end_time || '-';
            document.getElementById('currentEmail').textContent = data.contact_email || '-';
            document.getElementById('lastUpdated').textContent = data.updated_at ? new Date(data.updated_at).toLocaleString() : '-';

            // Fill form fields
            document.getElementById('messageInput').value = data.message || '';
            document.getElementById('typeInput').value = data.type || 'general';
            document.getElementById('progressInput').value = data.progress ?? 0;
            document.getElementById('startTimeInput').value = data.start_time ? data.start_time.replace(' ', 'T').substring(0, 16) : '';
            document.getElementById('endTimeInput').value = data.end_time ? data.end_time.replace(' ', 'T').substring(0, 16) : '';
            document.getElementById('emailInput').value = data.contact_email || '';
            document.getElementById('updatesInput').value = data.updates && data.updates.length ? JSON.stringify(data.updates, null, 2) : '';
        }

        async function toggleMaintenance() {
            const toggle = document.getElementById('maintenanceToggle');
            const isMaintenance = toggle.checked;

            // Disable toggle during request
            toggle.disabled = true;
            document.getElementById('toggleLabel').textContent = '⏳ Processing...';

            try {
                const res = await fetch(`${API_BASE}/toggle?token=${encodeURIComponent(adminToken)}&maintenance=${isMaintenance}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    showToast(isMaintenance ? '🔴 Maintenance mode ENABLED' : '🟢 Maintenance mode DISABLED');
                    await fetchStatus();
                } else {
                    toggle.checked = !isMaintenance;
                    showToast(data.message || 'Failed to toggle maintenance', 'error');
                    await fetchStatus();
                }
            } catch (e) {
                toggle.checked = !isMaintenance;
                showToast('Error: ' + e.message, 'error');
                await fetchStatus();
            } finally {
                toggle.disabled = false;
            }
        }

        async function quickEnable() {
            const toggle = document.getElementById('maintenanceToggle');
            if (toggle.checked) { showToast('Maintenance is already active', 'error'); return; }
            toggle.checked = true;
            await toggleMaintenance();
        }

        async function quickDisable() {
            const toggle = document.getElementById('maintenanceToggle');
            if (!toggle.checked) { showToast('Maintenance is already inactive', 'error'); return; }
            toggle.checked = false;
            await toggleMaintenance();
        }

        async function saveConfig() {
            const btn = document.getElementById('saveBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner"></span> Saving...';
            btn.disabled = true;

            let updates = [];
            const updatesRaw = document.getElementById('updatesInput').value.trim();
            if (updatesRaw) {
                try { updates = JSON.parse(updatesRaw); }
                catch (e) { showToast('Invalid JSON in Status Updates field', 'error'); btn.innerHTML = originalText; btn.disabled = false; return; }
            }

            const payload = {
                message: document.getElementById('messageInput').value,
                type: document.getElementById('typeInput').value,
                progress: parseInt(document.getElementById('progressInput').value) || 0,
                start_time: document.getElementById('startTimeInput').value.replace('T', ' ') || null,
                end_time: document.getElementById('endTimeInput').value.replace('T', ' ') || null,
                contact_email: document.getElementById('emailInput').value,
                updates: updates
            };

            try {
                const res = await fetch(`${API_BASE}/config?token=${encodeURIComponent(adminToken)}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast('✅ Configuration saved successfully');
                    await fetchStatus();
                } else {
                    showToast(data.message || 'Failed to save configuration', 'error');
                }
            } catch (e) {
                showToast('Error: ' + e.message, 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        function loadConfig() {
            fetchStatus();
            showToast('🔄 Status refreshed');
        }

        // Auto-refresh every 30 seconds
        fetchStatus();
        setInterval(fetchStatus, 30000);
    </script>
</body>
</html>
