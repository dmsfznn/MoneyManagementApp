<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.password.email');
    }

    /**
     * Send a password reset request to admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            // Create password reset request
            $passwordResetRequest = PasswordResetService::createRequest($request->email);

            return back()->with('status', 'Password reset request has been sent to our administrators. They will review your request and send you a new password via email.');

        } catch (\Exception $e) {
            \Log::error('Password reset request failed: ' . $e->getMessage());

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to process your request at this time. Please try again later.']);
        }
    }

    /**
     * Show success message after request submission.
     *
     * @return \Illuminate\View\View
     */
    public function requestSubmitted()
    {
        return view('auth.password.request-submitted');
    }
}