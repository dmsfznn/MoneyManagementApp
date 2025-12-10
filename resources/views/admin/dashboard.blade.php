@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section with Admin Profile -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadcn bg-gradient-primary text-white border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                                 class="rounded-circle border border-3 border-white" width="80" height="80"
                                 style="object-fit: cover;">
                        </div>
                        <div class="col">
                            <h3 class="mb-1">Selamat Datang, {{ auth()->user()->name }}! 👑</h3>
                            <p class="mb-0 opacity-90">{{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} • Administrator</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.profile.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-user-cog me-1"></i> Admin Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
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
                            <i class="fas fa-user-tie text-primary opacity-25 fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-info border-3 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-info text-white rounded-circle me-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h6 class="text-info mb-0">Pengguna Baru</h6>
                            </div>
                            <h3 class="mb-0 fw-bold text-info">
                                {{ $newThisMonth }}
                            </h3>
                            <small class="text-muted">Bulan ini</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-user-plus text-info opacity-25 fa-2x"></i>
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
            <div class="card card-shadcn bg-gradient-info text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">🚀 Aksi Cepat Admin</h5>
                        <span class="badge bg-light text-info">Panel Administrator</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-light btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-users text-primary"></i>
                                <span>Kelola Pengguna</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.create') }}"
                               class="btn btn-light btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-user-plus text-success"></i>
                                <span>Tambah Pengguna</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.profile.index') }}"
                               class="btn btn-light btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-user-cog text-warning"></i>
                                <span>Profil Admin</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.password-resets.index') }}"
                               class="btn btn-light btn-lg d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="fas fa-key text-danger"></i>
                                <span>Reset Password</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-shadcn border-info border-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">📊 Aktivitas Terkini</h5>
                        <span class="badge bg-info">Live</span>
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
                            <a href="#" class="btn btn-outline-info btn-sm">Lihat Semua Aktivitas</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada aktivitas</h6>
                            <p class="text-muted small">Aktivitas sistem akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadcn border-success border-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">👥 Pendaftaran Terkini</h5>
                        <span class="badge bg-success">30 Hari Terakhir</span>
                    </div>
                    @if($recentRegistrations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Peran</th>
                                        <th>Terdaftar</th>
                                        <th>Aksi</th>
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
                                                    <span class="badge bg-primary">Pengguna</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
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
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-success btn-sm">Lihat Semua Pengguna</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Tidak ada pendaftaran baru</h6>
                            <p class="text-muted small">Tidak ada pengguna yang mendaftar dalam 30 hari terakhir</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Password Reset Management -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-shadcn border-warning border-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">🔑 Manajemen Reset Password</h5>
                        <a href="{{ route('admin.password-resets.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-key me-1"></i> Kelola Permintaan
                        </a>
                    </div>

                    @if(isset($passwordResetStats['pending']) && $passwordResetStats['pending'] > 0)
                        <div class="alert alert-warning alert-sm mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ $passwordResetStats['pending'] }}</strong> permintaan reset password menunggu diproses
                            <a href="{{ route('admin.password-resets.index') }}" class="alert-link ms-2">Lihat Sekarang</a>
                        </div>
                    @endif

                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-warning border">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6 class="mb-1">Menunggu</h6>
                                <span class="badge bg-warning fs-6">{{ $passwordResetStats['pending'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-success border">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h6 class="mb-1">Selesai</h6>
                                <span class="badge bg-success fs-6">{{ $passwordResetStats['completed'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-info border">
                                <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                <h6 class="mb-1">Hari Ini</h6>
                                <span class="badge bg-info fs-6">{{ $passwordResetStats['today'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-primary border">
                                <i class="fas fa-list fa-2x text-primary mb-2"></i>
                                <h6 class="mb-1">Total</h6>
                                <span class="badge bg-primary fs-6">{{ $passwordResetStats['total'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card card-shadcn border-primary border-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">🖥️ Status Sistem</h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active">Hari Ini</button>
                            <button class="btn btn-outline-primary">Minggu</button>
                            <button class="btn btn-outline-primary">Bulan</button>
                        </div>
                    </div>

                    <div class="row text-center">
                        <!-- Server Status -->
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded position-relative border-primary border">
                                <i class="fas fa-server fa-2x text-primary mb-2"></i>
                                <h6 class="mb-1">Status Server</h6>
                                <span class="badge @if($systemStatus['server']['status'] == 'online') bg-success @elseif($systemStatus['server']['status'] == 'slow') bg-warning @else bg-danger @endif">
                                    @if($systemStatus['server']['status'] == 'online') Online @elseif($systemStatus['server']['status'] == 'slow') Lambat @else Offline @endif
                                </span>
                                <small class="d-block mt-1 text-muted">
                                    Uptime: {{ $systemStatus['server']['uptime'] }}
                                </small>
                                <small class="d-block text-muted">
                                    {{ $systemStatus['server']['response_time'] }}ms
                                </small>
                            </div>
                        </div>

                        <!-- Database Status -->
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-info border">
                                <i class="fas fa-database fa-2x text-info mb-2"></i>
                                <h6 class="mb-1">Database</h6>
                                <span class="badge @if($systemStatus['database']['status'] == 'healthy') bg-success @else bg-danger @endif">
                                    @if($systemStatus['database']['status'] == 'healthy') Sehat @else Error @endif
                                </span>
                                <small class="d-block mt-1 text-muted">
                                    {{ $systemStatus['database']['tables'] }} tabel
                                </small>
                                <small class="d-block text-muted">
                                    {{ $systemStatus['database']['connection'] }}
                                </small>
                            </div>
                        </div>

                        <!-- Memory Status -->
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-warning border">
                                <i class="fas fa-memory fa-2x @if($systemStatus['memory']['status'] == 'good') text-success @elseif($systemStatus['memory']['status'] == 'moderate') text-warning @else text-danger @endif mb-2"></i>
                                <h6 class="mb-1">Memori</h6>
                                <span class="badge @if($systemStatus['memory']['status'] == 'good') bg-success @elseif($systemStatus['memory']['status'] == 'moderate') bg-warning @else bg-danger @endif">
                                    @if($systemStatus['memory']['status'] == 'good') Baik @elseif($systemStatus['memory']['status'] == 'moderate') Sedang @else Kritis @endif
                                </span>
                                <small class="d-block mt-1 text-muted">
                                    {{ $systemStatus['memory']['used'] }} / {{ $systemStatus['memory']['limit'] }}
                                </small>
                                <small class="d-block text-muted">
                                    {{ $systemStatus['memory']['percentage'] }}% terpakai
                                </small>
                            </div>
                        </div>

                        <!-- Storage Status -->
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 bg-light rounded border-success border">
                                <i class="fas fa-hdd fa-2x @if($systemStatus['storage']['status'] == 'available') text-success @elseif($systemStatus['storage']['status'] == 'warning') text-warning @else text-danger @endif mb-2"></i>
                                <h6 class="mb-1">Penyimpanan</h6>
                                <span class="badge @if($systemStatus['storage']['status'] == 'available') bg-success @elseif($systemStatus['storage']['status'] == 'warning') bg-warning @else bg-danger @endif">
                                    @if($systemStatus['storage']['status'] == 'available') Tersedia @elseif($systemStatus['storage']['status'] == 'warning') Rendah @else Kritis @endif
                                </span>
                                <small class="d-block mt-1 text-muted">
                                    {{ $systemStatus['storage']['free'] }} tersisa
                                </small>
                                <small class="d-block text-muted">
                                    {{ $systemStatus['storage']['percentage'] }}% terpakai
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection