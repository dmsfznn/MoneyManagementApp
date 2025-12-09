@extends('layouts.app')

@section('title', 'Income Management')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="card card-shadcn bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Income Management</h2>
                    <p class="mb-0 opacity-90">Track and manage all your income records</p>
                </div>
                <div>
                    <a href="{{ route('user.income.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i> Add Income
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-success border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Income</h5>
                            <h3 class="text-success mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                            <small class="text-muted">All time income</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-wallet"></i>
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
                            <h5 class="card-title text-muted mb-0">Monthly Income</h5>
                            <h3 class="text-info mb-0">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
                            <small class="text-muted">{{ now()->format('F Y') }}</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-primary border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Records</h5>
                            <h3 class="text-primary mb-0">{{ $incomes->count() }}</h3>
                            <small class="text-muted">Income entries</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- Income List -->
    <div class="card card-shadcn">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Income Records</h5>
                <span class="badge bg-primary">{{ $incomes->count() }} records</span>
            </div>

            @if($incomes->count() > 0)
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
                            @foreach($incomes as $income)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape bg-primary bg-opacity-10 text-primary rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-calendar fa-sm"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $income->date->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $income->date->format('l') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $income->description }}</div>
                                        @if($income->notes)
                                            <small class="text-muted">{{ Str::limit($income->notes, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" style="background-color: {{ $income->category->color }};">
                                            @if($income->category->icon)
                                                <i class="{{ $income->category->icon }} me-1"></i>
                                            @endif
                                            {{ $income->category->name }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="text-success mb-0">Rp {{ number_format($income->amount, 0, ',', '.') }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('user.income.show', $income) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.income.edit', $income) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.income.destroy', $income) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this income record?')">
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

                @if($incomes->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $incomes->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="icon icon-shape bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins fa-3x"></i>
                    </div>
                    <h5 class="text-muted">No income records yet</h5>
                    <p class="text-muted mb-4">Start adding your income to track your finances effectively.</p>
                    <a href="{{ route('user.income.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Add Your First Income
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection