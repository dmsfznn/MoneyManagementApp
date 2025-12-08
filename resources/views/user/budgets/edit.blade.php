@extends('layouts.app')

@section('title', 'Edit Budget')

@section('content')
<div class="container mt-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Budget</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('user.budgets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Budgets
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.budgets.update', $budget) }}">
                                @csrf
                                @method('PUT')

                                <!-- Budget Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Budget Name</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $budget->name) }}"
                                           placeholder="e.g., Monthly Food Budget"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id"
                                            name="category_id"
                                            required>
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $budget->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @if ($categories->count() === 0)
                                        <div class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            No expense categories found. Please create expense categories first.
                                        </div>
                                    @endif
                                </div>

                                <!-- Amount -->
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Budget Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               id="amount"
                                               name="amount"
                                               value="{{ old('amount', $budget->amount) }}"
                                               placeholder="0"
                                               step="0.01"
                                               min="0"
                                               required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">Current spent: Rp {{ number_format($budget->total_spent, 0, ',', '.') }}</div>
                                </div>

                                <!-- Period -->
                                <div class="mb-3">
                                    <label class="form-label">Budget Period</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('period') is-invalid @enderror"
                                                       type="radio"
                                                       name="period"
                                                       id="period_monthly"
                                                       value="monthly"
                                                       {{ old('period', $budget->period) == 'monthly' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label" for="period_monthly">
                                                    Monthly
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('period') is-invalid @enderror"
                                                       type="radio"
                                                       name="period"
                                                       id="period_yearly"
                                                       value="yearly"
                                                       {{ old('period') == 'yearly' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="period_yearly">
                                                    Yearly
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('period')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Month/Year -->
                                <div class="mb-3">
                                    <label for="month_year" class="form-label">
                                        <span id="month_year_label">Month</span>
                                    </label>
                                    <input type="month"
                                           class="form-control @error('month_year') is-invalid @enderror"
                                           id="month_year"
                                           name="month_year"
                                           value="{{ old('month_year', $budget->month_year) }}"
                                           required>
                                    @error('month_year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">Select the month and year for this budget.</div>
                                </div>

                                <!-- Notes -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Add any additional notes about this budget...">{{ old('notes', $budget->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Budget Status -->
                                <div class="mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Current Budget Status</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Budget:</strong><br>
                                                    Rp {{ number_format($budget->amount, 0, ',', '.') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Spent:</strong><br>
                                                    Rp {{ number_format($budget->total_spent, 0, ',', '.') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Remaining:</strong><br>
                                                    Rp {{ number_format($budget->remaining, 0, ',', '.') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Used:</strong><br>
                                                    {{ round($budget->percentage_used, 1) }}%
                                                </div>
                                            </div>
                                            @if ($budget->isExceeded())
                                                <div class="alert alert-danger mt-2 mb-0">
                                                    <i class="fas fa-exclamation-triangle"></i> Budget has been exceeded!
                                                </div>
                                            @elseif ($budget->isAlmostExceeded())
                                                <div class="alert alert-warning mt-2 mb-0">
                                                    <i class="fas fa-exclamation-circle"></i> Budget is almost exceeded (80%+ used)
                                                </div>
                                            @else
                                                <div class="alert alert-success mt-2 mb-0">
                                                    <i class="fas fa-check-circle"></i> Budget is on track
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('user.budgets.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Budget
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodRadios = document.querySelectorAll('input[name="period"]');
    const monthYearInput = document.getElementById('month_year');
    const monthYearLabel = document.getElementById('month_year_label');
    const currentPeriod = '{{ $budget->period }}';
    const currentMonthYear = '{{ $budget->month_year }}';

    function updateMonthYearInput() {
        const selectedPeriod = document.querySelector('input[name="period"]:checked').value;

        if (selectedPeriod === 'yearly') {
            monthYearInput.type = 'number';
            monthYearInput.min = '2020';
            monthYearInput.max = new Date().getFullYear() + 10;
            monthYearInput.value = selectedPeriod === currentPeriod ? currentMonthYear : new Date().getFullYear();
            monthYearLabel.textContent = 'Year';
            monthYearInput.placeholder = 'YYYY';
        } else {
            monthYearInput.type = 'month';
            monthYearInput.min = '2020-01';
            monthYearInput.max = (new Date().getFullYear() + 10) + '-12';
            monthYearInput.value = selectedPeriod === currentPeriod ? currentMonthYear : new Date().toISOString().slice(0, 7);
            monthYearLabel.textContent = 'Month';
            monthYearInput.placeholder = 'YYYY-MM';
        }
    }

    periodRadios.forEach(radio => {
        radio.addEventListener('change', updateMonthYearInput);
    });

    // Initialize on page load
    updateMonthYearInput();
});
</script>
@endsection