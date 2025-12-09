@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadcn bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-1">Admin Dashboard</h1>
                            <p class="mb-0 opacity-90">Welcome back, {{ auth()->user()->name }}! Here's your system overview.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="text-white-50 mb-0">Current Date</h5>
                            <h4 class="mb-0">{{ now()->format('F j, Y') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-primary border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Users</h5>
                            <h3 class="text-primary mb-0">{{ $totalUsers }}</h3>
                            <small class="text-muted">All registered users</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
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
                            <h5 class="card-title text-muted mb-0">Admin Users</h5>
                            <h3 class="text-success mb-0">{{ $totalAdmins }}</h3>
                            <small class="text-muted">System administrators</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Regular Users</h5>
                            <h3 class="text-warning mb-0">{{ $totalRegularUsers }}</h3>
                            <small class="text-muted">Standard users</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-user"></i>
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
                            <h5 class="card-title text-muted mb-0">New This Month</h5>
                            <h3 class="text-info mb-0">{{ $newThisMonth }}</h3>
                            <small class="text-muted">Recent registrations</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row mb-4">
        <!-- Quick Actions -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                        <span class="badge bg-primary">Admin Panel</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-users"></i>
                                <span>Manage Users</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.create') }}"
                               class="btn btn-outline-success btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-user-plus"></i>
                                <span>Add User</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.profile.index') }}"
                               class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-user-circle"></i>
                                <span>Profile</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center gap-2 w-100"
                                    disabled>
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Recent Activity</h5>
                        <span class="badge bg-primary">Live</span>
                    </div>
                    @if($recentActivities->count() > 0)
                        <div class="activity-list">
                            @foreach($recentActivities as $activity)
                                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                    <div class="icon icon-shape bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }} rounded-circle me-3 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="{{ $activity['icon'] }} fa-sm"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 small fw-medium">{{ $activity['description'] }}</p>
                                        @if(isset($activity['amount']))
                                            <p class="mb-1 text-{{ $activity['color'] }} fw-bold">Rp {{ number_format($activity['amount'], 0, ',', '.') }}</p>
                                        @endif
                                        <p class="mb-0 text-muted" style="font-size: 0.75rem;">
                                            {{ $activity['created_at']->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary btn-sm">View All Activities</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No recent activity</h6>
                            <p class="text-muted small">System activities will appear here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Recent Registrations</h5>
                        <span class="badge bg-success">Last 30 Days</span>
                    </div>
                    @if($recentRegistrations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRegistrations as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon icon-shape bg-primary bg-opacity-10 text-primary rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user fa-sm"></i>
                                                    </div>
                                                    <strong>{{ $user->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </td>
                                            <td>
                                                @if($user->role == 'admin')
                                                    <span class="badge bg-danger">Admin</span>
                                                @else
                                                    <span class="badge bg-primary">User</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($recentRegistrations->count() >= 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">View All Users</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No recent registrations</h6>
                            <p class="text-muted small">No users have registered in the last 30 days</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">System Overview</h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active">Today</button>
                            <button class="btn btn-outline-primary">Week</button>
                            <button class="btn btn-outline-primary">Month</button>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-server fa-2x text-primary mb-2"></i>
                                <h6 class="mb-1">Server Status</h6>
                                <span class="badge bg-success">Online</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-database fa-2x text-info mb-2"></i>
                                <h6 class="mb-1">Database</h6>
                                <span class="badge bg-success">Healthy</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-memory fa-2x text-warning mb-2"></i>
                                <h6 class="mb-1">Memory</h6>
                                <span class="badge bg-warning">Moderate</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-hdd fa-2x text-secondary mb-2"></i>
                                <h6 class="mb-1">Storage</h6>
                                <span class="badge bg-success">Available</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection