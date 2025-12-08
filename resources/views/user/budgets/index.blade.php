@extends('layouts.app')

@section('title', 'Budget Management')

@section('content')
<div class="container mt-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Budget Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('user.budgets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Budget
                    </a>
                </div>
            </div>

            <!-- Budget Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Budget</h5>
                            <h3>Rp {{ number_format($totalBudgetAmount, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Spent</h5>
                            <h3>Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Exceeded</h5>
                            <h3>{{ $exceededBudgets }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Almost Exceeded</h5>
                            <h3>{{ $almostExceededBudgets }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('user.budgets.index') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="filter" class="form-label">Period</label>
                                        <select name="filter" id="filter" class="form-select">
                                            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="month_year" class="form-label">{{ $filter == 'monthly' ? 'Month' : 'Year' }}</label>
                                        <input type="{{ $filter == 'monthly' ? 'month' : 'number' }}"
                                               name="month_year"
                                               id="month_year"
                                               class="form-control"
                                               value="{{ $monthYear }}"
                                               min="{{ $filter == 'yearly' ? '2020' : '2020-01' }}"
                                               max="{{ $filter == 'yearly' ? now()->year : now()->format('Y-m') }}">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-outline-primary me-2">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <a href="{{ route('user.budgets.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Budgets List -->
            <div class="card">
                <div class="card-body">
                    @if ($budgets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Budget Name</th>
                                        <th>Category</th>
                                        <th>Period</th>
                                        <th>Budget Amount</th>
                                        <th>Spent</th>
                                        <th>Remaining</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budgets as $budget)
                                        <tr>
                                            <td>
                                                <strong>{{ $budget->name }}</strong>
                                                @if ($budget->notes)
                                                    <br><small class="text-muted">{{ $budget->notes }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $budget->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $budget->period_display }}</td>
                                            <td>Rp {{ number_format($budget->amount, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($budget->total_spent, 0, ',', '.') }}</td>
                                            <td class="{{ $budget->isExceeded() ? 'text-danger' : 'text-success' }}">
                                                Rp {{ number_format($budget->remaining, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar {{ $budget->isExceeded() ? 'bg-danger' : ($budget->isAlmostExceeded() ? 'bg-warning' : 'bg-success') }}"
                                                         role="progressbar"
                                                         style="width: {{ min(100, $budget->percentage_used) }}%"
                                                         aria-valuenow="{{ $budget->percentage_used }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        {{ round($budget->percentage_used, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($budget->isExceeded())
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> Exceeded
                                                    </span>
                                                @elseif ($budget->isAlmostExceeded())
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-exclamation-circle"></i> Almost
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> On Track
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('user.budgets.show', $budget) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('user.budgets.edit', $budget) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('user.budgets.toggle', $budget) }}"
                                                          method="POST"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                                class="btn btn-sm {{ $budget->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                                title="{{ $budget->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fas {{ $budget->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('user.budgets.destroy', $budget) }}"
                                                          method="POST"
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this budget?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-piggy-bank fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No budgets found</h5>
                            <p class="text-muted">Create your first budget to start tracking your expenses.</p>
                            <a href="{{ route('user.budgets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Budget
                            </a>
                        </div>
                    @endif
                </div>
            </div>
</div>
@endsection