@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit User: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">New Password (leave empty to keep current)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Enter new password (min. 6 characters)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Password must be at least 6 characters long</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="user" @if(old('role', $user->role) == 'user') selected @endif>User</option>
                            <option value="admin" @if(old('role', $user->role) == 'admin') selected @endif>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($user->id === auth()->id())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Warning:</strong> You are editing your own account. Changing your role to 'user' will limit your access to admin features.
                        </div>
                    @endif

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection