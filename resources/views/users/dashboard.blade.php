@include('navbar.header')

<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.header')
    <style>
        body {
            overflow: hidden;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: transform 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
        }

        .welcome-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.5px;
        }

        .welcome-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.9);
            color: #4f46e5;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 10px;
        }

        .btn-glass:hover {
            background: white;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: #4338ca;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite linear;
            z-index: -1;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(100px, 100px) rotate(360deg);
            }
        }

        .animate-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Decorative Shapes -->
    <div class="floating-shape" style="width: 300px; height: 300px; top: -50px; left: -50px;"></div>
    <div class="floating-shape" style="width: 200px; height: 200px; bottom: 50px; right: -50px; animation-delay: -5s;">
    </div>

    <div class="container-fluid min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card p-5 text-center animate-up">
                <div class="mb-4">
                    <img src="/vnnovate.png" alt="Vnnovate Logo" height="80" class="rounded-circle shadow-sm mb-3">
                </div>

                <h1 class="welcome-title display-4 mb-3">
                    Welcome to Vnnovate
                </h1>

                <p class="welcome-text mb-5">
                    Experience the future of innovation. Sign in to access your personalized dashboard or create a new
                    account to get started.
                </p>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('auth.login') }}" class="btn-glass">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </a>
                    <a href="{{ route('auth.register') }}" class="btn-glass">
                        <i class="bi bi-person-plus me-2"></i> Register
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('layout.script')
</body>

</html>
