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
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 12px 15px;
            padding-left: 45px;
            /* Space for icon */
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
            padding: 0;
        }

        .form-floating {
            position: relative;
        }

        .btn-primary {
            background: #4f46e5;
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
        }

        .auth-title {
            font-family: 'Outfit', sans-serif;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            margin-left: 5px;
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        a {
            color: white;
            text-decoration: none;
            position: relative;
        }

        a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: white;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }

        a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
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
    </style>
</head>

<body>
    <!-- Decorative Shapes -->
    <div class="floating-shape" style="width: 300px; height: 300px; top: -100px; left: -100px;"></div>
    <div class="floating-shape" style="width: 200px; height: 200px; bottom: 50px; right: -50px; animation-delay: -5s;">
    </div>

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="glass-card p-4 p-md-5" style="max-width: 450px; width: 100%;">
            <div class="text-center mb-4">
                <img src="/vnnovate.png" alt="Logo" width="70" class="mb-3 rounded-circle shadow-sm">
                <h3 class="auth-title fw-bold">Welcome Back</h3>
                <p class="text-muted">Sign in to continue to Vnnovate</p>
            </div>

            <form method="POST" action="{{ route('auth.stores') }}">
                @csrf
                @method('POST')

                <div class="mb-4 position-relative">
                    <label for="email" class="form-label fw-medium">Email Address</label>
                    <div class="position-relative">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" id="email"
                            placeholder="name@example.com" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <small class="text-warning mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4 position-relative">
                    <label for="password" class="form-label fw-medium">Password</label>
                    <div class="position-relative">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Enter your password">
                    </div>
                    @error('password')
                        <small class="text-warning mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-muted mb-0">
                        Don't have an account? <a href="{{ route('auth.register') }}" class="fw-bold ms-1">Register
                            Now</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    @include('layout.script')
</body>

</html>
