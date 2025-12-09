@extends('layouts.app')

@section('title', 'Process Password Reset Request')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.password-resets.index') }}">Password Resets</a></li>
            <li class="breadcrumb-item active">Process Request #{{ $passwordResetRequest->id }}</li>
        </ol>
    </nav>

    <!-- Request Details Card -->
    <div class="card card-shadcn mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Request Details</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="text-muted small">Request ID</label>
                        <p class="mb-0 fw-medium">#{{ str_pad($passwordResetRequest->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">User</label>
                        @if($passwordResetRequest->user)
                            <p class="mb-0 fw-medium">{{ $passwordResetRequest->user->name }}</p>
                            <small class="text-muted">({{ $passwordResetRequest->user->role }})</small>
                        @else
                            <p class="mb-0 text-warning">User not found in system</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email Address</label>
                        <p class="mb-0 fw-medium">{{ $passwordResetRequest->email }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="text-muted small">Status</label>
                        @if($passwordResetRequest->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($passwordResetRequest->status == 'processing')
                            <span class="badge bg-info">Processing</span>
                        @elseif($passwordResetRequest->status == 'completed')
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Requested At</label>
                        <p class="mb-0 fw-medium">
                            @if($passwordResetRequest->created_at)
                                {{ $passwordResetRequest->created_at->format('F d, Y H:i A') }}
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Request Age</label>
                        <p class="mb-0 fw-medium">
                            @if($passwordResetRequest->created_at)
                                {{ $passwordResetRequest->created_at->diffForHumans() }}
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if($passwordResetRequest->admin_notes)
                <div class="mt-3">
                    <label class="text-muted small">Admin Notes</label>
                    <div class="alert alert-info">{{ $passwordResetRequest->admin_notes }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Password Reset Form -->
    <div class="card card-shadcn">
        <div class="card-body">
            <h5 class="card-title mb-4">Reset Password</h5>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($passwordResetRequest->isCompleted())
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-2"></i>Request Already Completed</h6>
                    <p class="mb-0">This password reset request has already been processed and completed.</p>
                </div>
            @else
                <form method="POST" action="{{ route('admin.password-resets.update', $passwordResetRequest) }}" id="passwordResetForm" onsubmit="console.log('Form submitted!'); return true;">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="new_password" class="form-label fw-medium">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-key text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0" id="new_password"
                                           name="new_password" required minlength="8"
                                           placeholder="Enter new password or generate below">
                                    <button type="button" class="btn btn-outline-secondary border-start-0"
                                            onclick="togglePasswordVisibility('new_password')" title="Show/Hide Password">
                                        <i class="fas fa-eye" id="password-toggle-icon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label fw-medium">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0" id="confirm_password"
                                           name="confirm_password" required minlength="8"
                                           placeholder="Confirm new password">
                                </div>
                                <div class="invalid-feedback">
                                    Passwords do not match.
                                </div>
                                <div class="valid-feedback">
                                    Passwords match.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-success" onclick="generateSecurePassword()">
                                <i class="fas fa-random me-2"></i>Generate Secure Password
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <span class="text-muted small">Strength: </span>
                                <div id="password-strength" class="progress flex-grow-1" style="height: 20px;">
                                    <div id="strength-bar" class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="admin_notes" class="form-label fw-medium">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"
                                  placeholder="Add any notes about this password reset request...">{{ old('admin_notes') }}</textarea>
                        <small class="text-muted">These notes will be visible to other admins</small>
                    </div>

                    <div class="mb-4">
                        <div class="alert alert-info border-start-4 border-info border-0">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Proses Reset Password</h6>
                            <p class="mb-2">Setelah Anda mengklik "Process Request":</p>
                            <ol class="mb-0">
                                <li>Password user akan langsung diubah di sistem</li>
                                <li>Request akan ditandai sebagai selesai</li>
                                <li>Password baru akan ditampilkan di halaman index</li>
                                <li>Anda perlu <strong>menginformasikan password baru secara manual melalui Gmail</strong> kepada user</li>
                            </ol>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>Process Request
                        </button>
                        <a href="{{ route('admin.password-resets.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('password-toggle-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function generateSecurePassword() {
    // Generate secure password with at least 12 characters
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";

    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }

    console.log('Generated password:', password);

    document.getElementById('new_password').value = password;
    document.getElementById('confirm_password').value = password;

    // Trigger validation
    checkPasswordStrength(password);
    validatePasswordConfirmation();

    // Show success feedback
    const confirmField = document.getElementById('confirm_password');
    const passwordField = document.getElementById('new_password');
    confirmField.classList.add('is-valid');
    passwordField.classList.add('is-valid');
}

function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('strength-bar');

    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    const strengthPercentage = (strength / 6) * 100;
    strengthBar.style.width = strengthPercentage + '%';

    if (strength <= 2) {
        strengthBar.className = 'progress-bar bg-danger';
    } else if (strength <= 4) {
        strengthBar.className = 'progress-bar bg-warning';
    } else {
        strengthBar.className = 'progress-bar bg-success';
    }
}

// Password strength checker
document.getElementById('new_password').addEventListener('input', function() {
    checkPasswordStrength(this.value);
});

// Confirm password validator
function validatePasswordConfirmation() {
    const password = document.getElementById('new_password').value.trim();
    const confirm = document.getElementById('confirm_password').value.trim();
    const submitBtn = document.getElementById('submitBtn');

    console.log('Password validation check:');
    console.log('Password:', password);
    console.log('Confirm:', confirm);
    console.log('Match:', password === confirm);
    console.log('Confirm empty:', confirm === '');

    // Add visual feedback
    const confirmField = document.getElementById('confirm_password');
    const passwordField = document.getElementById('new_password');

    // Remove existing validation classes
    confirmField.classList.remove('is-invalid', 'is-valid');
    passwordField.classList.remove('is-invalid', 'is-valid');

    if (confirm !== '' && password !== confirm) {
        confirmField.classList.add('is-invalid');
        submitBtn.disabled = true;
        console.log('Passwords do not match - form disabled');
    } else if (confirm !== '' && password === confirm) {
        confirmField.classList.add('is-valid');
        passwordField.classList.add('is-valid');
        submitBtn.disabled = false;
        console.log('Passwords match - form enabled');
    } else if (confirm === '') {
        submitBtn.disabled = false;
        console.log('Confirm password empty - form enabled');
    }
}

document.getElementById('confirm_password').addEventListener('input', validatePasswordConfirmation);
document.getElementById('new_password').addEventListener('input', validatePasswordConfirmation);

// Initial validation on page load
validatePasswordConfirmation();

// Form submission debugging
document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
    console.log('=== FORM SUBMISSION DEBUG ===');
    console.log('Submit button disabled:', document.getElementById('submitBtn').disabled);
    console.log('Form action:', this.action);
    console.log('Form method:', this.method);

    const formData = new FormData(this);
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        if (key !== 'new_password') {
            console.log(key + ':', value);
        } else {
            console.log(key + ': [HIDDEN]');
        }
    }

    console.log('Form will be submitted');
});
</script>
@endsection