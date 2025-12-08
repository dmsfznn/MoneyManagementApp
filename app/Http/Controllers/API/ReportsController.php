<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Get financial reports data
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'monthly'); // daily, monthly, yearly
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set default date ranges based on filter
        if (!$startDate || !$endDate) {
            switch ($filter) {
                case 'daily':
                    $startDate = now()->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
                case 'monthly':
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->endOfYear()->format('Y-m-d');
                    break;
            }
        }

        // Get income and expense data for the period
        $incomes = $user->incomes()
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $expenses = $user->expenses()
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        // Group by category
        $incomeByCategory = $incomes->groupBy('category.name')->map(function ($items) {
            return [
                'total' => $items->sum('amount'),
                'count' => $items->count(),
                'color' => $items->first()->category->color,
                'icon' => $items->first()->category->icon,
            ];
        });

        $expenseByCategory = $expenses->groupBy('category.name')->map(function ($items) {
            return [
                'total' => $items->sum('amount'),
                'count' => $items->count(),
                'color' => $items->first()->category->color,
                'icon' => $items->first()->category->icon,
            ];
        });

        // Prepare transactions list
        $transactions = $incomes->concat($expenses)
            ->sortByDesc('date')
            ->values()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction instanceof \App\Models\Income ? 'income' : 'expense',
                    'description' => $transaction->description,
                    'amount' => $transaction->amount,
                    'date' => $transaction->date->format('Y-m-d'),
                    'category' => [
                        'id' => $transaction->category->id,
                        'name' => $transaction->category->name,
                        'type' => $transaction->category->type,
                        'color' => $transaction->category->color,
                        'icon' => $transaction->category->icon,
                    ]
                ];
            });

        // Generate daily/monthly data for charts
        $chartData = [];
        if ($filter === 'daily') {
            $chartData = $this->generateDailyData($user, $startDate, $endDate);
        } elseif ($filter === 'monthly') {
            $chartData = $this->generateMonthlyData($user, $startDate, $endDate);
        } elseif ($filter === 'yearly') {
            $chartData = $this->generateYearlyData($user, $startDate, $endDate);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_income' => $totalIncome,
                    'total_expense' => $totalExpense,
                    'net_income' => $netIncome,
                    'period' => [
                        'filter' => $filter,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'start_date_display' => Carbon::parse($startDate)->format('F d, Y'),
                        'end_date_display' => Carbon::parse($endDate)->format('F d, Y'),
                    ]
                ],
                'income_by_category' => $incomeByCategory,
                'expense_by_category' => $expenseByCategory,
                'transactions' => $transactions,
                'chart_data' => $chartData,
            ]
        ]);
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf(Request $request)
    {
        // For now, return a response indicating PDF export
        return response()->json([
            'success' => true,
            'message' => 'PDF export endpoint. This would return a downloadable PDF file.',
            'note' => 'PDF export functionality would need additional implementation in the mobile app to handle file downloads.'
        ]);
    }

    /**
     * Export reports to Excel
     */
    public function exportExcel(Request $request)
    {
        // For now, return a response indicating Excel export
        return response()->json([
            'success' => true,
            'message' => 'Excel export endpoint. This would return a downloadable Excel file.',
            'note' => 'Excel export functionality would need additional implementation in the mobile app to handle file downloads.'
        ]);
    }

    /**
     * Generate daily data for charts
     */
    private function generateDailyData($user, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $data = [];

        while ($start <= $end) {
            $date = $start->format('Y-m-d');
            $dayIncome = $user->incomes()->whereDate('date', $date)->sum('amount');
            $dayExpense = $user->expenses()->whereDate('date', $date)->sum('amount');

            $data[] = [
                'date' => $date,
                'date_display' => $start->format('M d'),
                'income' => $dayIncome,
                'expense' => $dayExpense,
                'net' => $dayIncome - $dayExpense,
            ];

            $start->addDay();
        }

        return $data;
    }

    /**
     * Generate monthly data for charts
     */
    private function generateMonthlyData($user, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();
        $data = [];

        while ($start <= $end) {
            $month = $start->format('Y-m');
            $monthIncome = $user->incomes()
                ->whereYear('date', $start->year)
                ->whereMonth('date', $start->month)
                ->sum('amount');

            $monthExpense = $user->expenses()
                ->whereYear('date', $start->year)
                ->whereMonth('date', $start->month)
                ->sum('amount');

            $data[] = [
                'month' => $month,
                'month_display' => $start->format('M Y'),
                'income' => $monthIncome,
                'expense' => $monthExpense,
                'net' => $monthIncome - $monthExpense,
            ];

            $start->addMonth();
        }

        return $data;
    }

    /**
     * Generate yearly data for charts
     */
    private function generateYearlyData($user, $startDate, $endDate)
    {
        $startYear = Carbon::parse($startDate)->year;
        $endYear = Carbon::parse($endDate)->year;
        $data = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            $yearIncome = $user->incomes()
                ->whereYear('date', $year)
                ->sum('amount');

            $yearExpense = $user->expenses()
                ->whereYear('date', $year)
                ->sum('amount');

            $data[] = [
                'year' => $year,
                'year_display' => $year,
                'income' => $yearIncome,
                'expense' => $yearExpense,
                'net' => $yearIncome - $yearExpense,
            ];
        }

        return $data;
    }
}