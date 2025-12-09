@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-shadcn">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="icon icon-shape bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                        <h5 class="card-title mb-2">Edit Expense</h5>
                        <p class="text-muted">Update the details of your expense record</p>
                    </div>

                    <form action="{{ route('user.expense.update', $expense) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-medium">Description *</label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                                           id="description" name="description"
                                           value="{{ old('description', $expense->description) }}"
                                           placeholder="Enter expense description" required>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label fw-medium">Amount (IDR) *</label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                           id="amount" name="amount"
                                           value="{{ old('amount', $expense->amount) }}"
                                           placeholder="0" min="0" step="0.01" required>
                                    @error('amount')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-medium">Category *</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    @if(old('category_id', $expense->category_id) == $category->id) selected @endif>
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }}"></i>
                                                @endif
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label fw-medium">Date *</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                           id="date" name="date"
                                           value="{{ old('date', $expense->date->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-medium">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Add any additional notes (optional)">{{ old('notes', $expense->notes) }}</textarea>
                            @error('notes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Expense Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Current Expense Details
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">Current Amount:</small>
                                            <p class="mb-0"><strong>Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Category:</small>
                                            <p class="mb-0"><span class="badge badge-primary">{{ $expense->category->name }}</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Date:</small>
                                            <p class="mb-0">{{ $expense->date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.expense.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i> Update Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection