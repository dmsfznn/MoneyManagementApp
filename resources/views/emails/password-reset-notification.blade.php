<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Money Management App</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .credentials-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .credentials-box p {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .monospace {
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">Password Reset - Money Management App</h1>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $user->name }}</strong>,</p>

            <p>Your password reset request has been processed by our admin team.</p>

            <div class="credentials-box">
                <p><strong>Here are your new login credentials:</strong></p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>New Password:</strong> <span class="monospace">{{ $newPassword }}</span></p>
            </div>

            <p>You can now login to your Money Management App account by clicking the button below:</p>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
            </div>

            <p><em>For security reasons, we recommend changing your password after logging in.</em></p>

            @if($notes)
                <p><strong>Admin Notes:</strong> {{ $notes }}</p>
            @endif

            <hr style="border-top: 1px solid #e5e7eb; margin: 20px 0;">

            <p style="font-size: 12px; color: #6b7280;">If you did not request this password reset, please contact our support team immediately.</p>

            <p style="margin-bottom: 0;">Best regards,<br>
            <strong>{{ $admin->name }}</strong><br>
            Admin - Money Management App</p>
        </div>

        <div class="footer">
            <p>This is an automated message from Money Management App. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Money Management App. All rights reserved.</p>
        </div>
    </div>
</body>
</html>