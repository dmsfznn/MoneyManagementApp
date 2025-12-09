<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class EmailDebugService
{
    /**
     * Test SMTP connection
     */
    public static function testSmtpConnection(): array
    {
        $results = [];

        // Get current mail configuration
        $mailer = config('mail.default');
        $mailConfig = config("mail.mailers.{$mailer}");

        $results['current_config'] = [
            'mailer' => $mailer,
            'host' => $mailConfig['host'] ?? 'Not set',
            'port' => $mailConfig['port'] ?? 'Not set',
            'encryption' => $mailConfig['encryption'] ?? 'Not set',
            'username' => config('mail.username') ?? 'Not set',
            'from_address' => config('mail.from.address') ?? 'Not set',
        ];

        // Test connection
        if ($mailer === 'smtp') {
            try {
                $transport = new EsmtpTransport(
                    $mailConfig['host'] ?? '',
                    $mailConfig['port'] ?? 587,
                    $mailConfig['encryption'] ?? null
                );

                $username = config('mail.username') ?? '';
                $password = config('mail.password') ?? '';

                if ($username && $password) {
                    $transport->setUsername($username);
                    $transport->setPassword($password);
                }

                $transport->start();
                $results['connection'] = [
                    'success' => true,
                    'message' => 'SMTP connection successful',
                    'connected_to' => $mailConfig['host'] . ':' . ($mailConfig['port'] ?? 587)
                ];
                $transport->stop();

            } catch (\Exception $e) {
                $results['connection'] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ];
            }
        } else {
            $results['connection'] = [
                'success' => false,
                'message' => 'Not using SMTP mailer'
            ];
        }

        return $results;
    }

    /**
     * Get common SMTP error solutions
     */
    public static function getErrorSolutions(string $errorMessage): array
    {
        $solutions = [];

        if (strpos($errorMessage, 'Connection refused') !== false) {
            $solutions[] = 'Check if SMTP server is running and accessible';
            $solutions[] = 'Verify the SMTP host and port are correct';
            $solutions[] = 'Check firewall settings (port 587/465/25)';
        }

        if (strpos($errorMessage, 'Authentication failed') !== false) {
            $solutions[] = 'Verify username and password are correct';
            $solutions[] = 'Use App Password instead of regular password (for Gmail/Yahoo)';
            $solutions[] = 'Enable 2-Factor Authentication and generate App Password';
        }

        if (strpos($errorMessage, 'TLS/SSL') !== false) {
            $solutions[] = 'Check encryption settings (TLS for port 587, SSL for port 465)';
            $solutions[] = 'Try changing encryption: tls -> ssl or remove encryption';
        }

        if (strpos($errorMessage, 'timeout') !== false) {
            $solutions[] = 'Check internet connection';
            $solutions[] = 'Try different SMTP server address';
            $solutions[] = 'Increase timeout value';
        }

        if (strpos($errorMessage, 'certificate') !== false) {
            $solutions[] = 'Check SSL certificate validity';
            $solutions[] = 'Try disabling certificate verification (for testing only)';
        }

        return $solutions;
    }

    /**
     * Create fallback configurations for common providers
     */
    public static function getFallbackConfigs(): array
    {
        return [
            'gmail_alternatives' => [
                [
                    'host' => 'smtp.gmail.com',
                    'port' => 587,
                    'encryption' => 'tls'
                ],
                [
                    'host' => 'smtp.gmail.com',
                    'port' => 465,
                    'encryption' => 'ssl'
                ]
            ],
            'yahoo_alternatives' => [
                [
                    'host' => 'smtp.mail.yahoo.com',
                    'port' => 587,
                    'encryption' => 'tls'
                ],
                [
                    'host' => 'smtp.mail.yahoo.com',
                    'port' => 465,
                    'encryption' => 'ssl'
                ]
            ],
            'outlook_alternatives' => [
                [
                    'host' => 'smtp-mail.outlook.com',
                    'port' => 587,
                    'encryption' => 'tls'
                ],
                [
                    'host' => 'smtp.live.com',
                    'port' => 587,
                    'encryption' => 'tls'
                ]
            ]
        ];
    }

    /**
     * Log email debugging information
     */
    public static function logEmailDebug(array $data): void
    {
        Log::channel('single')->info('Email Debug Information:', $data);
    }
}