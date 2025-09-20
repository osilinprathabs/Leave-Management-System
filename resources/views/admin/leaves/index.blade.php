@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tasks text-primary"></i> Leave Management
                </h1>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-info me-2">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if(isset($statistics))
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['approved'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['rejected'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter"></i> Filter Leave Requests
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.leaves.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="employee" class="form-label">Employee</label>
                                <input type="text" name="employee" id="employee" class="form-control" 
                                       placeholder="Search by name or email" value="{{ request('employee') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    @if(isset($statuses))
                                        @foreach($statuses as $key => $value)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" 
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" 
                                       value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Leave Requests Table -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Leave Requests
                    </h6>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Dates</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($leave->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $leave->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $leave->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $leave->type }}</span>
                                            </td>
                                            <td>{{ $leave->duration }} day{{ $leave->duration > 1 ? 's' : '' }}</td>
                                            <td>
                                                <div>
                                                    <strong>From:</strong> {{ $leave->start_date->format('M d, Y') }}<br>
                                                    <strong>To:</strong> {{ $leave->end_date->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span data-bs-toggle="tooltip" title="{{ $leave->reason ?? 'No reason provided' }}">
                                                    {{ Str::limit($leave->reason ?? 'No reason provided', 30) }}
                                                </span>
                                            </td>
                                            <td>
                                                @switch($leave->status)
                                                    @case('Pending')
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock"></i> Pending
                                                        </span>
                                                        @break
                                                    @case('Approved')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Approved
                                                        </span>
                                                        @break
                                                    @case('Rejected')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times"></i> Rejected
                                                        </span>
                                                        @break
                                                @endswitch
                                                @if($leave->admin_remarks)
                                                    <br><small class="text-muted">{{ Str::limit($leave->admin_remarks, 20) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if ($leave->status === 'Pending')
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to approve this leave request?')">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $leave->id }}">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No actions available</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Rejection Modal -->
                                        @if ($leave->status === 'Pending')
                                            <div class="modal fade" id="rejectModal_{{ $leave->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Leave Request</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <strong>Employee:</strong> {{ $leave->user->name }}<br>
                                                                    <strong>Leave Type:</strong> {{ $leave->type }}<br>
                                                                    <strong>Duration:</strong> {{ $leave->duration }} days<br>
                                                                    <strong>Dates:</strong> {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="admin_remarks_{{ $leave->id }}" class="form-label">
                                                                        Reason for Rejection <span class="text-danger">*</span>
                                                                    </label>
                                                                    <textarea name="admin_remarks" id="admin_remarks_{{ $leave->id }}" 
                                                                              class="form-control" rows="3" required 
                                                                              placeholder="Please provide a reason for rejecting this leave request..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject Leave Request</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $leaves->appends(request()->input())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">No Leave Requests Found</h5>
                            <p class="text-muted">No leave requests match your current filters.</p>
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

.text-primary { color: #4e73df !important; }
.text-success { color: #1cc88a !important; }
.text-info { color: #36b9cc !important; }
.text-warning { color: #f6c23e !important; }
.text-danger { color: #e74a3b !important; }

.badge-primary { background-color: #4e73df; }
.badge-success { background-color: #1cc88a; }
.badge-info { background-color: #36b9cc; }
.badge-warning { background-color: #f6c23e; }
.badge-danger { background-color: #e74a3b; }
.badge-secondary { background-color: #858796; }
</style>

<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
@endsection
