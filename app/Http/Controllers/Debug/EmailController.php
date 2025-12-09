<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use App\Services\EmailDebugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class EmailController extends Controller
{
    public function __construct()
    {
        // Only allow in development environment
        if (app()->environment('production')) {
            abort(403, 'Debug controllers are not available in production.');
        }
    }

    /**
     * Display email configuration debug page
     */
    public function debug()
    {
        $testResults = EmailDebugService::testSmtpConnection();
        $solutions = [];

        if (!$testResults['connection']['success'] && isset($testResults['connection']['error'])) {
            $solutions = EmailDebugService::getErrorSolutions($testResults['connection']['error']);
        }

        return view('debug.email', compact('testResults', 'solutions'));
    }

    /**
     * Test email sending
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            Mail::raw('This is a test email from Money Management App', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Test Email - Money Management App')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return back()->with('success', 'Test email sent successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Switch email configuration
     */
    public function switchConfig(Request $request)
    {
        $request->validate([
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' => 'nullable|string|in:tls,ssl',
        ]);

        config([
            'mail.default' => $request->mailer,
            'mail.mailers.smtp.host' => $request->host,
            'mail.mailers.smtp.port' => $request->port,
            'mail.mailers.smtp.encryption' => $request->encryption,
        ]);

        return back()->with('success', 'Configuration updated!');
    }
}