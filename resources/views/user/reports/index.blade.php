@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Laporan Keuangan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportPdf()">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportExcel()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter Laporan</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('user.reports') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="filter" class="form-label">Periode</label>
                                <select class="form-select" id="filter" name="filter" onchange="updateDateFields()">
                                    <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                    <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Kustom</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ $startDate }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ $endDate }}" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stats success">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="card-category">Total Pemasukan</p>
                                        <h3 class="card-title">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-arrow-up text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stats danger">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="card-category">Total Pengeluaran</p>
                                        <h3 class="card-title">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-arrow-down text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stats {{ $balance >= 0 ? 'success' : 'danger' }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="card-category">Saldo</p>
                                        <h3 class="card-title">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-wallet {{ $balance >= 0 ? 'text-success' : 'text-danger' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ringkasan Keuangan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="summaryChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Kategori Pemasukan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="incomeCategoryChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Kategori Pengeluaran</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="expenseCategoryChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Detail Pemasukan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Deskripsi</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($incomes as $income)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($income->date)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ $income->category->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($income->description, 30) }}</td>
                                                <td class="text-end text-success">+ Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data pemasukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Detail Pengeluaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Deskripsi</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expenses as $expense)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        {{ $expense->category->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($expense->description, 30) }}</td>
                                                <td class="text-end text-danger">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data pengeluaran</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Update date fields based on filter selection
function updateDateFields() {
    const filter = document.getElementById('filter').value;
    const startDateField = document.getElementById('start_date');
    const endDateField = document.getElementById('end_date');
    const now = new Date();

    switch(filter) {
        case 'daily':
            startDateField.value = now.toISOString().split('T')[0];
            endDateField.value = now.toISOString().split('T')[0];
            break;
        case 'monthly':
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            startDateField.value = firstDay.toISOString().split('T')[0];
            endDateField.value = lastDay.toISOString().split('T')[0];
            break;
        case 'yearly':
            const firstYearDay = new Date(now.getFullYear(), 0, 1);
            const lastYearDay = new Date(now.getFullYear(), 11, 31);
            startDateField.value = firstYearDay.toISOString().split('T')[0];
            endDateField.value = lastYearDay.toISOString().split('T')[0];
            break;
        case 'custom':
            // Don't change dates for custom
            break;
    }
}

// Export functions
function exportPdf() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    window.open(`/user/reports/export/pdf?${params.toString()}`, '_blank');
}

function exportExcel() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    window.open(`/user/reports/export/excel?${params.toString()}`, '_blank');
}

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Summary Chart
    const summaryCtx = document.getElementById('summaryChart').getContext('2d');
    new Chart(summaryCtx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Jumlah',
                data: @json($chartData),
                backgroundColor: @json($chartColors),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Income Category Chart
    const incomeCtx = document.getElementById('incomeCategoryChart').getContext('2d');
    new Chart(incomeCtx, {
        type: 'pie',
        data: {
            labels: @json($incomeByCategory->keys()->toArray()),
            datasets: [{
                data: @json($incomeByCategory->values()->toArray()),
                backgroundColor: [
                    '#28a745', '#20c997', '#17a2b8', '#6f42c1',
                    '#fd7e14', '#e83e8c', '#6c757d', '#343a40'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Expense Category Chart
    const expenseCtx = document.getElementById('expenseCategoryChart').getContext('2d');
    new Chart(expenseCtx, {
        type: 'pie',
        data: {
            labels: @json($expenseByCategory->keys()->toArray()),
            datasets: [{
                data: @json($expenseByCategory->values()->toArray()),
                backgroundColor: [
                    '#dc3545', '#fd7e14', '#ffc107', '#6f42c1',
                    '#17a2b8', '#20c997', '#28a745', '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection