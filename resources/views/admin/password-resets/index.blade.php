@extends('layouts.app')

@section('title', 'Password Reset Management')

@section('content')
<div class="container mt-4">
    <!-- Password Reset Success Alert -->
    @if(session('new_password') && session('user_email') && session('user_name'))
        <div class="alert alert-success alert-dismissible border-start-4 border-success border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-3">
                        <i class="fas fa-check-circle me-2"></i>Password Berhasil Diubah!
                    </h5>
                    <p class="mb-3">Password untuk <strong>{{ session('user_name') }}</strong> ({{ session('user_email') }}) telah direset.</p>

                    <!-- Password Display -->
                    <div class="bg-light rounded p-3 mb-3">
                        <p class="mb-1"><strong>Password Baru:</strong></p>
                        <div class="d-flex align-items-center justify-content-between">
                            <code class="fs-5 text-primary fw-bold" id="new-password-display">{{ session('new_password') }}</code>
                            <button class="btn btn-sm btn-outline-secondary" onclick="copyPassword()">
                                <i class="fas fa-copy me-1"></i>Copy
                            </button>
                        </div>
                    </div>

                    <!-- Email Template -->
                    <div class="border-top pt-3">
                        <p class="mb-2"><strong>Template Email untuk Gmail:</strong></p>
                        <div class="bg-white border rounded p-3 mb-2">
                            <div class="mb-2">
                                <strong>Subject:</strong><br>
                                <code>Password Reset - Money Management App</code>
                            </div>
                            <div>
                                <strong>Message:</strong><br>
                                <div class="border rounded p-2 bg-light" id="email-template">
                                    <p>Dear <strong>{{ session('user_name') }},</strong></p>
                                    <p>Password Anda untuk aplikasi Money Management App telah direset oleh admin.</p>
                                    <div class="bg-white border rounded p-2 mb-2">
                                        <p class="mb-1"><strong>Login Information:</strong></p>
                                        <p class="mb-1"><strong>Email:</strong> {{ session('user_email') }}</p>
                                        <p class="mb-0"><strong>Password Baru:</strong> <span class="text-primary fw-bold">{{ session('new_password') }}</span></p>
                                    </div>
                                    <p>Anda dapat login dengan password baru tersebut. Untuk keamanan, disarankan untuk mengubah password setelah login.</p>
                                    <p>Terima kasih,</p>
                                    <p>Admin - Money Management App</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary" onclick="copyEmailTemplate()">
                                <i class="fas fa-copy me-1"></i>Copy Template
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="openGmail()">
                                <i class="fas fa-envelope me-1"></i>Buka Gmail
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <script>
        function copyPassword() {
            const passwordText = document.getElementById('new-password-display').textContent;
            navigator.clipboard.writeText(passwordText).then(() => {
                alert('Password berhasil disalin!');
            });
        }

        function copyEmailTemplate() {
            const subject = 'Password Reset - Money Management App';
            const message = `Dear {{ session('user_name') }},

Password Anda untuk aplikasi Money Management App telah direset oleh admin.

Anda dapat login dengan informasi berikut:
Email: {{ session('user_email') }}
Password Baru: {{ session('new_password') }}

Anda dapat login dengan password baru tersebut. Untuk keamanan, disarankan untuk mengubah password setelah login.

Terima kasih,
Admin - Money Management App`;

            const fullTemplate = `Subject: ${subject}\n\n${message}`;
            navigator.clipboard.writeText(fullTemplate).then(() => {
                alert('Template email berhasil disalin! Anda bisa paste di Gmail.');
            });
        }

        function openGmail() {
            const subject = encodeURIComponent('Password Reset - Money Management App');
            const body = encodeURIComponent(`Dear {{ session('user_name') }},

Password Anda untuk aplikasi Money Management App telah direset oleh admin.

Anda dapat login dengan informasi berikut:
Email: {{ session('user_email') }}
Password Baru: {{ session('new_password') }}

Anda dapat login dengan password baru tersebut. Untuk keamanan, disarankan untuk mengubah password setelah login.

Terima kasih,
Admin - Money Management App`);

            window.open(`https://mail.google.com/mail/?view=cm&fs=1&to={{ session('user_email') }}&su=${subject}&body=${body}`, '_blank');
        }
        </script>
    @endif

    <!-- Header Section -->
    <div class="card card-shadcn bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Password Reset Management</h2>
                    <p class="mb-0 opacity-90">Manage and process user password reset requests</p>
                </div>
                <div>
                    <a href="{{ route('admin.password-resets.statistics') }}" class="btn btn-light">
                        <i class="fas fa-chart-bar me-1"></i> Statistics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Pending Requests</h5>
                            <h3 class="text-warning mb-0">{{ App\Services\PasswordResetService::getPendingCount() }}</h3>
                            <small class="text-muted">Waiting for processing</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-info border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Processing</h5>
                            <h3 class="text-info mb-0">{{ $requests->where('status', 'processing')->count() }}</h3>
                            <small class="text-muted">Currently being processed</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-spinner"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-success border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Completed</h5>
                            <h3 class="text-success mb-0">{{ $requests->where('status', 'completed')->count() }}</h3>
                            <small class="text-muted">Successfully processed</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-primary border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Requests</h5>
                            <h3 class="text-primary mb-0">{{ $requests->total() }}</h3>
                            <small class="text-muted">All time requests</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card card-shadcn">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Password Reset Requests</h5>
                <div class="d-flex gap-2">
                    <!-- Status Filter -->
                    <select class="form-select" id="statusFilter" onchange="filterByStatus(this.value)">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Completed</th>
                                <th>Admin</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        @if($request->user)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user fa-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $request->user->name }}</div>
                                                    <small class="text-muted">{{ $request->user->role }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">User not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $request->email }}</span>
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($request->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $request->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($request->completed_at)
                                            <small>{{ $request->completed_at->format('M d, Y H:i') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->admin_notes)
                                            <span class="badge bg-light text-dark">With notes</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            @if($request->isPending())
                                                <a href="{{ route('admin.password-resets.edit', $request) }}"
                                                   class="btn btn-primary" title="Process Request">
                                                    <i class="fas fa-cog"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.password-resets.cancel', $request) }}"
                                                      style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger" title="Cancel">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($request->status == 'processing')
                                                <a href="{{ route('admin.password-resets.edit', $request) }}"
                                                   class="btn btn-info" title="Continue Processing">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-outline-secondary" disabled title="Request completed">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="icon icon-shape bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-key fa-3x"></i>
                    </div>
                    <h5 class="text-muted">No Password Reset Requests</h5>
                    <p class="text-muted mb-4">There are no password reset requests at this time.</p>
                    <small class="text-muted">New requests will appear here when users submit password reset requests.</small>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}
</script>
@endsection