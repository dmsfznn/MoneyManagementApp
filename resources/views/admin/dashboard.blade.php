@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Users</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $totalUsers }}</span>
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
        <div class="card card-stats success">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Admin Users</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $totalAdmins }}</span>
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
        <div class="card card-stats warning">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Regular Users</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $totalRegularUsers }}</span>
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
        <div class="card card-stats danger">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">New This Month</h5>
                        <span class="h2 font-weight-bold mb-0">0</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-users mr-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success btn-block">
                            <i class="fas fa-user-plus mr-2"></i>Add User
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-warning btn-block" disabled>
                            <i class="fas fa-cog mr-2"></i>Settings
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-info btn-block" disabled>
                            <i class="fas fa-database mr-2"></i>Database
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">No recent activity to display.</p>
            </div>
        </div>
    </div>
</div>
@endsection