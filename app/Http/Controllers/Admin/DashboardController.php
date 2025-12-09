<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display admin dashboard.
     */
    public function index()
    {
        // Check if user is authenticated and admin
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Access denied. You are not an admin.');
        }

        // Get user statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        // Get recent registrations (users created in the last 30 days)
        $recentRegistrations = User::where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        // Get new this month count
        $newThisMonth = User::where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now()->endOfMonth())
            ->count();

        // Get recent activities (for demo, we'll use recent registrations as activities)
        // In a real app, you might have an activities table
        $recentActivities = collect();

        // Add recent user registrations as activities
        $recentRegistrations->each(function($user) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'user_registered',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'description' => "New user {$user->name} ({$user->role}) registered",
                'icon' => 'fas fa-user-plus',
                'color' => 'success',
                'created_at' => $user->created_at
            ]);
        });

        // Get recent income records
        $recentIncomes = \App\Models\Income::with('user', 'category')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $recentIncomes->each(function($income) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'income_added',
                'user_id' => $income->user_id,
                'user_name' => $income->user->name,
                'description' => "{$income->user->name} added income: {$income->category->name}",
                'amount' => $income->amount,
                'icon' => 'fas fa-plus-circle',
                'color' => 'primary',
                'created_at' => $income->created_at
            ]);
        });

        // Get recent expense records
        $recentExpenses = \App\Models\Expense::with('user', 'category')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $recentExpenses->each(function($expense) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'expense_added',
                'user_id' => $expense->user_id,
                'user_name' => $expense->user->name,
                'description' => "{$expense->user->name} added expense: {$expense->category->name}",
                'amount' => $expense->amount,
                'icon' => 'fas fa-minus-circle',
                'color' => 'danger',
                'created_at' => $expense->created_at
            ]);
        });

        // Sort all activities by created_at
        $recentActivities = $recentActivities->sortByDesc('created_at')->take(10)->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'newThisMonth',
            'recentRegistrations',
            'recentActivities'
        ));
    }
}