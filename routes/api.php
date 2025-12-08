<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\IncomeController;
use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\API\BudgetController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ReportsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);

    // Income endpoints
    Route::get('/income', [IncomeController::class, 'index']);
    Route::post('/income', [IncomeController::class, 'store']);
    Route::get('/income/{income}', [IncomeController::class, 'show']);
    Route::put('/income/{income}', [IncomeController::class, 'update']);
    Route::delete('/income/{income}', [IncomeController::class, 'destroy']);

    // Expense endpoints
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show']);
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);

    // Budget endpoints
    Route::get('/budgets', [BudgetController::class, 'index']);
    Route::post('/budgets', [BudgetController::class, 'store']);
    Route::get('/budgets/{budget}', [BudgetController::class, 'show']);
    Route::put('/budgets/{budget}', [BudgetController::class, 'update']);
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy']);
    Route::put('/budgets/{budget}/toggle', [BudgetController::class, 'toggle']);

    // Reports endpoints
    Route::get('/reports', [ReportsController::class, 'index']);
    Route::get('/reports/export/pdf', [ReportsController::class, 'exportPdf']);
    Route::get('/reports/export/excel', [ReportsController::class, 'exportExcel']);
});

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Money Management API is working!',
        'version' => '1.0.0'
    ]);
});