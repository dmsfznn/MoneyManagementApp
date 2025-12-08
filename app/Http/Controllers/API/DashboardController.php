<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get financial statistics
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
            ->take(10)
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

        // Prepare budget data
        $budgetData = $activeBudgets->map(function ($budget) {
            return [
                'id' => $budget->id,
                'name' => $budget->name,
                'amount' => $budget->amount,
                'total_spent' => $budget->total_spent,
                'remaining' => $budget->remaining,
                'percentage_used' => round($budget->percentage_used, 1),
                'period' => $budget->period,
                'period_display' => $budget->period_display,
                'is_exceeded' => $budget->isExceeded(),
                'is_almost_exceeded' => $budget->isAlmostExceeded(),
                'category' => [
                    'id' => $budget->category->id,
                    'name' => $budget->category->name,
                    'type' => $budget->category->type,
                    'color' => $budget->category->color,
                    'icon' => $budget->category->icon,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'statistics' => [
                    'monthly_income' => $monthlyIncome,
                    'monthly_expense' => $monthlyExpense,
                    'total_balance' => $totalBalance,
                ],
                'budgets' => [
                    'total_budget_amount' => $totalBudgetAmount,
                    'total_budget_spent' => $totalBudgetSpent,
                    'budget_remaining' => $totalBudgetAmount - $totalBudgetSpent,
                    'budget_usage_percentage' => $totalBudgetAmount > 0 ? round(($totalBudgetSpent / $totalBudgetAmount) * 100, 1) : 0,
                    'exceeded_budgets' => $exceededBudgets,
                    'almost_exceeded_budgets' => $almostExceededBudgets,
                    'active_budgets' => $budgetData,
                ],
                'recent_transactions' => $recentTransactions,
                'current_month' => now()->format('F Y'),
            ]
        ]);
    }
}