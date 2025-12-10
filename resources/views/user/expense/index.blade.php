@extends('layouts.app')

@section('title', 'Expense Management')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="card card-shadcn bg-gradient-danger text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Expense Management</h2>
                    <p class="mb-0 opacity-90">Track and manage all your expense records</p>
                </div>
                <div>
                    <a href="{{ route('user.expense.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i> Add Expense
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Component -->
    <x-date-filter :dateRangeText="$dateRangeText" />

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-danger border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Expenses</h5>
                            <h3 class="text-danger mb-0">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                            <small class="text-muted">All time expenses</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Monthly Expenses</h5>
                            <h3 class="text-warning mb-0">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</h3>
                            <small class="text-muted">{{ now()->format('F Y') }}</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-info border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Records</h5>
                            <h3 class="text-info mb-0">{{ $expenses->count() }}</h3>
                            <small class="text-muted">Expense entries</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Expense List -->
    <div class="card card-shadcn">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Expense Records</h5>
                <span class="badge bg-danger">{{ $expenses->count() }} records</span>
            </div>

            @if($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape bg-danger bg-opacity-10 text-danger rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-calendar fa-sm"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $expense->date->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $expense->date->format('l') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $expense->description }}</div>
                                        @if($expense->notes)
                                            <small class="text-muted">{{ Str::limit($expense->notes, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" style="background-color: {{ $expense->category->color }};">
                                            @if($expense->category->icon)
                                                <i class="{{ $expense->category->icon }} me-1"></i>
                                            @endif
                                            {{ $expense->category->name }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="text-danger mb-0">Rp {{ number_format($expense->amount, 0, ',', '.') }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('user.expense.show', $expense) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.expense.edit', $expense) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.expense.destroy', $expense) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this expense record?')">
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

                @if($expenses->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $expenses->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="icon icon-shape bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shopping-cart fa-3x"></i>
                    </div>
                    <h5 class="text-muted">No expense records yet</h5>
                    <p class="text-muted mb-4">Start adding your expenses to track your finances effectively.</p>
                    <a href="{{ route('user.expense.create') }}" class="btn btn-danger">
                        <i class="fas fa-plus me-1"></i> Add Your First Expense
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection