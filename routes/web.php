<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\IncomeController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\ReportsController;
use App\Http\Controllers\User\BudgetController;

// Home route - redirect based on user role
Route::get('/home', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->name('home');

// Redirect root to login if not authenticated
Route::get('/', function () {
    return auth()->check() ?
        (auth()->user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('user.dashboard'))
        : redirect()->route('login');
})->name('home.index');

// Authentication routes
Auth::routes();

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', AdminUserController::class);

    // Profile routes
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Password Reset Management
    Route::resource('/password-resets', \App\Http\Controllers\Admin\PasswordResetController::class, [
        'index' => 'index',
        'edit' => 'edit',
        'update' => 'update',
        'destroy' => 'destroy'
    ])->parameters([
        'password-resets' => 'passwordResetRequest'
    ])->names('password-resets');
    Route::post('/password-resets/{passwordResetRequest}/cancel', [\App\Http\Controllers\Admin\PasswordResetController::class, 'cancel'])->name('password-resets.cancel');
    Route::get('/password-resets/statistics', [\App\Http\Controllers\Admin\PasswordResetController::class, 'statistics'])->name('password-resets.statistics');
});

// User Routes
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/export/pdf', [ReportsController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/export/excel', [ReportsController::class, 'exportExcel'])->name('reports.export.excel');
    Route::resource('/income', IncomeController::class);
    Route::resource('/expense', ExpenseController::class);
    Route::resource('/budgets', BudgetController::class);
    Route::put('/budgets/{budget}/toggle', [BudgetController::class, 'toggle'])->name('budgets.toggle');
});

// Debug Routes (Development Only)
if (app()->environment(['local', 'testing'])) {
    Route::prefix('debug')->name('debug.')->group(function () {
        Route::get('/email', [\App\Http\Controllers\Debug\EmailController::class, 'debug'])->name('email');
        Route::post('/email/test', [\App\Http\Controllers\Debug\EmailController::class, 'testEmail'])->name('email.test');
    });
}
