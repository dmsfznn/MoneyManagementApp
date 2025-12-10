@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section with User Profile -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-shadcn bg-gradient-primary text-white border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                             class="rounded-circle border border-3 border-white" width="80" height="80"
                             style="object-fit: cover;">
                    </div>
                    <div class="col">
                        <h3 class="mb-1">Selamat Datang, {{ $user->name }}! 👋</h3>
                        <p class="mb-0 opacity-90">{{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }}</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('user.profile') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-user me-1"></i> Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-shadcn border-success border-3 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                                <i class="fas fa-trending-up"></i>
                            </div>
                            <h6 class="text-success mb-0">Pendapatan Bulan Ini</h6>
                        </div>
                        <h3 class="mb-0 fw-bold text-success">
                            Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">{{ now()->format('F Y') }}</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-plus-circle text-success fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-shadcn border-danger border-3 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon icon-shape bg-danger text-white rounded-circle me-3">
                                <i class="fas fa-trending-down"></i>
                            </div>
                            <h6 class="text-danger mb-0">Pengeluaran Bulan Ini</h6>
                        </div>
                        <h3 class="mb-0 fw-bold text-danger">
                            Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">{{ now()->format('F Y') }}</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-minus-circle text-danger fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-shadcn {{ $totalBalance >= 0 ? 'border-primary border-3' : 'border-warning border-3' }} h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon icon-shape bg-{{ $totalBalance >= 0 ? 'primary' : 'warning' }} text-white rounded-circle me-3">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <h6 class="text-{{ $totalBalance >= 0 ? 'primary' : 'warning' }} mb-0">Saldo Total</h6>
                        </div>
                        <h3 class="mb-0 fw-bold text-{{ $totalBalance >= 0 ? 'primary' : 'warning' }}">
                            Rp {{ number_format($totalBalance, 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">Kumulatif</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-piggy-bank text-{{ $totalBalance >= 0 ? 'primary' : 'warning' }} fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-shadcn border-info">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 text-info">
                            <i class="fas fa-bolt me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <a href="{{ route('user.income.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Tambah Income
                            </a>
                            <a href="{{ route('user.expense.create') }}" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-minus-circle me-1"></i> Tambah Expense
                            </a>
                            <a href="{{ route('user.budgets.create') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-piggy-bank me-1"></i> Buat Budget
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Budget Overview -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-shadcn border-info">
            <div class="card-header bg-light border-bottom-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 text-info">
                            <i class="fas fa-piggy-bank me-2"></i>Ringkasan Budget - {{ now()->format('F Y') }}
                        </h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('user.budgets.index') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-list me-1"></i> Lihat Semua Budget
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($activeBudgets->count() > 0)
                    <!-- Budget Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card card-shadcn border-primary">
                                <div class="card-body text-center">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle mx-auto mb-2">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Total Budget</h6>
                                    <h5 class="text-primary mb-0">Rp {{ number_format($totalBudgetAmount, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card card-shadcn border-info">
                                <div class="card-body text-center">
                                    <div class="icon icon-shape bg-info text-white rounded-circle mx-auto mb-2">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Dana Terpakai</h6>
                                    <h5 class="text-info mb-0">Rp {{ number_format($totalBudgetSpent, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card card-shadcn border-success">
                                <div class="card-body text-center">
                                    <div class="icon icon-shape bg-success text-white rounded-circle mx-auto mb-2">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Sisa Dana</h6>
                                    <h5 class="text-success mb-0">Rp {{ number_format($totalBudgetAmount - $totalBudgetSpent, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        @php
    $budgetUsageClass = $totalBudgetAmount > 0 ? ($totalBudgetSpent / $totalBudgetAmount) >= 0.8 ? 'border-warning' : 'border-success' : 'border-muted';
    $budgetIconClass = $totalBudgetAmount > 0 ? ($totalBudgetSpent / $totalBudgetAmount) >= 0.8 ? 'bg-warning' : 'bg-success' : 'bg-muted';
    $budgetTextClass = $totalBudgetAmount > 0 ? ($totalBudgetSpent / $totalBudgetAmount) >= 0.8 ? 'text-warning' : 'text-success' : 'text-muted';
@endphp
<div class="col-md-3 mb-3">
                            <div class="card card-shadcn {{ $budgetUsageClass }}">
                                <div class="card-body text-center">
                                    <div class="icon icon-shape {{ $budgetIconClass }} text-white rounded-circle mx-auto mb-2">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Penggunaan</h6>
                                    <h5 class="{{ $budgetTextClass }} mb-0">
                                        {{ $totalBudgetAmount > 0 ? round(($totalBudgetSpent / $totalBudgetAmount) * 100, 1) : 0 }}%
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($exceededBudgets > 0 || $almostExceededBudgets > 0)
                        <div class="row mb-3">
                            @if($exceededBudgets > 0)
                                <div class="col-md-6">
                                    <div class="alert alert-danger mb-0">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>{{ $exceededBudgets }}</strong> budget{{ $exceededBudgets > 1 ? 's have' : ' has' }} been exceeded!
                                    </div>
                                </div>
                            @endif
                            @if($almostExceededBudgets > 0)
                                <div class="col-md-6">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <strong>{{ $almostExceededBudgets }}</strong> budget{{ $almostExceededBudgets > 1 ? 's are' : ' is' }} almost exceeded (80%+)
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Budget Progress</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Budget</th>
                                            <th>Spent</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activeBudgets->take(5) as $budget)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-info">{{ $budget->category->name }}</span>
                                                </td>
                                                <td>Rp {{ number_format($budget->amount, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($budget->total_spent, 0, ',', '.') }}</td>
                                                <td>
                                                    <div class="progress" style="height: 15px;">
                                                        <div class="progress-bar {{ $budget->isExceeded() ? 'bg-danger' : ($budget->isAlmostExceeded() ? 'bg-warning' : 'bg-success') }}"
                                                             role="progressbar"
                                                             style="width: {{ min(100, $budget->percentage_used) }}%">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($budget->isExceeded())
                                                        <span class="badge bg-danger">Exceeded</span>
                                                    @elseif($budget->isAlmostExceeded())
                                                        <span class="badge bg-warning">Almost</span>
                                                    @else
                                                        <span class="badge bg-success">On Track</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($activeBudgets->count() > 5)
                                <div class="text-center mt-2">
                                    <small class="text-muted">Showing 5 of {{ $activeBudgets->count() }} active budgets</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-piggy-bank fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No budgets set for {{ now()->format('F Y') }}</h5>
                        <p class="text-muted">Create budgets to better track and control your expenses.</p>
                        <a href="{{ route('user.budgets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Create Your First Budget
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card card-shadcn border-secondary">
            <div class="card-header bg-light border-bottom-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 text-secondary">
                            <i class="fas fa-history me-2"></i>Transaksi Terbaru
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-calendar me-1"></i> Tanggal</th>
                                    <th><i class="fas fa-tag me-1"></i> Kategori</th>
                                    <th><i class="fas fa-file-text me-1"></i> Deskripsi</th>
                                    <th><i class="fas fa-coins me-1"></i> Jumlah</th>
                                    <th class="text-center"><i class="fas fa-cog me-1"></i> Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $transaction->date->format('d M Y') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $transaction->date->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $transaction->category->color }};">
                                                @if($transaction->category->icon)
                                                    <i class="{{ $transaction->category->icon }} me-1"></i>
                                                @endif
                                                {{ $transaction->category->name }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                @if($transaction instanceof App\Models\Income)
                                                    <span class="text-success"><i class="fas fa-arrow-up"></i> Income</span>
                                                @else
                                                    <span class="text-danger"><i class="fas fa-arrow-down"></i> Expense</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $transaction->description }}</strong>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $transaction instanceof App\Models\Income ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction instanceof App\Models\Income ? '+' : '-' }}
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($transaction instanceof App\Models\Income)
                                                    <a href="{{ route('user.income.edit', $transaction) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('user.income.destroy', $transaction) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus transaksi ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('user.expense.edit', $transaction) }}" class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('user.expense.destroy', $transaction) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus transaksi ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('user.income.index') }}" class="btn btn-outline-success btn-sm mr-2">View All Income</a>
                        <a href="{{ route('user.expense.index') }}" class="btn btn-outline-danger btn-sm">View All Expenses</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No transactions yet</h5>
                        <p class="text-muted">Start adding your income and expenses to see them here.</p>
                        <div class="mt-3">
                            <a href="{{ route('user.income.create') }}" class="btn btn-success mr-2">
                                <i class="fas fa-plus mr-1"></i> Add Income
                            </a>
                            <a href="{{ route('user.expense.create') }}" class="btn btn-danger">
                                <i class="fas fa-minus mr-1"></i> Add Expense
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection