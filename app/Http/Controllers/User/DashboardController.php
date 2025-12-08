<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        $user = Auth::user();

        // Get real financial data
        $monthlyIncome = $user->monthly_income;
        $monthlyExpense = $user->monthly_expense;
        $totalBalance = $user->total_balance;

        // Get budget information for current month
        $currentMonth = now()->format('Y-m');
        $activeBudgets = $user->activeBudgets()
            ->where('month_year', $currentMonth)
            ->with('category')
            ->get();

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

        // Get recent transactions
        $recentIncomes = $user->incomes()
            ->with('category')
            ->latest('date')
            ->limit(5)
            ->get();

        $recentExpenses = $user->expenses()
            ->with('category')
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
            'almostExceededBudgets'
        ));
    }

    
  }
