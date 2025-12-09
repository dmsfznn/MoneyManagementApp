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

        // Get password reset statistics
        $passwordResetStats = \App\Services\PasswordResetService::getStatistics();

        // System Overview Data
        $systemStatus = [
            'server' => $this->getServerStatus(),
            'database' => $this->getDatabaseStatus(),
            'memory' => $this->getMemoryUsage(),
            'storage' => $this->getStorageStatus()
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'newThisMonth',
            'recentRegistrations',
            'recentActivities',
            'systemStatus',
            'passwordResetStats'
        ));
    }

    /**
     * Get server status
     */
    private function getServerStatus()
    {
        // Check if server is responding
        $status = [
            'status' => 'online',
            'uptime' => $this->getServerUptime(),
            'response_time' => $this->getResponseTime()
        ];

        if ($status['response_time'] > 5000) {
            $status['status'] = 'slow';
        }

        return $status;
    }

    /**
     * Get database status
     */
    private function getDatabaseStatus()
    {
        try {
            // Test database connection
            \DB::connection()->getPdo();

            // Get database stats
            $tables = \DB::select('SHOW TABLES');
            $tableCount = count($tables);

            return [
                'status' => 'healthy',
                'tables' => $tableCount,
                'connection' => 'connected'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection failed'
            ];
        }
    }

    /**
     * Get memory usage
     */
    private function getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');

        // Convert memory limit to bytes
        if ($memoryLimit === '-1') {
            $memoryLimitBytes = PHP_INT_MAX;
        } else {
            $memoryLimitBytes = $this->parseMemoryLimit($memoryLimit);
        }

        $usagePercent = ($memoryUsage / $memoryLimitBytes) * 100;

        if ($usagePercent > 80) {
            $status = 'critical';
        } elseif ($usagePercent > 60) {
            $status = 'moderate';
        } else {
            $status = 'good';
        }

        return [
            'status' => $status,
            'used' => $this->formatBytes($memoryUsage),
            'limit' => $memoryLimit === '-1' ? 'Unlimited' : $memoryLimit,
            'percentage' => round($usagePercent, 2)
        ];
    }

    /**
     * Get storage status
     */
    private function getStorageStatus()
    {
        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());

        if ($freeSpace === false || $totalSpace === false) {
            return [
                'status' => 'error',
                'message' => 'Unable to get disk info'
            ];
        }

        $usedSpace = $totalSpace - $freeSpace;
        $usagePercent = ($usedSpace / $totalSpace) * 100;

        if ($usagePercent > 90) {
            $status = 'critical';
        } elseif ($usagePercent > 70) {
            $status = 'warning';
        } else {
            $status = 'available';
        }

        return [
            'status' => $status,
            'used' => $this->formatBytes($usedSpace),
            'free' => $this->formatBytes($freeSpace),
            'total' => $this->formatBytes($totalSpace),
            'percentage' => round($usagePercent, 2)
        ];
    }

    /**
     * Get server uptime (Windows version)
     */
    private function getServerUptime()
    {
        if (function_exists('exec')) {
            try {
                exec('wmic os get lastbootuptime /value', $output);
                if (isset($output[1])) {
                    $boottime = substr($output[1], strpos($output[1], '=') + 1);
                    $boottime = substr($boottime, 0, 14);
                    $bootTime = \DateTime::createFromFormat('YmdHis', $boottime);
                    if ($bootTime) {
                        $now = new \DateTime();
                        $diff = $now->diff($bootTime);
                        return $diff->days . ' days, ' . $diff->h . ' hours';
                    }
                }
            } catch (\Exception $e) {
                // Fallback
            }
        }

        return 'Unknown';
    }

    /**
     * Get response time in milliseconds
     */
    private function getResponseTime()
    {
        $startTime = microtime(true);

        // Simple database query to test response time
        try {
            \DB::select('SELECT 1');
            $endTime = microtime(true);
            return round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds
        } catch (\Exception $e) {
            return 9999; // Return high value on error
        }
    }

    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);

        switch ($unit) {
            case 'g':
                return $value * 1024 * 1024 * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'k':
                return $value * 1024;
            default:
                return (int) $memoryLimit;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}