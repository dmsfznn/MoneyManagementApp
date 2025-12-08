<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->budgets()->with('category');

        // Filter by period
        if ($request->has('filter')) {
            $filter = $request->get('filter');
            if (in_array($filter, ['monthly', 'yearly'])) {
                $query->where('period', $filter);
            }
        }

        // Filter by month/year
        if ($request->has('month_year')) {
            $query->where('month_year', $request->month_year);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $budgets = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'budgets' => $budgets->getCollection()->map(function ($budget) {
                    return [
                        'id' => $budget->id,
                        'name' => $budget->name,
                        'amount' => $budget->amount,
                        'total_spent' => $budget->total_spent,
                        'remaining' => $budget->remaining,
                        'percentage_used' => round($budget->percentage_used, 1),
                        'period' => $budget->period,
                        'month_year' => $budget->month_year,
                        'period_display' => $budget->period_display,
                        'is_active' => $budget->is_active,
                        'notes' => $budget->notes,
                        'is_exceeded' => $budget->isExceeded(),
                        'is_almost_exceeded' => $budget->isAlmostExceeded(),
                        'category' => [
                            'id' => $budget->category->id,
                            'name' => $budget->category->name,
                            'type' => $budget->category->type,
                            'color' => $budget->category->color,
                            'icon' => $budget->category->icon,
                        ],
                        'created_at' => $budget->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $budget->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
            'pagination' => [
                'current_page' => $budgets->currentPage(),
                'per_page' => $budgets->perPage(),
                'total' => $budgets->total(),
                'last_page' => $budgets->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'period' => 'required|in:monthly,yearly',
            'month_year' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if budget already exists for this category and period
        $existingBudget = Auth::user()->budgets()
            ->where('category_id', $request->category_id)
            ->where('period', $request->period)
            ->where('month_year', $request->month_year)
            ->first();

        if ($existingBudget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget for this category and period already exists'
            ], 422);
        }

        $budget = Auth::user()->budgets()->create([
            'name' => $request->name,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'period' => $request->period,
            'month_year' => $request->month_year,
            'notes' => $request->notes,
        ]);

        $budget->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Budget created successfully',
            'data' => [
                'budget' => [
                    'id' => $budget->id,
                    'name' => $budget->name,
                    'amount' => $budget->amount,
                    'total_spent' => $budget->total_spent,
                    'remaining' => $budget->remaining,
                    'percentage_used' => round($budget->percentage_used, 1),
                    'period' => $budget->period,
                    'month_year' => $budget->month_year,
                    'period_display' => $budget->period_display,
                    'is_active' => $budget->is_active,
                    'notes' => $budget->notes,
                    'is_exceeded' => $budget->isExceeded(),
                    'is_almost_exceeded' => $budget->isAlmostExceeded(),
                    'category' => [
                        'id' => $budget->category->id,
                        'name' => $budget->category->name,
                        'type' => $budget->category->type,
                        'color' => $budget->category->color,
                        'icon' => $budget->category->icon,
                    ],
                    'created_at' => $budget->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $budget->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        // Ensure user can only view their own budget
        if ($budget->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $budget->load(['category', 'expenses']);

        // Get related expenses
        $expenses = $budget->expenses()
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'date' => $expense->date->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'budget' => [
                    'id' => $budget->id,
                    'name' => $budget->name,
                    'amount' => $budget->amount,
                    'total_spent' => $budget->total_spent,
                    'remaining' => $budget->remaining,
                    'percentage_used' => round($budget->percentage_used, 1),
                    'period' => $budget->period,
                    'month_year' => $budget->month_year,
                    'period_display' => $budget->period_display,
                    'is_active' => $budget->is_active,
                    'notes' => $budget->notes,
                    'is_exceeded' => $budget->isExceeded(),
                    'is_almost_exceeded' => $budget->isAlmostExceeded(),
                    'category' => [
                        'id' => $budget->category->id,
                        'name' => $budget->category->name,
                        'type' => $budget->category->type,
                        'color' => $budget->category->color,
                        'icon' => $budget->category->icon,
                    ],
                    'period_info' => [
                        'start_date' => $budget->getStartDate()->format('Y-m-d'),
                        'end_date' => $budget->getEndDate()->format('Y-m-d'),
                        'start_date_display' => $budget->getStartDate()->format('F d, Y'),
                        'end_date_display' => $budget->getEndDate()->format('F d, Y'),
                    ],
                    'expenses' => $expenses,
                    'created_at' => $budget->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $budget->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        // Ensure user can only update their own budget
        if ($budget->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'period' => 'required|in:monthly,yearly',
            'month_year' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if another budget exists for this category and period
        $existingBudget = Auth::user()->budgets()
            ->where('category_id', $request->category_id)
            ->where('period', $request->period)
            ->where('month_year', $request->month_year)
            ->where('id', '!=', $budget->id)
            ->first();

        if ($existingBudget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget for this category and period already exists'
            ], 422);
        }

        $budget->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'period' => $request->period,
            'month_year' => $request->month_year,
            'notes' => $request->notes,
        ]);

        $budget->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Budget updated successfully',
            'data' => [
                'budget' => [
                    'id' => $budget->id,
                    'name' => $budget->name,
                    'amount' => $budget->amount,
                    'total_spent' => $budget->total_spent,
                    'remaining' => $budget->remaining,
                    'percentage_used' => round($budget->percentage_used, 1),
                    'period' => $budget->period,
                    'month_year' => $budget->month_year,
                    'period_display' => $budget->period_display,
                    'is_active' => $budget->is_active,
                    'notes' => $budget->notes,
                    'is_exceeded' => $budget->isExceeded(),
                    'is_almost_exceeded' => $budget->isAlmostExceeded(),
                    'category' => [
                        'id' => $budget->category->id,
                        'name' => $budget->category->name,
                        'type' => $budget->category->type,
                        'color' => $budget->category->color,
                        'icon' => $budget->category->icon,
                    ],
                    'created_at' => $budget->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $budget->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        // Ensure user can only delete their own budget
        if ($budget->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $budget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Budget deleted successfully'
        ]);
    }

    /**
     * Toggle budget active status
     */
    public function toggle(Budget $budget)
    {
        // Ensure user can only toggle their own budget
        if ($budget->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $budget->update([
            'is_active' => !$budget->is_active
        ]);

        $status = $budget->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Budget {$status} successfully",
            'data' => [
                'budget' => [
                    'id' => $budget->id,
                    'is_active' => $budget->is_active,
                ]
            ]
        ]);
    }
}