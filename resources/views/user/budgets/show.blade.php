@extends('layouts.app')

@section('title', 'Budget Details')

@section('content')
<div class="container mt-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Budget Details</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('user.budgets.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Budgets
                    </a>
                    <a href="{{ route('user.budgets.edit', $budget) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Budget
                    </a>
                </div>
            </div>

            <!-- Budget Overview Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ $budget->name }}
                        @if ($budget->is_active)
                            <span class="badge bg-success float-end">Active</span>
                        @else
                            <span class="badge bg-secondary float-end">Inactive</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Category:</strong>
                                <span class="badge bg-info">{{ $budget->category->name }}</span>
                            </p>
                            <p><strong>Period:</strong> {{ $budget->period_display }}</p>
                            <p><strong>Budget Amount:</strong> Rp {{ number_format($budget->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Spent:</strong> Rp {{ number_format($budget->total_spent, 0, ',', '.') }}</p>
                            <p><strong>Remaining:</strong>
                                <span class="{{ $budget->isExceeded() ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($budget->remaining, 0, ',', '.') }}
                                </span>
                            </p>
                            <p><strong>Usage:</strong> {{ round($budget->percentage_used, 1) }}%</p>
                        </div>
                    </div>

                    @if ($budget->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong>
                            <p class="mb-0">{{ $budget->notes }}</p>
                        </div>
                    @endif

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Budget Progress</span>
                            <span>{{ round($budget->percentage_used, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar {{ $budget->isExceeded() ? 'bg-danger' : ($budget->isAlmostExceeded() ? 'bg-warning' : 'bg-success') }}"
                                 role="progressbar"
                                 style="width: {{ min(100, $budget->percentage_used) }}%"
                                 aria-valuenow="{{ $budget->percentage_used }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                @if ($budget->percentage_used > 10)
                                    {{ round($budget->percentage_used, 1) }}%
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Alert -->
                    <div class="mt-3">
                        @if ($budget->isExceeded())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Budget Exceeded!</strong> You have spent Rp {{ number_format($budget->total_spent - $budget->amount, 0, ',', '.') }} over your budget.
                            </div>
                        @elseif ($budget->isAlmostExceeded())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-circle"></i>
                                <strong>Budget Almost Exceeded!</strong> You have used {{ round($budget->percentage_used, 1) }}% of your budget.
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Budget On Track!</strong> You still have Rp {{ number_format($budget->remaining, 0, ',', '.') }} remaining.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Budget Period Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Budget Period</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Start Date:</strong> {{ $budget->getStartDate()->format('d M Y') }}</p>
                            <p><strong>End Date:</strong> {{ $budget->getEndDate()->format('d M Y') }}</p>
                            <p><strong>Days Remaining:</strong>
                                @php
                                    $daysRemaining = $budget->getEndDate()->diffInDays(now());
                                @endphp
                                {{ $daysRemaining > 0 ? $daysRemaining . ' days' : 'Period ended' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Budget Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('user.budgets.edit', $budget) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit Budget
                                </a>
                                <form action="{{ route('user.budgets.toggle', $budget) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-warning w-100">
                                        <i class="fas {{ $budget->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        {{ $budget->is_active ? 'Deactivate' : 'Activate' }} Budget
                                    </button>
                                </form>
                                <a href="{{ route('user.expenses.create') }}?category_id={{ $budget->category_id }}" class="btn btn-outline-success">
                                    <i class="fas fa-plus"></i> Add Expense to this Category
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Expenses -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Recent Expenses in This Category</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentExpenses = $budget->expenses()
                            ->orderBy('date', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp

                    @if ($recentExpenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentExpenses as $expense)
                                        <tr>
                                            <td>{{ $expense->date->format('d M Y') }}</td>
                                            <td>{{ $expense->description }}</td>
                                            <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('user.expenses.edit', $expense) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($budget->expenses()->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Showing 10 of {{ $budget->expenses()->count() }} expenses in this category
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No expenses found in this category for the budget period.</p>
                            <a href="{{ route('user.expenses.create') }}?category_id={{ $budget->category_id }}"
                               class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Add First Expense
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
</div>
@endsection