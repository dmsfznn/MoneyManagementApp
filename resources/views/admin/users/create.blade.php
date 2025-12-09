@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="icon icon-shape bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <h5 class="card-title mb-2">Create New User</h5>
                        <p class="text-muted">Fill in the details below to create a new user account</p>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Enter full name" required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="Enter email address" required>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="Enter password" required>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirm Password *</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="Confirm password" required>
                                    @error('password_confirmation')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label fw-medium">User Role *</label>
                                    <select class="form-select @error('role') is-invalid @enderror"
                                            id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="user" @if(old('role') == 'user') selected @endif>
                                            <i class="fas fa-user me-1"></i>User
                                        </option>
                                        <option value="admin" @if(old('role') == 'admin') selected @endif>
                                            <i class="fas fa-user-shield me-1"></i>Admin
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-medium">Account Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" selected>
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </option>
                                        <option value="inactive">
                                            <i class="fas fa-pause-circle me-1"></i>Inactive
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">Set the initial status of the user account</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection