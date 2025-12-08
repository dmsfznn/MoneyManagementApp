@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Expense Details</h5>
                <div>
                    <a href="{{ route('user.expense.edit', $expense) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('user.expense.destroy', $expense) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Description:</strong> {{ $expense->description }}</p>
                        <p><strong>Amount:</strong>
                            <span class="text-danger font-weight-bold">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </span>
                        </p>
                        <p><strong>Date:</strong> {{ $expense->date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Category:</strong>
                            <span class="badge" style="background-color: {{ $expense->category->color }};">
                                @if($expense->category->icon)
                                    <i class="{{ $expense->category->icon }} mr-1"></i>
                                @endif
                                {{ $expense->category->name }}
                            </span>
                        </p>
                        <p><strong>Created:</strong> {{ $expense->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Last Updated:</strong> {{ $expense->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                @if($expense->notes)
                    <div class="mt-3">
                        <p><strong>Notes:</strong></p>
                        <p class="bg-light p-3 rounded">{{ $expense->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('user.expense.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Expense List
            </a>
        </div>
    </div>
</div>
@endsection