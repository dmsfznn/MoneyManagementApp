<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan {{ $periodText }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
        }
        .card-income {
            border-left: 4px solid #28a745;
        }
        .card-expense {
            border-left: 4px solid #dc3545;
        }
        .card-balance {
            border-left: 4px solid #007bff;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table .text-end {
            text-align: right;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .page-break {
            page-break-before: always;
        }
        .category-summary {
            margin-bottom: 20px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>LAPORAN KEUANGAN</h2>
        <h3>Periode {{ $periodText }}</h3>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        <p>Nama User: {{ $user->name }} ({{ $user->email }})</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card card-income">
            <h4>Total Pemasukan</h4>
            <h3 class="text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>
        <div class="card card-expense">
            <h4>Total Pengeluaran</h4>
            <h3 class="text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
        </div>
        <div class="card card-balance">
            <h4>Saldo</h4>
            <h3 class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Category Summary -->
    @if($incomeByCategory->count() > 0)
    <div class="category-summary">
        <h4>Ringkasan Pemasukan per Kategori</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomeByCategory as $category => $total)
                    <tr>
                        <td><span class="badge badge-success">{{ $category }}</span></td>
                        <td class="text-end text-success">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total Pemasukan</th>
                    <th class="text-end text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    @if($expenseByCategory->count() > 0)
    <div class="category-summary">
        <h4>Ringkisan Pengeluaran per Kategori</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenseByCategory as $category => $total)
                    <tr>
                        <td><span class="badge badge-danger">{{ $category }}</span></td>
                        <td class="text-end text-danger">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total Pengeluaran</th>
                    <th class="text-end text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- Detailed Income Table -->
    <div class="page-break">
        <h4>Detail Pemasukan</h4>
        @if($incomes->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomes as $index => $income)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($income->date)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-success">{{ $income->category->name ?? 'N/A' }}</span></td>
                            <td>{{ $income->description ?? '-' }}</td>
                            <td class="text-end text-success">Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Pemasukan</th>
                        <th class="text-end text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>Tidak ada data pemasukan pada periode ini.</p>
        @endif
    </div>

    <!-- Detailed Expense Table -->
    <div>
        <h4>Detail Pengeluaran</h4>
        @if($expenses->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $index => $expense)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-danger">{{ $expense->category->name ?? 'N/A' }}</span></td>
                            <td>{{ $expense->description ?? '-' }}</td>
                            <td class="text-end text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Pengeluaran</th>
                        <th class="text-end text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>Tidak ada data pengeluaran pada periode ini.</p>
        @endif
    </div>

    <!-- Footer -->
    <div style="margin-top: 50px; text-align: center; border-top: 1px solid #ddd; padding-top: 10px;">
        <p>Laporan ini dibangkitkan secara otomatis dari Sistem Manajemen Keuangan</p>
    </div>
</body>
</html>