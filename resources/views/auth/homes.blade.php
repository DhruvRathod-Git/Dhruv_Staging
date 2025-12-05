@include('header.navbar')

<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.header')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            min-height: 100vh;
            margin-left: 220px;
            font-family: 'Poppins', sans-serif;
            padding: 80px 20px 40px 20px;
            overflow-x: hidden;
        }

        .main-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            text-align: center;
        }

        .logo-box {
            margin-bottom: 25px;
        }

        .logo-box img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #6a11cb;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .user-name {
            color: #6a11cb;
            font-weight: 700;
        }

        .welcome-message {
            font-size: 16px;
            color: #666;
            line-height: 1.8;
        }

        .welcome-message i {
            color: #6a11cb;
            font-size: 20px;
            margin-right: 8px;
        }

        .welcome-message strong {
            color: #333;
            font-weight: 600;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-box {
            background: white;
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 40px;
            color: #6a11cb;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .stat-text {
            font-size: 13px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .fade-in {
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 {
            animation-delay: 0.2s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .delay-2 {
            animation-delay: 0.4s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .delay-3 {
            animation-delay: 0.6s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        @media (max-width: 768px) {
            body {
                margin-left: 0;
                padding: 20px 15px;
            }

            .welcome-card {
                padding: 30px 20px;
            }

            .welcome-title {
                font-size: 24px;
            }

            .stats-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="welcome-card fade-in">
            <div class="logo-box">
                <img src="/vnnovate.png" alt="Vnnovate Logo">
            </div>

            <h1 class="welcome-title">
                Hello, <span class="user-name">{{ Auth::user()->name }}</span>!
            </h1>

            @if (Auth::user()->role === 'admin')
                <p class="welcome-message">
                    <i class="bi bi-shield-lock-fill"></i>
                    <strong>Admin Panel Access</strong><br>
                    Manage records, add, update, and remove data with complete control.
                </p>
            @else
                <p class="welcome-message">
                    <i class="bi bi-person-badge-fill"></i>
                    <strong>User Dashboard</strong><br>
                    Manage your personal records and track your status seamlessly.
                </p>
            @endif
        </div>

        <div class="stats-row">
            <div class="stat-box fade-in delay-1">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-number">{{ date('d') }}</div>
                <div class="stat-text">{{ date('F Y') }}</div>
            </div>

            <div class="stat-box fade-in delay-2">
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-number" id="currentTime">--:--</div>
                <div class="stat-text">Current Time</div>
            </div>

            <div class="stat-box fade-in delay-3">
                <div class="stat-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="stat-number">{{ ucfirst(Auth::user()->role) }}</div>
                <div class="stat-text">Account Type</div>
            </div>
        </div>
    </div>

    @include('layout.script')

    <script>
        function updateTime() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();

            if (hours < 10) hours = '0' + hours;
            if (minutes < 10) minutes = '0' + minutes;

            document.getElementById('currentTime').textContent = hours + ':' + minutes;
        }

        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>

</html>
