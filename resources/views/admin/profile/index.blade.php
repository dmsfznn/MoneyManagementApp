@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle mr-2"></i>Admin Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-1">
                            <i class="fas fa-envelope mr-2"></i>{{ $user->email }}
                        </p>
                        <p class="mb-1">
                            <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar mr-2"></i>Member since {{ $user->created_at->format('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary">
                                    @php
                                        $totalUsers = App\Models\User::count();
                                    @endphp
                                    {{ $totalUsers }}
                                </h5>
                                <p class="card-text">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title text-success">
                                    @php
                                        $totalIncome = $user->incomes()->sum('amount');
                                        $totalExpense = $user->expenses()->sum('amount');
                                    @endphp
                                    Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                                </h5>
                                <p class="card-text">Total Balance</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection