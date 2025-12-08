@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New Expense</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.expense.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                       id="description" name="description" value="{{ old('description') }}" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Amount (IDR) *</label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                       id="amount" name="amount" value="{{ old('amount') }}" min="0" step="0.01" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if(old('category_id') == $category->id) selected @endif>
                                            @if($category->icon)
                                                <i class="{{ $category->icon }}"></i>
                                            @endif
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date" class="form-label">Date *</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                       id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save mr-1"></i> Save Expense
                        </button>
                        <a href="{{ route('user.expense.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection