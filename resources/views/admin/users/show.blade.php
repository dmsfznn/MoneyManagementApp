@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- User Info Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">User Information</h5>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm ml-2" onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $user->id }}</p>
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                {{ $user->role }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Income:</strong>
                            <span class="text-success font-weight-bold">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </span>
                        </p>
                        <p><strong>Total Expenses:</strong>
                            <span class="text-danger font-weight-bold">
                                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                            </span>
                        </p>
                        <p><strong>Balance:</strong>
                            <span class="font-weight-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </span>
                        </p>
                        <p><strong>Member Since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Financial Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                            <p class="text-muted">Total Income</p>
                            <p class="mb-0"><small>{{ $user->incomes->count() }} transactions</small></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                            <p class="text-muted">Total Expenses</p>
                            <p class="mb-0"><small>{{ $user->expenses->count() }} transactions</small></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </h3>
                            <p class="text-muted">Net Balance</p>
                            <p class="mb-0"><small>{{ $balance >= 0 ? 'Positive' : 'Negative' }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
                @if($user->incomes->count() > 0 || $user->expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->incomes as $income)
                                    <tr>
                                        <td>{{ $income->date->format('M d, Y') }}</td>
                                        <td><span class="badge bg-success">Income</span></td>
                                        <td>{{ $income->description }}</td>
                                        <td class="text-success text-right">+Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @foreach($user->expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->date->format('M d, Y') }}</td>
                                        <td><span class="badge bg-danger">Expense</span></td>
                                        <td>{{ $expense->description }}</td>
                                        <td class="text-danger text-right">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">No transactions found for this user.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Back to Users List
    </a>
</div>
@endsection