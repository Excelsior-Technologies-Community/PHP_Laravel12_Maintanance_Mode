<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Site Under Maintenance</title>

    <!-- Auto Refresh Every 30 Seconds -->
    <meta http-equiv="refresh" content="30">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            width: 90%;
            max-width: 700px;
            text-align: center;
            background: rgba(255, 255, 255, 0.08);
            padding: 50px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 8px solid rgba(255, 255, 255, 0.2);
            border-top: 8px solid #38bdf8;
            border-radius: 50%;
            margin: 0 auto 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.8;
            color: #d1d5db;
            margin-bottom: 15px;
        }

        .info-box {
            margin-top: 20px;
            padding: 20px;
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
        }

        .info-box strong {
            color: #38bdf8;
        }

        .info-box a {
            color: #38bdf8;
            text-decoration: none;
        }

        .info-box a:hover {
            text-decoration: underline;
        }

        #countdown {
            margin-top: 25px;
            font-size: 32px;
            font-weight: bold;
            color: #38bdf8;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #94a3b8;
        }

        @media(max-width:768px) {

            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 36px;
            }

            p {
                font-size: 16px;
            }

            #countdown {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Loader -->
        <div class="loader"></div>

        <!-- Heading -->
        <h1>We'll Be Back Soon!</h1>

        <!-- Dynamic Message -->
        <p>
            {{ config('app.maintenance_message') }}
        </p>

        <!-- Maintenance Info -->
        <p>
            Sorry for the inconvenience. We are currently upgrading our system to serve you better.
        </p>

        <!-- New Features -->
        <div class="info-box">

            <p>
                <strong>Maintenance Started:</strong><br>
                {{ config('app.maintenance_start_time') }}
            </p>

            <p>
                <strong>Expected Completion:</strong><br>
                {{ config('app.maintenance_end_time') }}
            </p>

            <p>
                <strong>Support Email:</strong><br>
                <a href="mailto:{{ config('app.maintenance_contact_email') }}">
                    {{ config('app.maintenance_contact_email') }}
                </a>
            </p>

        </div>

        <!-- Countdown -->
        <div id="countdown"></div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Maintenance Mode Project. All Rights Reserved.
        </div>

    </div>

    <script>
        const endTime = new Date().getTime() + (2 * 60 * 60 * 1000);

        const timer = setInterval(function() {

            const now = new Date().getTime();
            const distance = endTime - now;

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) /
                (1000 * 60 * 60));

            const minutes = Math.floor((distance % (1000 * 60 * 60)) /
                (1000 * 60));

            const seconds = Math.floor((distance % (1000 * 60)) /
                1000);

            document.getElementById("countdown").innerHTML =
                hours + "h " +
                minutes + "m " +
                seconds + "s ";

            if (distance < 0) {

                clearInterval(timer);

                document.getElementById("countdown").innerHTML =
                    "Website is Live Now!";
            }

        }, 1000);
    </script>

</body>

</html>