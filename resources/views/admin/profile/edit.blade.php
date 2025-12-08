@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit mr-2"></i>Edit Profile
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Change Password</h6>
                    <p class="text-muted small mb-3">Leave these fields empty if you don't want to change your password.</p>

                    <div class="form-group mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Required only if changing password</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimum 6 characters</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Security Note:</strong> For your security, please enter your current password when changing your password.
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Profile
                        </button>
                        <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPassword = document.getElementById('current_password');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    // Enable/disable password fields based on current password
    function togglePasswordFields() {
        const hasCurrentPassword = currentPassword.value.length > 0;
        const hasNewPassword = password.value.length > 0 || passwordConfirmation.value.length > 0;

        if (hasNewPassword && !hasCurrentPassword) {
            currentPassword.setAttribute('required', 'required');
        } else {
            currentPassword.removeAttribute('required');
        }
    }

    currentPassword.addEventListener('input', togglePasswordFields);
    password.addEventListener('input', togglePasswordFields);
    passwordConfirmation.addEventListener('input', togglePasswordFields);
});
</script>
@endsection