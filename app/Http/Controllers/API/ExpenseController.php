<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->expenses()->with('category');

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by description
        if ($request->has('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $expenses = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'expenses' => $expenses->getCollection()->map(function ($expense) {
                    return [
                        'id' => $expense->id,
                        'description' => $expense->description,
                        'amount' => $expense->amount,
                        'date' => $expense->date->format('Y-m-d'),
                        'category' => [
                            'id' => $expense->category->id,
                            'name' => $expense->category->name,
                            'type' => $expense->category->type,
                            'color' => $expense->category->color,
                            'icon' => $expense->category->icon,
                        ],
                        'created_at' => $expense->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $expense->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
            'pagination' => [
                'current_page' => $expenses->currentPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'last_page' => $expenses->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $expense = Auth::user()->expenses()->create([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        $expense->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Expense created successfully',
            'data' => [
                'expense' => [
                    'id' => $expense->id,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'date' => $expense->date->format('Y-m-d'),
                    'category' => [
                        'id' => $expense->category->id,
                        'name' => $expense->category->name,
                        'type' => $expense->category->type,
                        'color' => $expense->category->color,
                        'icon' => $expense->category->icon,
                    ],
                    'created_at' => $expense->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $expense->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        // Ensure user can only view their own expense
        if ($expense->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $expense->load('category');

        return response()->json([
            'success' => true,
            'data' => [
                'expense' => [
                    'id' => $expense->id,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'date' => $expense->date->format('Y-m-d'),
                    'category' => [
                        'id' => $expense->category->id,
                        'name' => $expense->category->name,
                        'type' => $expense->category->type,
                        'color' => $expense->category->color,
                        'icon' => $expense->category->icon,
                    ],
                    'created_at' => $expense->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $expense->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Ensure user can only update their own expense
        if ($expense->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        $expense->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully',
            'data' => [
                'expense' => [
                    'id' => $expense->id,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'date' => $expense->date->format('Y-m-d'),
                    'category' => [
                        'id' => $expense->category->id,
                        'name' => $expense->category->name,
                        'type' => $expense->category->type,
                        'color' => $expense->category->color,
                        'icon' => $expense->category->icon,
                    ],
                    'created_at' => $expense->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $expense->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Ensure user can only delete their own expense
        if ($expense->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully'
        ]);
    }
}