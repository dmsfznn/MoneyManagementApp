<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Money Management App') - Money Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: 100vh;
        }
        .card-stats {
            border-left: 4px solid #007bff;
        }
        .card-stats.success {
            border-left-color: #28a745;
        }
        .card-stats.warning {
            border-left-color: #ffc107;
        }
        .card-stats.danger {
            border-left-color: #dc3545;
        }
    </style>
</head>
<body>
    @if(auth()->check())
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <h5 class="font-weight-bold">Money Management</h5>
                            <small class="text-muted">{{ auth()->user()->name }}</small>
                            <br>
                            <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'primary' }}">
                                {{ auth()->user()->role }}
                            </span>
                        </div>

                        <ul class="nav flex-column">
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                       href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                                       href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-users"></i> Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                                       href="{{ route('admin.profile.index') }}">
                                        <i class="fas fa-user-circle"></i> Profile
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                                       href="{{ route('user.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.income.index') ? 'active' : '' }}"
                                       href="{{ route('user.income.index') }}">
                                        <i class="fas fa-plus-circle text-success"></i> Income
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.expense.index') ? 'active' : '' }}"
                                       href="{{ route('user.expense.index') }}">
                                        <i class="fas fa-minus-circle text-danger"></i> Expenses
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.budgets.*') ? 'active' : '' }}"
                                       href="{{ route('user.budgets.index') }}">
                                        <i class="fas fa-piggy-bank text-info"></i> Budgets
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.reports') ? 'active' : '' }}"
                                       href="{{ route('user.reports') }}">
                                        <i class="fas fa-chart-bar"></i> Reports
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.profile*') ? 'active' : '' }}"
                                       href="{{ route('user.profile') }}">
                                        <i class="fas fa-user-circle text-info"></i> Profile
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <hr>

                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </nav>

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('title', 'Dashboard')</h1>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @yield('scripts')
</body>
</html>
