<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DateFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get date filter from request
        $filter = $request->get('filter', DateFilterService::FILTER_MONTHLY);
        $customDate = $request->get('custom_date') ? Carbon::parse($request->get('custom_date')) : null;

        // Get date range
        $dateRange = DateFilterService::getDateRange($filter, $customDate);
        $dateRangeText = DateFilterService::getDateRangeText($filter, $customDate);

        // Get financial data within date range
        $incomes = $user->incomes()->whereBetween('date', [$dateRange['start'], $dateRange['end']]);
        $expenses = $user->expenses()->whereBetween('date', [$dateRange['start'], $dateRange['end']]);

        $monthlyIncome = $incomes->sum('amount');
        $monthlyExpense = $expenses->sum('amount');
        $totalBalance = $monthlyIncome - $monthlyExpense;

        // Get budget information for the filtered period
        if ($filter === DateFilterService::FILTER_MONTHLY) {
            $currentMonth = $dateRange['start']->format('Y-m');
            $activeBudgets = $user->activeBudgets()
                ->where('month_year', $currentMonth)
                ->with('category')
                ->get();
        } else {
            // For other filters, get budgets that fall within the date range
            $activeBudgets = $user->activeBudgets()
                ->with('category')
                ->where(function($query) use ($dateRange) {
                    $query->whereBetween('month_year', [
                        $dateRange['start']->format('Y-m'),
                        $dateRange['end']->format('Y-m')
                    ]);
                })
                ->get();
        }

        $totalBudgetAmount = $activeBudgets->sum('amount');
        $totalBudgetSpent = $activeBudgets->sum(function ($budget) {
            return $budget->total_spent;
        });

        $exceededBudgets = $activeBudgets->filter(function ($budget) {
            return $budget->isExceeded();
        })->count();

        $almostExceededBudgets = $activeBudgets->filter(function ($budget) {
            return $budget->isAlmostExceeded();
        })->count();

        // Get recent transactions within date range
        $recentIncomes = $incomes->with('category')
            ->latest('date')
            ->limit(5)
            ->get();

        $recentExpenses = $expenses->with('category')
            ->latest('date')
            ->limit(5)
            ->get();

        $recentTransactions = $recentIncomes->concat($recentExpenses)
            ->sortByDesc('date')
            ->take(10);

        return view('user.dashboard', compact(
            'user',
            'monthlyIncome',
            'monthlyExpense',
            'totalBalance',
            'recentTransactions',
            'activeBudgets',
            'totalBudgetAmount',
            'totalBudgetSpent',
            'exceededBudgets',
            'almostExceededBudgets',
            'filter',
            'dateRangeText'
        ));
    }

    
  }
