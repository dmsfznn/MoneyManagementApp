@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card card-shadcn">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px; font-size: 48px; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <h4 class="mb-2">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-user-shield me-1"></i>{{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <hr>

                    <div class="text-start">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-muted me-3" style="width: 20px;"></i>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-calendar text-muted me-3" style="width: 20px;"></i>
                            <small class="text-muted">Member since {{ $user->created_at->format('d F Y') }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-clock text-muted me-3" style="width: 20px;"></i>
                            <small class="text-muted">Last active {{ $user->updated_at->format('d F Y, H:i') }}</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats & Information -->
        <div class="col-md-8">
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card card-shadcn border-primary border-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0">Total Users</h5>
                                    <h3 class="text-primary mb-0">
                                        @php
                                            $totalUsers = App\Models\User::count();
                                        @endphp
                                        {{ $totalUsers }}
                                    </h3>
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
                <div class="col-md-6 mb-3">
                    <div class="card card-shadcn border-success border-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0">Total Balance</h5>
                                    <h3 class="text-success mb-0">
                                        @php
                                            $totalIncome = $user->incomes()->sum('amount');
                                            $totalExpense = $user->expenses()->sum('amount');
                                        @endphp
                                        Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                                    </h3>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div class="card card-shadcn">
                <div class="card-body">
                    <h5 class="card-title mb-4">Account Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Full Name</label>
                                <p class="mb-0 fw-medium">{{ $user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Email Address</label>
                                <p class="mb-0 fw-medium">{{ $user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">User Role</label>
                                <p class="mb-0">
                                    <span class="badge bg-danger">
                                        <i class="fas fa-user-shield me-1"></i>{{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Account Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Member Since</label>
                                <p class="mb-0 fw-medium">{{ $user->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Last Updated</label>
                                <p class="mb-0 fw-medium">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <hr>
                    <h6 class="mb-3">Financial Summary</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Total Income</h6>
                                <h5 class="text-success mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Total Expenses</h6>
                                <h5 class="text-danger mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Net Balance</h6>
                                <h5 class="{{ $totalIncome - $totalExpense >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                                    Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection