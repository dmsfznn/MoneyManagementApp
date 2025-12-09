@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<style>
    :root {
        --primary-blue: #3b82f6;
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --card-bg: rgba(255, 255, 255, 0.98);
    }

    .reset-password-container {
        min-height: 100vh;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .reset-password-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        margin: 0 20px;
    }

    .card-header-left {
        background: var(--primary-gradient);
        color: white;
        padding: 3rem;
        text-align: center;
    }

    .card-body-right {
        padding: 3rem;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        padding: 12px 16px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }

    .back-to-login {
        text-align: center;
        margin-top: 1.5rem;
    }

    .back-to-login a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .back-to-login a:hover {
        text-decoration: underline;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        border-radius: 2px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .password-requirements {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .reset-password-card {
            margin: 0 15px;
        }

        .card-header-left, .card-body-right {
            padding: 2rem;
        }
    }
</style>

<div class="reset-password-container">
    <div class="reset-password-card">
        <!-- Header Section -->
        <div class="card-header-left">
            <div class="text-center mb-4">
                <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-lock fa-2x"></i>
                </div>
                <h2 class="mb-2">Reset Password</h2>
                <p class="mb-0 opacity-90">Create your new secure password</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card-body-right">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-envelope text-primary"></i>
                        </span>
                        <input id="email" type="email"
                               class="form-control border-start-0 @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ $email ?? old('email') }}"
                               placeholder="Enter your email address"
                               required
                               autocomplete="email">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-medium">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-lock text-primary"></i>
                        </span>
                        <input id="password" type="password"
                               class="form-control border-start-0 @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Enter new password"
                               required
                               autocomplete="new-password"
                               onkeyup="checkPasswordStrength(this.value)">
                        <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="password-strength-bar"></div>
                    </div>
                    <div class="password-requirements">
                        Password must be at least 8 characters
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="form-label fw-medium">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-lock text-primary"></i>
                        </span>
                        <input id="password-confirm" type="password"
                               class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation"
                               placeholder="Confirm new password"
                               required
                               autocomplete="new-password"
                               onkeyup="checkPasswordMatch()">
                        <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('password-confirm')">
                            <i class="fas fa-eye" id="password-confirm-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div id="password-match-message" class="text-danger small mt-1" style="display: none;">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Passwords do not match
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-key me-2"></i>
                        Reset Password
                    </button>
                </div>
            </form>

            <div class="back-to-login">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');

    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('password-strength-bar');
    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;

    const width = (strength / 5) * 100;
    strengthBar.style.width = width + '%';

    if (strength <= 2) {
        strengthBar.style.backgroundColor = '#ef4444';
    } else if (strength <= 3) {
        strengthBar.style.backgroundColor = '#f59e0b';
    } else {
        strengthBar.style.backgroundColor = '#10b981';
    }

    checkPasswordMatch();
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password-confirm').value;
    const matchMessage = document.getElementById('password-match-message');

    if (confirmPassword && password !== confirmPassword) {
        matchMessage.style.display = 'block';
    } else {
        matchMessage.style.display = 'none';
    }
}
</script>
@endsection