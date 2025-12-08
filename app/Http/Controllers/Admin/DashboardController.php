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

        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers'
        ));
    }
}