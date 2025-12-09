@extends('layouts.app')

@section('title', 'Email Preview - Password Reset')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.password-resets.index') }}">Password Resets</a></li>
            <li class="breadcrumb-item active">Email Preview</li>
        </ol>
    </nav>

    <!-- Email Preview -->
    <div class="card card-shadcn mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Email Preview</h5>
            <div class="border rounded p-4" style="background-color: #f8f9fa;">
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px;">
                    <h2 style="color: #3b82f6; margin-bottom: 20px;">Password Reset - Money Management App</h2>

                    <p>Dear <strong>{{ $passwordResetRequest->user->name ?? 'User' }}</strong>,</p>

                    <p>Your password reset request has been processed by our admin team.</p>

                    <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0;">
                        <p style="margin: 0;"><strong>Here are your new login credentials:</strong></p>
                        <p style="margin: 5px 0;">Email: {{ $passwordResetRequest->email }}</p>
                        <p style="margin: 5px 0;">New Password: <span style="font-family: monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">{{ $request->new_password }}</span></p>
                    </div>

                    <p>You can now login to your Money Management App account at: <a href="{{ route('login') }}" style="color: #3b82f6;">{{ route('login') }}</a></p>

                    <p><em>For security reasons, we recommend changing your password after logging in.</em></p>

                    @if($request->admin_notes)
                        <p><strong>Admin Notes:</strong> {{ $request->admin_notes }}</p>
                    @endif

                    <hr style="margin: 20px 0; border-top: 1px solid #e5e7eb;">

                    <p style="font-size: 12px; color: #6b7280;">If you did not request this password reset, please contact our support team immediately.</p>

                    <p style="margin-bottom: 0;">Best regards,<br>
                    <strong>{{ auth()->user()->name }}</strong><br>
                    Admin - Money Management App</p>
                </div>
            </div>

            <div class="mt-3">
                <p class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    This is how the email will appear to the user. Click "Send Email" to deliver this message.
                </p>
            </div>
        </div>
    </div>

    <!-- Send Email Form -->
    <div class="card card-shadcn">
        <div class="card-body">
            <h5 class="card-title mb-4">Send Email to User</h5>

            <form method="POST" action="{{ route('admin.password-resets.send-email', $passwordResetRequest) }}">
                @csrf
                <input type="hidden" name="new_password" value="{{ $request->new_password }}">
                <input type="hidden" name="admin_notes" value="{{ $request->admin_notes }}">

                <div class="mb-3">
                    <label class="form-label">Recipient Email</label>
                    <input type="email" class="form-control" value="{{ $passwordResetRequest->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password (for display)</label>
                    <div class="input-group">
                        <input type="password" class="form-control" value="{{ $request->new_password }}" readonly>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility(this)" title="Show/Hide">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                @if($request->admin_notes)
                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea class="form-control" rows="3" readonly>{{ $request->admin_notes }}</textarea>
                    </div>
                @endif

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Important:</h6>
                    <ul class="mb-0">
                        <li>The user's password in the database will be updated to: <strong>{{ $request->new_password }}</strong></li>
                        <li>This email will be sent to: <strong>{{ $passwordResetRequest->email }}</strong></li>
                        <li>Make sure the email address is correct before sending</li>
                        <li>Once sent, the request status will be marked as "Completed"</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success" id="sendEmailBtn">
                        <i class="fas fa-paper-plane me-2"></i>Send Email
                    </button>
                    <a href="{{ route('admin.password-resets.edit', $passwordResetRequest) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Edit
                    </a>
                    <a href="{{ route('admin.password-resets.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(button) {
    const input = button.previousElementSibling;
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Prevent double submission
document.getElementById('sendEmailBtn')?.addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
});
</script>
@endsection