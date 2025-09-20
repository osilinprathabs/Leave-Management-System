@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section with Logout Button -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-tachometer-alt text-primary"></i> Admin Dashboard
                    </h1>
                    <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="text-right">
                    <div class="d-flex align-items-center">
                        <span class="badge badge-primary badge-lg me-3">{{ auth()->user()->email }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to logout?')">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                    <div class="small text-muted">Last login: {{ now()->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['approved_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['rejected_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_employees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['this_month_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('admin.leaves.index') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-tasks fa-2x mb-2"></i><br>
                                <strong>Manage Leave Requests</strong><br>
                                <small>Review and approve/reject requests</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                <strong>Manage Employees</strong><br>
                                <small>Add, edit, and manage employees</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                <strong>View Reports</strong><br>
                                <small>Analytics and insights</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('admin.leaves.index', ['status' => 'Pending']) }}" class="btn btn-warning btn-block btn-lg">
                                <i class="fas fa-clock fa-2x mb-2"></i><br>
                                <strong>Pending Requests</strong><br>
                                <small>{{ $statistics['pending_requests'] }} awaiting review</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <!-- Recent Leave Requests -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Recent Leave Requests
                    </h6>
                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves as $leave)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($leave->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $leave->user->name }}</div>
                                                        <small class="text-muted">{{ $leave->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge badge-secondary">{{ $leave->type }}</span></td>
                                            <td>{{ $leave->duration }}d</td>
                                            <td>
                                                @switch($leave->status)
                                                    @case('Pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                        @break
                                                    @case('Approved')
                                                        <span class="badge badge-success">Approved</span>
                                                        @break
                                                    @case('Rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $leave->created_at->format('M d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No recent leave requests</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Pending Requests
                    </h6>
                    <a href="{{ route('admin.leaves.index', ['status' => 'Pending']) }}" class="btn btn-sm btn-outline-warning">View All</a>
                </div>
                <div class="card-body">
                    @if($pendingLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingLeaves as $leave)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($leave->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $leave->user->name }}</div>
                                                        <small class="text-muted">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge badge-secondary">{{ $leave->type }}</span></td>
                                            <td>{{ $leave->duration }}d</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm" 
                                                                onclick="return confirm('Approve this request?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $leave->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Quick Reject Modal -->
                                        <div class="modal fade" id="rejectModal_{{ $leave->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Reject Request</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-body">
                                                            <textarea name="admin_remarks" class="form-control form-control-sm" 
                                                                      rows="2" required placeholder="Reason for rejection..."></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-success">No pending requests</h6>
                            <p class="text-muted">All caught uup!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

.text-primary { color: #4e73df !important; }
.text-success { color: #1cc88a !important; }
.text-info { color: #36b9cc !important; }
.text-warning { color: #f6c23e !important; }
.text-danger { color: #e74a3b !important; }
.text-secondary { color: #858796 !important; }

.badge-primary { background-color: #4e73df; }
.badge-success { background-color: #1cc88a; }
.badge-info { background-color: #36b9cc; }
.badge-warning { background-color: #f6c23e; }
.badge-danger { background-color: #e74a3b; }
.badge-secondary { background-color: #858796; }
</style>
@endsection
