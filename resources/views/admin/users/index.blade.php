@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        <div class="col-md-4">
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
    </div>

    <!-- Users Table -->
    <div class="card card-shadcn">
        <div class="card-body">
            <h5 class="card-title mb-4">All Users</h5>
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-shadcn">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-end">Total Income</th>
                                <th class="text-end">Total Expenses</th>
                                <th class="text-end">Balance</th>
                                <th>Joined</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><span class="badge bg-light text-dark">#{{ $user->id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape bg-primary bg-opacity-10 text-primary rounded-circle me-2">
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
                                            <span class="badge bg-danger">
                                                <i class="fas fa-user-shield me-1"></i>{{ $user->role }}
                                            </span>
                                        @else
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user me-1"></i>{{ $user->role }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($user->incomes->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        Rp {{ number_format($user->expenses->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold {{ $user->incomes->sum('amount') - $user->expenses->sum('amount') >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($user->incomes->sum('amount') - $user->expenses->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-outline-info btn-sm"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Delete User">
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No users found</h5>
                    <p class="text-muted">Start by creating your first user account.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New User
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection