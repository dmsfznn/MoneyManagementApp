<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DateFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
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

        // Map date filters to budget periods
        $period = in_array($filter, [DateFilterService::FILTER_MONTHLY, 'custom']) ? 'monthly' : 'yearly';
        $monthYear = $dateRange['start']->format('Y-m');

        // Get expense categories for dropdown
        $expenseCategories = Category::where('type', 'expense')
            ->orderBy('name')
            ->get();

        // Get budgets for the selected period
        $budgets = Budget::with('category')
            ->where('user_id', $user->id)
            ->where('period', $period)
            ->where('month_year', $monthYear)
            ->active()
            ->get();

        // Calculate budget statistics
        $totalBudgetAmount = $budgets->sum('amount');
        $totalSpent = 0;
        $exceededBudgets = 0;
        $almostExceededBudgets = 0;

        foreach ($budgets as $budget) {
            $totalSpent += $budget->total_spent;
            if ($budget->isExceeded()) {
                $exceededBudgets++;
            } elseif ($budget->isAlmostExceeded()) {
                $almostExceededBudgets++;
            }
        }

        return view('user.budgets.index', compact(
            'budgets',
            'expenseCategories',
            'filter',
            'monthYear',
            'totalBudgetAmount',
            'totalSpent',
            'exceededBudgets',
            'almostExceededBudgets',
            'dateRangeText'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Get expense categories for the user
        $categories = Category::where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('user.budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,yearly',
            'month_year' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if budget already exists for this category and period
        $existingBudget = Budget::where('user_id', $user->id)
            ->where('category_id', $request->category_id)
            ->where('period', $request->period)
            ->where('month_year', $request->month_year)
            ->first();

        if ($existingBudget) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Budget for this category and period already exists.');
        }

        Budget::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'period' => $request->period,
            'month_year' => $request->month_year,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('user.budgets.index')
            ->with('success', 'Budget created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        // Ensure user can only view their own budgets
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->load(['category', 'expenses']);

        return view('user.budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        // Ensure user can only edit their own budgets
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('user.budgets.edit', compact('budget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        // Ensure user can only update their own budgets
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,yearly',
            'month_year' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Check if another budget exists for this category and period
        $existingBudget = Budget::where('user_id', Auth::id())
            ->where('category_id', $request->category_id)
            ->where('period', $request->period)
            ->where('month_year', $request->month_year)
            ->where('id', '!=', $budget->id)
            ->first();

        if ($existingBudget) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Budget for this category and period already exists.');
        }

        $budget->update($request->only([
            'category_id',
            'name',
            'amount',
            'period',
            'month_year',
            'notes'
        ]));

        return redirect()
            ->route('user.budgets.index')
            ->with('success', 'Budget updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        // Ensure user can only delete their own budgets
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->delete();

        return redirect()
            ->route('user.budgets.index')
            ->with('success', 'Budget deleted successfully!');
    }

    /**
     * Toggle budget active status
     */
    public function toggle(Budget $budget)
    {
        // Ensure user can only toggle their own budgets
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->update([
            'is_active' => !$budget->is_active
        ]);

        $status = $budget->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('user.budgets.index')
            ->with('success', "Budget {$status} successfully!");
    }
}
