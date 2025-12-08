<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Carbon\Carbon;
use PDF;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'monthly'); // daily, monthly, yearly
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $user = auth()->user();

        // Set default dates based on filter
        if (!$startDate || !$endDate) {
            $now = Carbon::now();

            switch ($filter) {
                case 'daily':
                    $startDate = $now->copy()->startOfDay()->format('Y-m-d');
                    $endDate = $now->copy()->endOfDay()->format('Y-m-d');
                    break;
                case 'monthly':
                    $startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                    $endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = $now->copy()->startOfYear()->format('Y-m-d');
                    $endDate = $now->copy()->endOfYear()->format('Y-m-d');
                    break;
            }
        }

        // Get income and expense data for the period
        $incomes = Income::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Group data by category for charts
        $incomeByCategory = $incomes->groupBy('category.name')->map(function ($items) {
            return $items->sum('amount');
        });

        $expenseByCategory = $expenses->groupBy('category.name')->map(function ($items) {
            return $items->sum('amount');
        });

        // Prepare chart data
        $chartLabels = ['Pemasukan', 'Pengeluaran'];
        $chartData = [$totalIncome, $totalExpense];
        $chartColors = ['#28a745', '#dc3545'];

        return view('user.reports.index', compact(
            'filter',
            'startDate',
            'endDate',
            'incomes',
            'expenses',
            'totalIncome',
            'totalExpense',
            'balance',
            'incomeByCategory',
            'expenseByCategory',
            'chartLabels',
            'chartData',
            'chartColors'
        ));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $user = auth()->user();

        if (!$startDate || !$endDate) {
            $now = Carbon::now();

            switch ($filter) {
                case 'daily':
                    $startDate = $now->copy()->startOfDay()->format('Y-m-d');
                    $endDate = $now->copy()->endOfDay()->format('Y-m-d');
                    break;
                case 'monthly':
                    $startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                    $endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = $now->copy()->startOfYear()->format('Y-m-d');
                    $endDate = $now->copy()->endOfYear()->format('Y-m-d');
                    break;
            }
        }

        // Get data for export
        $incomes = Income::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $periodText = match($filter) {
            'daily' => 'Harian',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => 'Kustom'
        };

        $data = [
            'user' => $user,
            'filter' => $filter,
            'periodText' => $periodText,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'incomeByCategory' => $incomes->groupBy('category.name')->map(function ($items) {
                return $items->sum('amount');
            }),
            'expenseByCategory' => $expenses->groupBy('category.name')->map(function ($items) {
                return $items->sum('amount');
            })
        ];

        $pdf = PDF::loadView('user.reports.pdf', $data);

        $filename = 'laporan-keuangan-' . $filter . '-' . Carbon::now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $user = auth()->user();

        if (!$startDate || !$endDate) {
            $now = Carbon::now();

            switch ($filter) {
                case 'daily':
                    $startDate = $now->copy()->startOfDay()->format('Y-m-d');
                    $endDate = $now->copy()->endOfDay()->format('Y-m-d');
                    break;
                case 'monthly':
                    $startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                    $endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = $now->copy()->startOfYear()->format('Y-m-d');
                    $endDate = $now->copy()->endOfYear()->format('Y-m-d');
                    break;
            }
        }

        // Get data for export
        $incomes = Income::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $periodText = match($filter) {
            'daily' => 'Harian',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => 'Kustom'
        };

        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= "LAPORAN KEUANGAN PERIODE " . strtoupper($periodText) . "\n";
        $csvContent .= "Tanggal," . Carbon::parse($startDate)->format('d F Y') . " - " . Carbon::parse($endDate)->format('d F Y') . "\n";
        $csvContent .= "Nama User," . $user->name . " (" . $user->email . ")\n";
        $csvContent .= "Tanggal Cetak," . Carbon::now()->format('d F Y H:i') . "\n\n";

        // Summary section
        $csvContent .= "RINGKASAN\n";
        $csvContent .= "Total Pemasukan," . $totalIncome . "\n";
        $csvContent .= "Total Pengeluaran," . $totalExpense . "\n";
        $csvContent .= "Saldo," . $balance . "\n\n";

        // Income section
        $csvContent .= "DATA PEMASUKAN\n";
        $csvContent .= "Tanggal,Kategori,Deskripsi,Jumlah\n";
        foreach ($incomes as $income) {
            $csvContent .= Carbon::parse($income->date)->format('d/m/Y') . ",";
            $csvContent .= '"' . ($income->category->name ?? 'N/A') . '",';
            $csvContent .= '"' . str_replace('"', '""', $income->description ?? '-') . '",';
            $csvContent .= $income->amount . "\n";
        }

        // Expense section
        $csvContent .= "\nDATA PENGELUARAN\n";
        $csvContent .= "Tanggal,Kategori,Deskripsi,Jumlah\n";
        foreach ($expenses as $expense) {
            $csvContent .= Carbon::parse($expense->date)->format('d/m/Y') . ",";
            $csvContent .= '"' . ($expense->category->name ?? 'N/A') . '",';
            $csvContent .= '"' . str_replace('"', '""', $expense->description ?? '-') . '",';
            $csvContent .= $expense->amount . "\n";
        }

        $filename = 'laporan-keuangan-' . $filter . '-' . Carbon::now()->format('Y-m-d') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
