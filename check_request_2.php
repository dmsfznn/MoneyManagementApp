<?php

require_once 'vendor/autoload.php';

use App\Models\PasswordResetRequest;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = PasswordResetRequest::find(2);

if ($request) {
    echo "Request ID 2 exists:\n";
    echo "ID: {$request->id}\n";
    echo "Email: {$request->email}\n";
    echo "User ID: " . ($request->user_id ?? 'null') . "\n";
    echo "Status: {$request->status}\n";
    echo "Created At: " . ($request->created_at ?? 'null') . "\n";
} else {
    echo "Request ID 2 not found!\n";
}