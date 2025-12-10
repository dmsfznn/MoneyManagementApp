@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card card-shadcn bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Manajemen Pengguna</h2>
                    <p class="mb-0 opacity-90">Kelola semua akun pengguna sistem</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-light">
                        <i class="fas fa-user-plus me-1"></i> Tambah Pengguna Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
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
                                {{ $totalUsers }}
                            </h3>
                            <small class="text-muted">Semua pengguna terdaftar</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-users text-primary opacity-25 fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-shadcn border-success border-3 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <h6 class="text-success mb-0">Administrator</h6>
                            </div>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ $totalAdmins }}
                            </h3>
                            <small class="text-muted">Pengelola sistem</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-crown text-success opacity-25 fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-shadcn border-warning border-3 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h6 class="text-warning mb-0">Pengguna Biasa</h6>
                            </div>
                            <h3 class="mb-0 fw-bold text-warning">
                                {{ $totalRegularUsers }}
                            </h3>
                            <small class="text-muted">Pengguna standar</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-user-friends text-warning opacity-25 fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card card-shadcn border-info border-2">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">👥 Semua Pengguna</h5>
                <span class="badge bg-info">{{ $users->count() }} pengguna</span>
            </div>
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-shadcn">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th class="text-end">Total Pemasukan</th>
                                <th class="text-end">Total Pengeluaran</th>
                                <th class="text-end">Saldo</th>
                                <th>Bergabung</th>
                                <th class="text-center">Aksi</th>
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
                                                <i class="fas fa-user-shield me-1"></i>Admin
                                            </span>
                                        @else
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user me-1"></i>Pengguna
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
                                        <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-outline-info btn-sm"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-outline-warning btn-sm"
                                               title="Edit Pengguna">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Hapus Pengguna">
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
                    <h5 class="text-muted">Tidak ada pengguna</h5>
                    <p class="text-muted">Mulai dengan membuat akun pengguna pertama.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Pengguna Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection