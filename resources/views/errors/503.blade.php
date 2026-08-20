<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'Site Under Maintenance') }}</title>

    <!-- Maintenance Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ config('app.maintenance_favicon') }}">
    <link rel="shortcut icon" href="{{ config('app.maintenance_favicon') }}">

    <!-- Auto Refresh Every 30 Seconds -->
    <meta http-equiv="refresh" content="30">

    <!-- Retry-After Header for SEO -->
    <meta name="robots" content="noindex, nofollow">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 720px;
            text-align: center;
            background: rgba(255, 255, 255, 0.06);
            padding: 48px 40px;
            border-radius: 24px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.08);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loader */
        .loader {
            width: 72px;
            height: 72px;
            border: 6px solid rgba(56, 189, 248, 0.15);
            border-top: 6px solid #38bdf8;
            border-radius: 50%;
            margin: 0 auto 28px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        /* Maintenance Type Badge */
        .maintenance-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
            animation: fadeIn 0.4s ease-out 0.1s both;
        }

        .badge-security { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge-feature { background: rgba(34, 197, 94, 0.15); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.3); }
        .badge-bugfix { background: rgba(249, 115, 22, 0.15); color: #fed7aa; border: 1px solid rgba(249, 115, 22, 0.3); }
        .badge-general { background: rgba(56, 189, 248, 0.15); color: #bae6fd; border: 1px solid rgba(56, 189, 248, 0.3); }
        .badge-deploy { background: rgba(168, 85, 247, 0.15); color: #e9d5ff; border: 1px solid rgba(168, 85, 247, 0.3); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
            animation: fadeIn 0.4s ease-out 0.2s both;
        }

        .message {
            font-size: 17px;
            line-height: 1.7;
            color: #cbd5e1;
            margin-bottom: 24px;
            animation: fadeIn 0.4s ease-out 0.3s both;
        }

        /* Progress Bar */
        .progress-container {
            margin: 32px 0;
            animation: fadeIn 0.4s ease-out 0.4s both;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .progress-label {
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
        }

        .progress-percent {
            font-size: 14px;
            font-weight: 700;
            color: #38bdf8;
            font-variant-numeric: tabular-nums;
        }

        .progress-bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #38bdf8 0%, #0ea5e9 100%);
            border-radius: 4px;
            transition: width 1s ease-out;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Info Box */
        .info-box {
            margin-top: 32px;
            padding: 24px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            text-align: left;
            animation: fadeIn 0.4s ease-out 0.5s both;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            color: #e2e8f0;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        .info-value strong {
            color: #38bdf8;
        }

        .info-value a {
            color: #38bdf8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .info-value a:hover {
            color: #0ea5e9;
            text-decoration: underline;
        }

        /* ETA Countdown */
        .countdown-container {
            margin-top: 32px;
            animation: fadeIn 0.4s ease-out 0.6s both;
        }

        .countdown-label {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 12px;
            padding: 16px 20px;
            min-width: 70px;
            transition: all 0.2s;
        }

        .countdown-item:hover {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.4);
        }

        .countdown-value {
            font-size: 28px;
            font-weight: 700;
            color: #38bdf8;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .countdown-unit {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
            display: block;
        }

        .countdown-expired {
            font-size: 18px;
            font-weight: 600;
            color: #22c55e;
            padding: 16px 32px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 12px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 0 12px rgba(34, 197, 94, 0); }
        }

        /* Status Updates Feed */
        .status-feed {
            margin-top: 32px;
            animation: fadeIn 0.4s ease-out 0.7s both;
        }

        .status-feed-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .status-feed-title {
            font-size: 14px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-live-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #22c55e;
        }

        .status-live-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .status-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .status-item {
            display: flex;
            gap: 12px;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            text-align: left;
            transition: all 0.2s;
            animation: slideIn 0.3s ease-out backwards;
        }

        .status-item:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .status-time {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .status-content {
            flex: 1;
            min-width: 0;
        }

        .status-message {
            font-size: 13px;
            color: #e2e8f0;
            line-height: 1.5;
        }

        .status-type {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        .status-type.info { background: rgba(56, 189, 248, 0.2); color: #38bdf8; }
        .status-type.success { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
        .status-type.warning { background: rgba(249, 115, 22, 0.2); color: #f97316; }
        .status-type.error { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

        /* Admin Bypass */
        .admin-bypass {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            animation: fadeIn 0.4s ease-out 0.8s both;
        }

        .admin-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .admin-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: #e2e8f0;
        }

        .admin-link svg {
            width: 16px;
            height: 16px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #475569;
        }

        .footer a {
            color: #64748b;
            text-decoration: none;
        }

        .footer a:hover {
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .container {
                padding: 32px 24px;
            }

            h1 {
                font-size: 32px;
            }

            .message {
                font-size: 15px;
            }

            .countdown {
                gap: 10px;
            }

            .countdown-item {
                padding: 12px 16px;
                min-width: 60px;
            }

            .countdown-value {
                font-size: 22px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .info-value {
                max-width: 100%;
                text-align: left;
            }

            .status-item {
                flex-direction: column;
                gap: 6px;
            }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Loader -->
        <div class="loader" aria-hidden="true"></div>

        <!-- Maintenance Type Badge -->
        <div class="maintenance-badge badge-{{ config('app.maintenance_type', 'general') }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span id="maintenanceTypeLabel">{{ ucfirst(config('app.maintenance_type', 'general')) }} Maintenance</span>
        </div>

        <!-- Heading -->
        <h1>We'll Be Back Soon!</h1>

        <!-- Dynamic Message -->
        <p class="message">{{ config('app.maintenance_message') }}</p>

        <!-- Progress Bar -->
        <div class="progress-container" role="progressbar" aria-valuenow="{{ config('app.maintenance_progress', 0) }}" aria-valuemin="0" aria-valuemax="100" aria-label="Maintenance progress">
            <div class="progress-header">
                <span class="progress-label">Maintenance Progress</span>
                <span class="progress-percent">{{ config('app.maintenance_progress', 0) }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ config('app.maintenance_progress', 0) }}%"></div>
            </div>
        </div>

        <!-- Maintenance Info -->
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Started</span>
                <span class="info-value"><strong>{{ config('app.maintenance_start_time') }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Expected Completion</span>
                <span class="info-value"><strong>{{ config('app.maintenance_end_time') }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Support Email</span>
                <span class="info-value">
                    <a href="mailto:{{ config('app.maintenance_contact_email') }}">
                        {{ config('app.maintenance_contact_email') }}
                    </a>
                </span>
            </div>
        </div>

        <!-- ETA Countdown -->
        <div class="countdown-container">
            <div class="countdown-label">Estimated Time Remaining</div>
            <div class="countdown" id="countdown" role="timer" aria-live="polite"></div>
        </div>

        <!-- Status Updates Feed -->
        @php
            $statusUpdatesRaw = config('app.maintenance_status_updates', []);
            $statusUpdates = is_string($statusUpdatesRaw) ? json_decode($statusUpdatesRaw, true) : $statusUpdatesRaw;
            if (!empty($statusUpdates)) {
        @endphp
        <div class="status-feed">
            <div class="status-feed-header">
                <span class="status-feed-title">Status Updates</span>
                <span class="status-live-indicator">
                    <span class="status-live-dot" aria-hidden="true"></span>
                    Live
                </span>
            </div>
            <div class="status-list" role="log" aria-live="polite" aria-label="Maintenance status updates">
                @foreach($statusUpdates as $index => $update)
                    <div class="status-item" style="animation-delay: {{ $index * 100 }}ms;">
                        <span class="status-time">{{ $update['time'] ?? '' }}</span>
                        <div class="status-content">
                            @if(isset($update['type']))
                                <span class="status-type {{ $update['type'] }}">{{ ucfirst($update['type']) }}</span>
                            @endif
                            <div class="status-message">{{ $update['message'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @php } @endphp

        <!-- Admin Bypass Link -->
        @php
            $adminToken = config('app.maintenance_admin_token');
            $bypassUrl = $adminToken ? '/?maintenance_bypass=' . $adminToken : null;
        @endphp
        @if($bypassUrl)
        <div class="admin-bypass">
            <a href="{{ $bypassUrl }}" class="admin-link" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Admin Access
            </a>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Maintenance Mode Project') }}. All Rights Reserved.
        </div>

    </div>

    <script>
        // Configuration from Laravel
        const MAINTENANCE_END_TIME = '{{ config('app.maintenance_end_time') }}';
        const MAINTENANCE_PROGRESS = {{ config('app.maintenance_progress', 0) }};

        // Countdown Timer - Dynamic from maintenance_end_time config
        function initCountdown() {
            const countdownEl = document.getElementById('countdown');
            if (!countdownEl) return;

            let endTime;

            // Try to parse the configured end time
            if (MAINTENANCE_END_TIME) {
                const parsed = Date.parse(MAINTENANCE_END_TIME);
                if (!isNaN(parsed)) {
                    endTime = parsed;
                }
            }

            // Fallback: 2 hours from now if no valid end time
            if (!endTime) {
                endTime = new Date().getTime() + (2 * 60 * 60 * 1000);
            }

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = '<div class="countdown-expired">Website is Live Now! Refreshing...</div>';
                    // Auto-refresh after 5 seconds
                    setTimeout(() => location.reload(), 5000);
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownEl.innerHTML = `
                    <div class="countdown-item">
                        <span class="countdown-value">${hours.toString().padStart(2, '0')}</span>
                        <span class="countdown-unit">Hours</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value">${minutes.toString().padStart(2, '0')}</span>
                        <span class="countdown-unit">Minutes</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value">${seconds.toString().padStart(2, '0')}</span>
                        <span class="countdown-unit">Seconds</span>
                    </div>
                `;
            }, 1000);
        }

        // Animate progress bar on load
        function animateProgressBar() {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                const targetWidth = MAINTENANCE_PROGRESS + '%';
                progressFill.style.width = '0%';
                requestAnimationFrame(() => {
                    progressFill.style.transition = 'width 1.2s ease-out';
                    progressFill.style.width = targetWidth;
                });
            }
        }

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initCountdown();
            animateProgressBar();
        });

        // Handle visibility change - pause/resume animations
        document.addEventListener('visibilitychange', function() {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.animationPlayState = document.hidden ? 'paused' : 'running';
            }
        });
    </script>

</body>

</html>