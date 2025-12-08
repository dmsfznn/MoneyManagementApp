@extends('layouts.app')

@section('title', 'Income Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Income Management</h1>
    <a href="{{ route('user.income.create') }}" class="btn btn-success">
        <i class="fas fa-plus mr-1"></i> Add Income
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card card-stats success">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Income</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
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
    <div class="col-md-6">
        <div class="card card-stats info">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Monthly Income</h5>
                        <span class="h2 font-weight-bold mb-0">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</span>
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
</div>

<!-- Income List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Income Records</h5>
    </div>
    <div class="card-body">
        @if($incomes->count() > 0)
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
                        @foreach($incomes as $income)
                            <tr>
                                <td>{{ $income->date->format('M d, Y') }}</td>
                                <td>{{ $income->description }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $income->category->color }};">
                                        @if($income->category->icon)
                                            <i class="{{ $income->category->icon }} mr-1"></i>
                                        @endif
                                        {{ $income->category->name }}
                                    </span>
                                </td>
                                <td class="text-right font-weight-bold text-success">
                                    Rp {{ number_format($income->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('user.income.show', $income) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('user.income.edit', $income) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.income.destroy', $income) }}" method="POST" style="display: inline;">
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

            @if($incomes->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $incomes->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-plus-circle fa-4x text-muted mb-3"></i>
                <h5>No income records yet</h5>
                <p class="text-muted">Start adding your income to track your finances.</p>
                <a href="{{ route('user.income.create') }}" class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i> Add Your First Income
                </a>
            </div>
        @endif
    </div>
</div>
@endsection