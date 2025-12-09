@extends('layouts.app')

@section('title', 'Email Debug')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Email Configuration Debug</h4>
                </div>
                <div class="card-body">

                    <!-- Current Configuration -->
                    <h5>Current Configuration</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Mailer</th>
                                <td>{{ $testResults['current_config']['mailer'] }}</td>
                            </tr>
                            <tr>
                                <th>Host</th>
                                <td>{{ $testResults['current_config']['host'] }}</td>
                            </tr>
                            <tr>
                                <th>Port</th>
                                <td>{{ $testResults['current_config']['port'] }}</td>
                            </tr>
                            <tr>
                                <th>Encryption</th>
                                <td>{{ $testResults['current_config']['encryption'] }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $testResults['current_config']['username'] }}</td>
                            </tr>
                            <tr>
                                <th>From Address</th>
                                <td>{{ $testResults['current_config']['from_address'] }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Connection Test Results -->
                    <h5>Connection Test</h5>
                    @if($testResults['connection']['success'])
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Connection Successful</h6>
                            <p class="mb-0">{{ $testResults['connection']['message'] }}</p>
                            <p class="mb-0">Connected to: {{ $testResults['connection']['connected_to'] }}</p>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-circle me-2"></i>Connection Failed</h6>
                            @if(isset($testResults['connection']['error']))
                                <p class="mb-2"><strong>Error:</strong> {{ $testResults['connection']['error'] }}</p>
                                <p class="mb-2"><strong>Code:</strong> {{ $testResults['connection']['error_code'] }}</p>
                                <p class="mb-0"><strong>Location:</strong> {{ $testResults['connection']['file'] }}:{{ $testResults['connection']['line'] }}</p>
                            @endif
                        </div>

                        <!-- Solutions -->
                        @if(!empty($solutions))
                            <h6 class="mt-3">Possible Solutions:</h6>
                            <ul class="list-unstyled">
                                @foreach($solutions as $solution)
                                    <li class="mb-2">
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        {{ $solution }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif

                    <!-- Quick Fixes -->
                    <h5 class="mt-4">Quick Fixes</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Fix Gmail Issues</h6>
                                </div>
                                <div class="card-body">
                                    <ol>
                                        <li>Enable 2FA: <a href="https://myaccount.google.com/security" target="_blank">Click here</a></li>
                                        <li>Create App Password: <a href="https://myaccount.google.com/apppasswords" target="_blank">Click here</a></li>
                                        <li>Use App Password instead of regular password</li>
                                        <li>Update .env file with App Password</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Fix Yahoo Issues</h6>
                                </div>
                                <div class="card-body">
                                    <ol>
                                        <li>Enable 2FA: <a href="https://login.yahoo.com/account/security" target="_blank">Click here</a></li>
                                        <li>Create App Password: <a href="https://login.yahoo.com/account/security/app-passwords" target="_blank">Click here</a></li>
                                        <li>Generate App Password for "Mail"</li>
                                        <li>Update .env file with App Password</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Email Form -->
                    <h5 class="mt-4">Test Email Sending</h5>
                    <div class="row">
                        <div class="col-md-6">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('debug.email.test') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Test Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="Enter your email to test" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Test Email
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Alternative Configurations -->
                    <h5 class="mt-4">Alternative SMTP Configurations</h5>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    Gmail Alternative Configurations
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <h6>Try these configurations in your .env file:</h6>

                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <strong>Option 1 (TLS - Recommended)</strong>
                                        </div>
                                        <div class="card-body">
                                            <pre><code>MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password</code></pre>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Option 2 (SSL)</strong>
                                        </div>
                                        <div class="card-body">
                                            <pre><code>MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    Yahoo Alternative Configurations
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <h6>Try these configurations in your .env file:</h6>

                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <strong>Option 1 (TLS - Recommended)</strong>
                                        </div>
                                        <div class="card-body">
                                            <pre><code>MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password</code></pre>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Option 2 (SSL)</strong>
                                        </div>
                                        <div class="card-body">
                                            <pre><code>MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Debug -->
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection