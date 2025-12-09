@extends('layouts.app')

@section('title', 'Password Reset Statistics')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="card card-shadcn bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Password Reset Statistics</h2>
                    <p class="mb-0 opacity-90">Analytics and insights for password reset requests</p>
                </div>
                <div>
                    <a href="{{ route('admin.password-resets.index') }}" class="btn btn-light">
                        <i class="fas fa-list me-1"></i> Back to Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-primary border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Total Requests</h5>
                            <h3 class="text-primary mb-0">{{ $totalRequests }}</h3>
                            <small class="text-muted">All time</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-list-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Pending</h5>
                            <h3 class="text-warning mb-0">{{ $pendingRequests }}</h3>
                            <small class="text-muted">Waiting for processing</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-success border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Completed</h5>
                            <h3 class="text-success mb-0">{{ $completedRequests }}</h3>
                            <small class="text-muted">Successfully processed</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-shadcn border-danger border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">Cancelled</h5>
                            <h3 class="text-danger mb-0">{{ $cancelledRequests }}</h3>
                            <small class="text-muted">Cancelled by admin</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Period Statistics -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-info border-4">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3">Today</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-info mb-0">{{ $todayRequests }}</h3>
                            <small class="text-muted">Requests today</small>
                        </div>
                        <div class="icon icon-shape bg-info text-white rounded-circle">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-primary border-4">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3">This Week</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-primary mb-0">{{ $thisWeekRequests }}</h3>
                            <small class="text-muted">Last 7 days</small>
                        </div>
                        <div class="icon icon-shape bg-primary text-white rounded-circle">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-shadcn border-success border-4">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3">This Month</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-success mb-0">{{ $thisMonthRequests }}</h3>
                            <small class="text-muted">{{ now()->format('F Y') }}</small>
                        </div>
                        <div class="icon icon-shape bg-success text-white rounded-circle">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="card card-shadcn">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Recent Requests</h5>
                <span class="badge bg-primary">{{ $recentRequests->count() }} recent</span>
            </div>

            @if($recentRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Completed</th>
                                <th>Admin Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRequests as $request)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>
                                        @if($request->user)
                                            {{ $request->user->name }}
                                        @else
                                            <span class="text-muted">User not found</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->email }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($request->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($request->completed_at)
                                            {{ $request->completed_at->format('M d, Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->admin_notes)
                                            <span class="badge bg-light text-dark">Notes</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No Recent Requests</h6>
                    <p class="text-muted">Recent password reset requests will appear here.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-shadcn">
                <div class="card-body">
                    <h5 class="card-title mb-4">Performance Metrics</h5>
                    <div class="row text-center">
                        <div class="col-md-3 col-6">
                            <h6 class="text-muted mb-2">Completion Rate</h6>
                            @php
                                $completionRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100, 1) : 0;
                            @endphp
                            <h3 class="text-success mb-0">{{ $completionRate }}%</h3>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <h6 class="text-muted mb-2">Cancellation Rate</h6>
                            @php
                                $cancellationRate = $totalRequests > 0 ? round(($cancelledRequests / $totalRequests) * 100, 1) : 0;
                            @endphp
                            <h3 class="text-danger mb-0">{{ $cancellationRate }}%</h3>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ $cancellationRate }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <h6 class="text-muted mb-2">Pending Rate</h6>
                            @php
                                $pendingRate = $totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100, 1) : 0;
                            @endphp
                            <h3 class="text-warning mb-0">{{ $pendingRate }}%</h3>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ $pendingRate }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <h6 class="text-muted mb-2">Processing Rate</h6>
                            @php
                                $processingRate = $totalRequests > 0 ? round(($thisWeekRequests / $totalRequests) * 100, 1) : 0;
                            @endphp
                            <h3 class="text-info mb-0">{{ $processingRate }}%</h3>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: {{ $processingRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection