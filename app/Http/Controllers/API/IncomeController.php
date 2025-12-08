<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->incomes()->with('category');

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
        $incomes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'incomes' => $incomes->getCollection()->map(function ($income) {
                    return [
                        'id' => $income->id,
                        'description' => $income->description,
                        'amount' => $income->amount,
                        'date' => $income->date->format('Y-m-d'),
                        'category' => [
                            'id' => $income->category->id,
                            'name' => $income->category->name,
                            'type' => $income->category->type,
                            'color' => $income->category->color,
                            'icon' => $income->category->icon,
                        ],
                        'created_at' => $income->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $income->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
            'pagination' => [
                'current_page' => $incomes->currentPage(),
                'per_page' => $incomes->perPage(),
                'total' => $incomes->total(),
                'last_page' => $incomes->lastPage(),
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

        $income = Auth::user()->incomes()->create([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        $income->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Income created successfully',
            'data' => [
                'income' => [
                    'id' => $income->id,
                    'description' => $income->description,
                    'amount' => $income->amount,
                    'date' => $income->date->format('Y-m-d'),
                    'category' => [
                        'id' => $income->category->id,
                        'name' => $income->category->name,
                        'type' => $income->category->type,
                        'color' => $income->category->color,
                        'icon' => $income->category->icon,
                    ],
                    'created_at' => $income->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $income->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        // Ensure user can only view their own income
        if ($income->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $income->load('category');

        return response()->json([
            'success' => true,
            'data' => [
                'income' => [
                    'id' => $income->id,
                    'description' => $income->description,
                    'amount' => $income->amount,
                    'date' => $income->date->format('Y-m-d'),
                    'category' => [
                        'id' => $income->category->id,
                        'name' => $income->category->name,
                        'type' => $income->category->type,
                        'color' => $income->category->color,
                        'icon' => $income->category->icon,
                    ],
                    'created_at' => $income->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $income->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        // Ensure user can only update their own income
        if ($income->user_id !== Auth::id()) {
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

        $income->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        $income->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Income updated successfully',
            'data' => [
                'income' => [
                    'id' => $income->id,
                    'description' => $income->description,
                    'amount' => $income->amount,
                    'date' => $income->date->format('Y-m-d'),
                    'category' => [
                        'id' => $income->category->id,
                        'name' => $income->category->name,
                        'type' => $income->category->type,
                        'color' => $income->category->color,
                        'icon' => $income->category->icon,
                    ],
                    'created_at' => $income->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $income->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        // Ensure user can only delete their own income
        if ($income->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $income->delete();

        return response()->json([
            'success' => true,
            'message' => 'Income deleted successfully'
        ]);
    }
}