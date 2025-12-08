@extends('layouts.app')

@section('title', 'Income Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Income Details</h5>
                <div>
                    <a href="{{ route('user.income.edit', $income) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('user.income.destroy', $income) }}" method="POST" style="display: inline;">
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
                        <p><strong>Description:</strong> {{ $income->description }}</p>
                        <p><strong>Amount:</strong>
                            <span class="text-success font-weight-bold">
                                Rp {{ number_format($income->amount, 0, ',', '.') }}
                            </span>
                        </p>
                        <p><strong>Date:</strong> {{ $income->date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Category:</strong>
                            <span class="badge" style="background-color: {{ $income->category->color }};">
                                @if($income->category->icon)
                                    <i class="{{ $income->category->icon }} mr-1"></i>
                                @endif
                                {{ $income->category->name }}
                            </span>
                        </p>
                        <p><strong>Created:</strong> {{ $income->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Last Updated:</strong> {{ $income->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                @if($income->notes)
                    <div class="mt-3">
                        <p><strong>Notes:</strong></p>
                        <p class="bg-light p-3 rounded">{{ $income->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('user.income.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Income List
            </a>
        </div>
    </div>
</div>
@endsection