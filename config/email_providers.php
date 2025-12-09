<?php

return [
    'gmail' => [
        'mailer' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'yahoo' => [
        'mailer' => 'smtp',
        'host' => 'smtp.mail.yahoo.com',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'outlook' => [
        'mailer' => 'smtp',
        'host' => 'smtp-mail.outlook.com',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'hotmail' => [
        'mailer' => 'smtp',
        'host' => 'smtp.live.com',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'icloud' => [
        'mailer' => 'smtp',
        'host' => 'smtp.mail.me.com',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'aol' => [
        'mailer' => 'smtp',
        'host' => 'smtp.aol.com',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    // SMTP services
    'sendgrid' => [
        'mailer' => 'smtp',
        'host' => 'smtp.sendgrid.net',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'mailgun' => [
        'mailer' => 'smtp',
        'host' => 'smtp.mailgun.org',
        'port' => 587,
        'encryption' => 'tls',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'ses' => [
        'mailer' => 'ses',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    // Development
    'log' => [
        'mailer' => 'log',
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],
];