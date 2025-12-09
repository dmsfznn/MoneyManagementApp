<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Money Management App Notification' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
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
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .body {
            margin-bottom: 30px;
        }
        .action {
            text-align: center;
            margin: 30px 0;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        .action-button:hover {
            opacity: 0.9;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .highlight {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Money Management App</h1>
        </div>

        <div class="content">
            <!-- Intro -->
            @if (!empty($greeting))
                <div class="greeting">{{ $greeting }}</div>
            @endif

            <!-- Body -->
            @if (!empty($introLines))
                @foreach ($introLines as $line)
                    <div class="body">{{ $line }}</div>
                @endforeach
            @endif

            <!-- Action -->
            @if (!empty($actionText) && !empty($actionUrl))
                <div class="action">
                    <a href="{{ $actionUrl }}" class="action-button">{{ $actionText }}</a>
                </div>
            @endif

            <!-- Outro -->
            @if (!empty($outroLines))
                @foreach ($outroLines as $line)
                    <div class="body">{{ $line }}</div>
                @endforeach
            @endif

            <!-- Salutation -->
            @if (!empty($salutation))
                <div class="salutation">{{ $salutation }}</div>
            @endif
        </div>

        <div class="footer">
            <p>This is an automated message from Money Management App. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Money Management App. All rights reserved.</p>
        </div>
    </div>
</body>
</html>