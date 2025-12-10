<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DateFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
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
        // Get date filter from request
        $filter = $request->get('filter', DateFilterService::FILTER_MONTHLY);
        $customDate = $request->get('custom_date') ? Carbon::parse($request->get('custom_date')) : null;

        // Get date range
        $dateRange = DateFilterService::getDateRange($filter, $customDate);
        $dateRangeText = DateFilterService::getDateRangeText($filter, $customDate);

        // Get expenses within date range
        $expensesQuery = Expense::where('user_id', Auth::id())
            ->with('category')
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']]);

        $expenses = $expensesQuery->latest('date')->paginate(10);
        $totalExpenses = $expensesQuery->sum('amount');

        // Get current month expenses for comparison
        $monthlyExpenses = Expense::where('user_id', Auth::id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('user.expense.index', compact(
            'expenses',
            'totalExpenses',
            'monthlyExpenses',
            'filter',
            'dateRangeText'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::expense()->get();
        return view('user.expense.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('user.expense.index')
            ->with('success', 'Expense added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.expense.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::expense()->get();
        return view('user.expense.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense->update($request->all());

        return redirect()->route('user.expense.index')
            ->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('user.expense.index')
            ->with('success', 'Expense deleted successfully!');
    }
}
