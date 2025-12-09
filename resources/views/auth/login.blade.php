<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Money Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #3b82f6;
            --primary-blue-dark: #2563eb;
            --primary-blue-light: #60a5fa;
            --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            --secondary-gradient: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            --accent-blue: #dbeafe;
            --accent-blue-dark: #1e40af;
            --dark-bg: #1e293b;
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-light: #f1f5f9;
            --border-color: #e2e8f0;
            --border-color-light: #cbd5e1;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.08);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.1);
            --shadow-blue: 0 10px 30px rgba(59, 130, 246, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 50%, #dbeafe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(96, 165, 250, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(29, 78, 216, 0.03) 0%, transparent 50%);
            z-index: 1;
        }

        /* Animated Background Elements */
        .bg-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 2;
        }

        .bg-animation span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(59, 130, 246, 0.08);
            border-radius: 50%;
            animation: move 25s linear infinite;
            bottom: -150px;
        }

        .bg-animation span:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }

        .bg-animation span:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .bg-animation span:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }

        .bg-animation span:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .bg-animation span:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }

        .bg-animation span:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }

        .bg-animation span:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }

        .bg-animation span:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }

        .bg-animation span:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }

        .bg-animation span:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }

        @keyframes move {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1200px;
            display: flex;
            background: var(--card-bg);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-blue);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            min-height: 600px;
        }

        .login-left {
            flex: 1;
            background: var(--primary-gradient);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.1) 0%, transparent 60%);
            animation: float 12s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, -20px) rotate(180deg); }
        }

        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .app-logo {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 35px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            position: relative;
            z-index: 2;
            box-shadow: 0 8px 32px rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }

        .app-logo:hover {
            transform: scale(1.05);
        }

        .app-logo i {
            font-size: 36px;
            color: white;
        }

        .app-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .app-subtitle {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 300px;
            position: relative;
            z-index: 2;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: var(--text-secondary);
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
            display: block;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8fafc;
            color: var(--text-primary);
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
            background: white;
        }

        .form-control.is-invalid {
            border-color: #f56565;
            background: #fff5f5;
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 42px;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: var(--primary-blue);
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            margin-right: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .form-check-label {
            color: var(--text-primary);
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .forgot-password {
            text-align: center;
            margin-top: 24px;
        }

        .forgot-password a {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--primary-blue-dark);
            text-decoration: underline;
        }

        .invalid-feedback {
            color: #f56565;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee;
            color: #c53030;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 30px;
            }

            .login-container {
                max-width: 450px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .login-right {
                padding: 30px 20px;
            }

            .form-title {
                font-size: 24px;
            }

            .app-title {
                font-size: 28px;
            }
        }

        /* Loading Spinner */
        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            opacity: 0.8;
        }

        /* Enhanced Blue Theme Elements */
        .login-container:hover {
            box-shadow: 0 25px 50px rgba(59, 130, 246, 0.2);
        }

        /* Additional subtle animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .app-logo i {
            animation: pulse 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="app-logo">
                <i class="fas fa-wallet"></i>
            </div>
            <h1 class="app-title">Money Management</h1>
            <p class="app-subtitle">Take control of your finances with intelligent budgeting and expense tracking</p>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <h2 class="form-title">Welcome Back</h2>
            <p class="form-subtitle">Sign in to access your account</p>

            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter your email address"
                           required
                           autocomplete="email"
                           autofocus>
                    <i class="fas fa-envelope input-icon"></i>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password"
                           placeholder="Enter your password"
                           required
                           autocomplete="current-password">
                    <i class="fas fa-lock input-icon"></i>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    <span class="spinner"></span>
                    <span class="btn-text">Sign In</span>
                </button>
            </form>

            @if (Route::has('password.request'))
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">
                        <i class="fas fa-question-circle me-1"></i>
                        Forgot your password?
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-login');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        // Remove loading state if validation fails
        document.addEventListener('DOMContentLoaded', function() {
            const hasErrors = document.querySelector('.is-invalid');
            if (hasErrors) {
                const submitBtn = document.querySelector('.btn-login');
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });

        // Add input focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
