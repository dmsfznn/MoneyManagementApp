<?php

namespace App\Services;

use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Notifications\AdminPasswordResetNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PasswordResetService
{
    /**
     * Create a new password reset request
     */
    public static function createRequest(string $email): PasswordResetRequest
    {
        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Create request for non-existing user for security (don't reveal if email exists)
            return self::createDummyRequest($email);
        }

        // Check if there's already a pending request
        $existingRequest = PasswordResetRequest::where('email', $email)
            ->where('status', PasswordResetRequest::STATUS_PENDING)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if ($existingRequest) {
            return $existingRequest;
        }

        // Create new request
        $token = Str::random(60);

        $passwordResetRequest = PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => $email,
            'token' => $token,
            'status' => PasswordResetRequest::STATUS_PENDING,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notify admins
        self::notifyAdmins($passwordResetRequest);

        return $passwordResetRequest;
    }

    /**
     * Create a dummy request for security (to prevent email enumeration)
     */
    private static function createDummyRequest(string $email): PasswordResetRequest
    {
        $token = Str::random(60);

        return PasswordResetRequest::create([
            'user_id' => null,
            'email' => $email,
            'token' => $token,
            'status' => PasswordResetRequest::STATUS_PENDING,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Notify all admins about new password reset request
     */
    private static function notifyAdmins(PasswordResetRequest $request): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            try {
                $admin->notify(new AdminPasswordResetNotification($request));
            } catch (\Exception $e) {
                Log::error('Failed to notify admin ' . $admin->email . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Get statistics for password reset requests
     */
    public static function getStatistics(): array
    {
        return [
            'total' => PasswordResetRequest::count(),
            'pending' => PasswordResetRequest::pending()->count(),
            'processing' => PasswordResetRequest::processing()->count(),
            'completed' => PasswordResetRequest::completed()->count(),
            'cancelled' => PasswordResetRequest::where('status', 'cancelled')->count(),
            'today' => PasswordResetRequest::whereDate('created_at', today())->count(),
            'this_week' => PasswordResetRequest::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => PasswordResetRequest::whereMonth('created_at', now()->month)->count(),
        ];
    }

    /**
     * Get pending requests count for admin dashboard
     */
    public static function getPendingCount(): int
    {
        return PasswordResetRequest::pending()->count();
    }

    /**
     * Generate a secure password
     */
    public static function generateSecurePassword(int $length = 12): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        $allChars = $uppercase . $lowercase . $numbers . $special;

        $password = '';

        // Ensure at least one character from each category
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];

        // Fill the rest
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    /**
     * Validate if user can request password reset
     */
    public static function canUserRequestReset(User $user): bool
    {
        // Check if user has a recent pending request
        $recentRequest = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', PasswordResetRequest::STATUS_PENDING)
            ->where('created_at', '>', now()->subMinutes(30))
            ->first();

        return !$recentRequest;
    }

    /**
     * Clean old requests (older than 7 days)
     */
    public static function cleanOldRequests(): int
    {
        return PasswordResetRequest::where('created_at', '<', now()->subDays(7))
            ->where('status', '!=', PasswordResetRequest::STATUS_PENDING)
            ->delete();
    }
}