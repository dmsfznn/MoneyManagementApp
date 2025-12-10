@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card card-shadcn bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">👑 Profil Administrator</h2>
                    <p class="mb-0 opacity-90">Kelola informasi profil admin Anda</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card card-shadcn border-primary border-3">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px; font-size: 48px; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <h4 class="mb-2">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-user-shield me-1"></i>Administrator
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
                            <small class="text-muted">Anggota sejak {{ $user->created_at->format('d F Y') }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-clock text-muted me-3" style="width: 20px;"></i>
                            <small class="text-muted">Aktif terakhir {{ $user->updated_at->format('d F Y, H:i') }}</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                        <a href="{{ route('admin.password-resets.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>Reset Password
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
                    <div class="card card-shadcn border-primary border-3 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h6 class="text-primary mb-0">Total Pengguna</h6>
                                    </div>
                                    <h3 class="mb-0 fw-bold text-primary">
                                        @php
                                            $totalUsers = App\Models\User::count();
                                        @endphp
                                        {{ $totalUsers }}
                                    </h3>
                                    <small class="text-muted">Semua pengguna sistem</small>
                                </div>
                                <div class="text-end">
                                    <i class="fas fa-users text-primary opacity-25 fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card card-shadcn border-success border-3 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <h6 class="text-success mb-0">Total Saldo</h6>
                                    </div>
                                    <h3 class="mb-0 fw-bold text-success">
                                        @php
                                            $totalIncome = $user->incomes()->sum('amount');
                                            $totalExpense = $user->expenses()->sum('amount');
                                        @endphp
                                        Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                                    </h3>
                                    <small class="text-muted">Pemasukan - Pengeluaran</small>
                                </div>
                                <div class="text-end">
                                    <i class="fas fa-wallet text-success opacity-25 fa-2x"></i>
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