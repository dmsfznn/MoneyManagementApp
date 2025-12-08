<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $type = $request->get('type'); // income, expense, or all

        $query = Category::query();

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        $categories = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'type' => $category->type,
                        'color' => $category->color,
                        'icon' => $category->icon,
                    ];
                })
            ]
        ]);
    }
}