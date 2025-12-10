<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DateFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
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

        // Get incomes within date range
        $incomesQuery = Income::where('user_id', Auth::id())
            ->with('category')
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']]);

        $incomes = $incomesQuery->latest('date')->paginate(10);
        $totalIncome = $incomesQuery->sum('amount');

        // Get current month income for comparison
        $monthlyIncome = Income::where('user_id', Auth::id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('user.income.index', compact(
            'incomes',
            'totalIncome',
            'monthlyIncome',
            'filter',
            'dateRangeText'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::income()->get();
        return view('user.income.create', compact('categories'));
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

        Income::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('user.income.index')
            ->with('success', 'Income added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.income.show', compact('income'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::income()->get();
        return view('user.income.edit', compact('income', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $income->update($request->all());

        return redirect()->route('user.income.index')
            ->with('success', 'Income updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }

        $income->delete();

        return redirect()->route('user.income.index')
            ->with('success', 'Income deleted successfully!');
    }
}
