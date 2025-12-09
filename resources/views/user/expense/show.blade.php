@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header Card -->
            <div class="card card-shadcn bg-gradient-danger text-white mb-4">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-white bg-opacity-20 text-white rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                    <h4 class="mb-2">Expense Details</h4>
                    <p class="mb-0 opacity-90">View complete information about your expense record</p>
                </div>
            </div>

            <!-- Main Details Card -->
            <div class="card card-shadcn mb-4">
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-danger bg-opacity-10 text-danger rounded-circle me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    @if($expense->category->icon)
                                        <i class="{{ $expense->category->icon }} fa-2x"></i>
                                    @else
                                        <i class="fas fa-shopping-cart fa-2x"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="mb-1">{{ $expense->description }}</h3>
                                    <p class="text-muted mb-0">{{ $expense->date->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <h2 class="text-danger mb-0">Rp {{ number_format($expense->amount, 0, ',', '.') }}</h2>
                            <p class="text-muted mb-0">Expense Amount</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="text-muted small">Category</label>
                                <div>
                                    <span class="badge badge-primary fs-6" style="background-color: {{ $expense->category->color }};">
                                        @if($expense->category->icon)
                                            <i class="{{ $expense->category->icon }} me-1"></i>
                                        @endif
                                        {{ $expense->category->name }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small">Expense Date</label>
                                <p class="mb-0 fw-medium">
                                    <i class="fas fa-calendar text-muted me-2"></i>
                                    {{ $expense->date->format('l, F d, Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="text-muted small">Record Created</label>
                                <p class="mb-0 fw-medium">
                                    <i class="fas fa-clock text-muted me-2"></i>
                                    {{ $expense->created_at->format('F d, Y H:i A') }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small">Last Updated</label>
                                <p class="mb-0 fw-medium">
                                    <i class="fas fa-edit text-muted me-2"></i>
                                    {{ $expense->updated_at->format('F d, Y H:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($expense->notes)
                        <div class="mt-4">
                            <label class="text-muted small">Additional Notes</label>
                            <div class="alert alert-light">
                                <p class="mb-0">{{ $expense->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="d-flex gap-2 justify-content-between">
                        <a href="{{ route('user.expense.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Expense List
                        </a>
                        <div>
                            <a href="{{ route('user.expense.edit', $expense) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('user.expense.destroy', $expense) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this expense record?')">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection