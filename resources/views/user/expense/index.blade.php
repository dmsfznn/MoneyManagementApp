@extends('layouts.app')

@section('title', 'Expense Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Expense Management</h1>
    <a href="{{ route('user.expense.create') }}" class="btn btn-danger">
        <i class="fas fa-plus mr-1"></i> Add Expense
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card card-stats danger">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Expenses</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
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
    <div class="col-md-6">
        <div class="card card-stats warning">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Monthly Expenses</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</span>
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
</div>

<!-- Expense List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Expense Records</h5>
    </div>
    <div class="card-body">
        @if($expenses->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->date->format('M d, Y') }}</td>
                                <td>{{ $expense->description }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $expense->category->color }};">
                                        @if($expense->category->icon)
                                            <i class="{{ $expense->category->icon }} mr-1"></i>
                                        @endif
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td class="text-right font-weight-bold text-danger">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('user.expense.show', $expense) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('user.expense.edit', $expense) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.expense.destroy', $expense) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
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
                <div class="d-flex justify-content-center mt-3">
                    {{ $expenses->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-minus-circle fa-4x text-muted mb-3"></i>
                <h5>No expense records yet</h5>
                <p class="text-muted">Start adding your expenses to track your finances.</p>
                <a href="{{ route('user.expense.create') }}" class="btn btn-danger">
                    <i class="fas fa-plus mr-1"></i> Add Your First Expense
                </a>
            </div>
        @endif
    </div>
</div>
@endsection