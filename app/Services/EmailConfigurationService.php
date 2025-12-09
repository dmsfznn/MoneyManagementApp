<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class EmailConfigurationService
{
    /**
     * Detect email provider and update configuration dynamically
     *
     * @param string $email
     * @return void
     */
    public static function configureForEmail(string $email): void
    {
        $domain = strtolower(explode('@', $email)[1] ?? '');
        $provider = self::detectProvider($domain);

        $config = config("email_providers.{$provider}");

        if ($config && $provider !== 'log') {
            Config::set('mail.mailers.smtp', [
                'transport' => $config['mailer'],
                'host' => $config['host'],
                'port' => $config['port'],
                'encryption' => $config['encryption'] ?? null,
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'timeout' => 30,
            ]);

            Config::set('mail.from', [
                'address' => $config['from_address'],
                'name' => $config['from_name'],
            ]);
        }
    }

    /**
     * Detect email provider from domain
     *
     * @param string $domain
     * @return string
     */
    private static function detectProvider(string $domain): string
    {
        $providers = [
            'gmail.com' => 'gmail',
            'googlemail.com' => 'gmail',
            'yahoo.com' => 'yahoo',
            'ymail.com' => 'yahoo',
            'rocketmail.com' => 'yahoo',
            'outlook.com' => 'outlook',
            'hotmail.com' => 'hotmail',
            'live.com' => 'hotmail',
            'msn.com' => 'hotmail',
            'icloud.com' => 'icloud',
            'me.com' => 'icloud',
            'mac.com' => 'icloud',
            'aol.com' => 'aol',
            'aim.com' => 'aol',
        ];

        return $providers[$domain] ?? 'log';
    }

    /**
     * Get SMTP settings for a specific provider
     *
     * @param string $provider
     * @return array|null
     */
    public static function getProviderSettings(string $provider): ?array
    {
        return config("email_providers.{$provider}");
    }

    /**
     * Validate email configuration
     *
     * @return array
     */
    public static function validateConfiguration(): array
    {
        $mailer = Config::get('mail.default');
        $config = Config::get("mail.mailers.{$mailer}");

        $errors = [];

        if ($config && isset($config['transport']) && $config['transport'] === 'smtp') {
            if (empty($config['host'])) {
                $errors[] = 'SMTP host is not configured';
            }

            if (empty($config['port'])) {
                $errors[] = 'SMTP port is not configured';
            }

            if (empty(env('MAIL_USERNAME'))) {
                $errors[] = 'Mail username is not configured';
            }

            if (empty(env('MAIL_PASSWORD'))) {
                $errors[] = 'Mail password is not configured';
            }
        }

        if (empty(Config::get('mail.from.address'))) {
            $errors[] = 'Mail from address is not configured';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'mailer' => $mailer,
            'from_address' => Config::get('mail.from.address'),
        ];
    }

    /**
     * Get setup instructions for a specific email
     *
     * @param string $email
     * @return array
     */
    public static function getSetupInstructions(string $email): array
    {
        $domain = strtolower(explode('@', $email)[1] ?? '');
        $provider = self::detectProvider($domain);

        $instructions = [
            'gmail' => [
                'provider' => 'Gmail',
                'steps' => [
                    'Enable 2-Factor Authentication in Google Account',
                    'Create App Password: https://myaccount.google.com/apppasswords',
                    'Use App Password (16 characters) instead of regular password',
                    'Update .env file with Gmail SMTP settings'
                ],
                'settings' => [
                    'mailer' => 'smtp',
                    'host' => 'smtp.gmail.com',
                    'port' => '587',
                    'encryption' => 'tls'
                ]
            ],
            'yahoo' => [
                'provider' => 'Yahoo Mail',
                'steps' => [
                    'Enable 2-Factor Authentication in Yahoo Account',
                    'Create App Password: https://login.yahoo.com/account/security/app-passwords',
                    'Use App Password instead of regular password',
                    'Update .env file with Yahoo SMTP settings'
                ],
                'settings' => [
                    'mailer' => 'smtp',
                    'host' => 'smtp.mail.yahoo.com',
                    'port' => '587',
                    'encryption' => 'tls'
                ]
            ],
            'outlook' => [
                'provider' => 'Outlook/Hotmail',
                'steps' => [
                    'Enable 2-Factor Authentication in Microsoft Account',
                    'Create App Password: https://account.microsoft.com/security',
                    'Use App Password instead of regular password',
                    'Update .env file with Outlook SMTP settings'
                ],
                'settings' => [
                    'mailer' => 'smtp',
                    'host' => 'smtp-mail.outlook.com',
                    'port' => '587',
                    'encryption' => 'tls'
                ]
            ],
            'log' => [
                'provider' => 'Log File (Development)',
                'steps' => [
                    'Emails will be saved to storage/logs/laravel.log',
                    'Change MAIL_MAILER=smtp in .env to send real emails',
                    'Configure SMTP settings for your email provider'
                ],
                'settings' => [
                    'mailer' => 'log'
                ]
            ]
        ];

        return $instructions[$provider] ?? $instructions['log'];
    }
}