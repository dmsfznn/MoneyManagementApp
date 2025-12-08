@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h4 class="card-title">Welcome back, {{ $user->name }}!</h4>
                <p class="card-text">Here's your financial overview for this month.</p>
            </div>
        </div>
    </div>
</div>

<!-- Financial Stats -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-stats success">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Monthly Income</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-stats danger">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Monthly Expenses</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-stats {{ $totalBalance >= 0 ? 'primary' : 'warning' }}">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Balance</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($totalBalance, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-{{ $totalBalance >= 0 ? 'primary' : 'warning' }} text-white rounded-circle shadow">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Budget Stats -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-piggy-bank text-info mr-2"></i>Budget Overview - {{ now()->format('F Y') }}
                </h5>
                <a href="{{ route('user.budgets.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye mr-1"></i> View All Budgets
                </a>
            </div>
            <div class="card-body">
                @if($activeBudgets->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Total Budget</h6>
                                <h4 class="text-primary">Rp {{ number_format($totalBudgetAmount, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Total Spent</h6>
                                <h4 class="text-info">Rp {{ number_format($totalBudgetSpent, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Remaining</h6>
                                <h4 class="text-success">Rp {{ number_format($totalBudgetAmount - $totalBudgetSpent, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="text-muted">Usage</h6>
                                <h4 class="{{ $totalBudgetAmount > 0 ? ($totalBudgetSpent / $totalBudgetAmount) >= 0.8 ? 'text-warning' : 'text-success' : 'text-muted' }}">
                                    {{ $totalBudgetAmount > 0 ? round(($totalBudgetSpent / $totalBudgetAmount) * 100, 1) : 0 }}%
                                </h4>
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

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.income.create') }}" class="btn btn-outline-success btn-block">
                            <i class="fas fa-plus-circle mr-2"></i>Add Income
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.expense.create') }}" class="btn btn-outline-danger btn-block">
                            <i class="fas fa-minus-circle mr-2"></i>Add Expense
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.income.index') }}" class="btn btn-outline-info btn-block">
                            <i class="fas fa-chart-bar mr-2"></i>Income List
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.expense.index') }}" class="btn btn-outline-warning btn-block">
                            <i class="fas fa-chart-bar mr-2"></i>Expense List
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.budgets.index') }}" class="btn btn-outline-info btn-block">
                            <i class="fas fa-piggy-bank mr-2"></i>Manage Budgets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Transactions</h5>
                <button class="btn btn-sm btn-outline-primary" disabled>
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </div>
            <div class="card-body">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->date->format('M d, Y') }}</td>
                                        <td>
                                            @if($transaction instanceof App\Models\Income)
                                                <span class="badge bg-success">Income</span>
                                            @else
                                                <span class="badge bg-danger">Expense</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $transaction->category->color }};">
                                                @if($transaction->category->icon)
                                                    <i class="{{ $transaction->category->icon }} mr-1"></i>
                                                @endif
                                                {{ $transaction->category->name }}
                                            </span>
                                        </td>
                                        <td class="text-right font-weight-bold {{ $transaction instanceof App\Models\Income ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction instanceof App\Models\Income ? '+' : '-' }}
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
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