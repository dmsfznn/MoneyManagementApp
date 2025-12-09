@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<style>
    :root {
        --primary-blue: #3b82f6;
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --card-bg: rgba(255, 255, 255, 0.98);
    }

    .forgot-password-container {
        min-height: 100vh;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .forgot-password-card {
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

    @media (max-width: 768px) {
        .forgot-password-card {
            margin: 0 15px;
        }

        .card-header-left, .card-body-right {
            padding: 2rem;
        }
    }
</style>

<div class="forgot-password-container">
    <div class="forgot-password-card">
        <!-- Header Section -->
        <div class="card-header-left">
            <div class="text-center mb-4">
                <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-key fa-2x"></i>
                </div>
                <h2 class="mb-2">Forgot Password?</h2>
                <p class="mb-0 opacity-90">Our admin team will help you reset it</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card-body-right">
            @if (session('status'))
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-envelope text-primary"></i>
                        </span>
                        <input id="email" type="email"
                               class="form-control border-start-0 @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Enter your registered email address"
                               required
                               autocomplete="email"
                               autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-paper-plane me-2"></i>
                        Request Password Reset
                    </button>
                </div>
            </form>

            <!-- Information Section -->
            <div class="alert alert-info mt-4">
                <h6><i class="fas fa-info-circle me-2"></i>How Password Reset Works</h6>
                <ol class="mb-0 small">
                    <li>You submit a password reset request with your email</li>
                    <li>Our admin team receives your request immediately</li>
                    <li>Admin will review and process your request</li>
                    <li>You'll receive a new password via email</li>
                    <li>Use the new password to login to your account</li>
                </ol>
            </div>

            <div class="back-to-login">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection