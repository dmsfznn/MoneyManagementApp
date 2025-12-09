<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Notifications\AdminPasswordResetNotification;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Display a listing of password reset requests.
     */
    public function index()
    {
        $requests = PasswordResetRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.password-resets.index', compact('requests'));
    }

    /**
     * Show the form for processing a password reset request.
     */
    public function edit(PasswordResetRequest $passwordResetRequest)
    {
        return view('admin.password-resets.edit', compact('passwordResetRequest'));
    }

    /**
     * Process the password reset request and update user password directly.
     */
    public function update(Request $request, PasswordResetRequest $passwordResetRequest)
    {
        \Log::info('=== PASSWORD RESET UPDATE START ===');
        \Log::info('Request ID: ' . $passwordResetRequest->id);
        \Log::info('Email: ' . $passwordResetRequest->email);
        \Log::info('Current Status: ' . $passwordResetRequest->status);
        \Log::info('User exists: ' . ($passwordResetRequest->user ? 'YES' : 'NO'));

        // Add debug for request data
        \Log::info('Request data: ', $request->all());
        \Log::info('New password value: "' . $request->new_password . '" (length: ' . strlen($request->new_password) . ')');
        \Log::info('Confirm password value: "' . $request->confirm_password . '" (length: ' . strlen($request->confirm_password) . ')');
        \Log::info('Are they equal? ' . ($request->new_password === $request->confirm_password ? 'YES' : 'NO'));

        $request->validate([
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:new_password',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        \Log::info('Validation passed');

        // Mark request as processing
        \Log::info('Before markAsProcessing - Status: ' . $passwordResetRequest->status);
        $passwordResetRequest->markAsProcessing();
        \Log::info('After markAsProcessing - Status: ' . $passwordResetRequest->status);

        // Force refresh from database
        $passwordResetRequest->refresh();
        \Log::info('After refresh - Status: ' . $passwordResetRequest->status);

        try {
            // Update user password directly if user exists
            if ($passwordResetRequest->user) {
                \Log::info('User found: ' . $passwordResetRequest->user->name);

                $passwordResetRequest->user->update([
                    'password' => bcrypt($request->new_password),
                    'remember_token' => Str::random(60),
                ]);

                \Log::info('Password updated in database');

                // Mark request as completed
                \Log::info('Before markAsCompleted - Status: ' . $passwordResetRequest->status);
                $passwordResetRequest->markAsCompleted($request->admin_notes);
                \Log::info('After markAsCompleted - Status: ' . $passwordResetRequest->status);

                // Force refresh from database
                $passwordResetRequest->refresh();
                \Log::info('After refresh completed - Status: ' . $passwordResetRequest->status);

                \Log::info('Redirecting to index with success message');
                return redirect()->route('admin.password-resets.index')
                    ->with('success', "Password has been reset successfully for {$passwordResetRequest->user->name}. New password: {$request->new_password}")
                    ->with('new_password', $request->new_password)
                    ->with('user_email', $passwordResetRequest->email)
                    ->with('user_name', $passwordResetRequest->user->name);
            } else {
                \Log::info('User not found');
                // Handle case where user doesn't exist
                $passwordResetRequest->markAsCompleted('User not found in system');

                return redirect()->route('admin.password-resets.index')
                    ->with('warning', "Password reset request processed. However, user not found in system for email: {$passwordResetRequest->email}");
            }

        } catch (\Exception $e) {
            \Log::error('Exception occurred: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->with('error', 'Failed to reset password. Please try again. Error: ' . $e->getMessage())
                ->withInput($request->all());
        }
    }

    
    /**
     * Cancel a password reset request.
     */
    public function cancel(PasswordResetRequest $passwordResetRequest)
    {
        $passwordResetRequest->cancel();

        return redirect()->route('admin.password-resets.index')
            ->with('info', 'Password reset request has been cancelled.');
    }

    /**
     * Generate email template for admin preview.
     */
    private function generateEmailTemplate($passwordResetRequest, $newPassword)
    {
        $user = $passwordResetRequest->user;
        $admin = auth()->user();

        $template = "Dear {$user->name},

Your password reset request has been processed by our admin team.

Here are your new login credentials:
Email: {$user->email}
New Password: {$newPassword}

You can now login to your Money Management App account at: " . route('login') . "

For security reasons, we recommend changing your password after logging in.

If you did not request this password reset, please contact our support team immediately.

Best regards,
{$admin->name}
Admin - Money Management App";

        return $template;
    }

    /**
     * Display statistics for password reset requests.
     */
    public function statistics()
    {
        $totalRequests = PasswordResetRequest::count();
        $pendingRequests = PasswordResetRequest::pending()->count();
        $completedRequests = PasswordResetRequest::completed()->count();
        $cancelledRequests = PasswordResetRequest::where('status', 'cancelled')->count();

        $recentRequests = PasswordResetRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.password-resets.statistics', compact(
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'cancelledRequests',
            'recentRequests'
        ));
    }
}