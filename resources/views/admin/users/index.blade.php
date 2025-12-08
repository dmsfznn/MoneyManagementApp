@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Management</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Add New User
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
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
    <div class="col-md-4">
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
    <div class="col-md-4">
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
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Users</h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Total Income</th>
                            <th>Total Expenses</th>
                            <th>Balance</th>
                            <th>Joined</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="text-success">
                                    Rp {{ number_format($user->incomes->sum('amount'), 0, ',', '.') }}
                                </td>
                                <td class="text-danger">
                                    Rp {{ number_format($user->expenses->sum('amount'), 0, ',', '.') }}
                                </td>
                                <td class="font-weight-bold {{ $user->incomes->sum('amount') - $user->expenses->sum('amount') >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($user->incomes->sum('amount') - $user->expenses->sum('amount'), 0, ',', '.') }}
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5>No users found</h5>
                <p class="text-muted">Start by creating your first user account.</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add New User
                </a>
            </div>
        @endif
    </div>
</div>
@endsection