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
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-info">
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_requests'] }}</div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending_requests'] }}</div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['approved_requests'] }}</div>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['rejected_requests'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Simple Filters -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.leaves.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="employee" class="form-label">Search Employee</label>
                            <input type="text" name="employee" id="employee" class="form-control" 
                                   placeholder="Name or email" value="{{ request('employee') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">From Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                    @if(request()->hasAny(['employee', 'status', 'start_date']))
                        <div class="mt-3">
                            <a href="{{ route('admin.leaves.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Leave Requests Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Leave Requests
                    </h6>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Dates</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Admin Remarks</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $index => $leave)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
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
                                        
                                            </td>
                                            <td>{{ $leave->admin_remarks }}</td>
                                            <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if ($leave->status === 'Pending')
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-success btn-sm" 
                                                                onclick="approveLeave({{ $leave->id }}, '{{ $leave->user->name }}', '{{ $leave->type }}', {{ $leave->duration }})">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                onclick="rejectLeave({{ $leave->id }}, '{{ $leave->user->name }}', '{{ $leave->type }}', {{ $leave->duration }})">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No actions available</span>
                                                @endif
                                            </td>
                                        </tr>
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
@endsection

@push('scripts')
<script>
function approveLeave(leaveId, userName, leaveType, duration) {
    // Show confirmation dialog
    const confirmMessage = `Approve leave request from ${userName}?\n\nDetails:\n- Type: ${leaveType}\n- Duration: ${duration} days\n\nPlease provide approval remarks (minimum 10 characters):`;
    
    const adminRemarks = prompt(confirmMessage);
    
    if (adminRemarks === null) {
        // User cancelled
        return;
    }
    
    if (!adminRemarks || adminRemarks.trim().length < 10) {
        alert('Error: Approval remarks are required and must be at least 10 characters long.');
        return;
    }
    
    if (adminRemarks.trim().length > 500) {
        alert('Error: Approval remarks cannot exceed 500 characters.');
        return;
    }
    
    // Show loading
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    button.disabled = true;
    
    // Submit the form
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PATCH');
    formData.append('admin_remarks', adminRemarks.trim());
    
    fetch(`/admin/leaves/${leaveId}/approve`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Success! Leave request approved successfully. Email notification sent to employee.');
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'An error occurred while approving the leave request.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network Error: An error occurred while approving the leave request.');
    })
    .finally(() => {
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function rejectLeave(leaveId, userName, leaveType, duration) {
    // Show confirmation dialog
    const confirmMessage = `Reject leave request from ${userName}?\n\nDetails:\n- Type: ${leaveType}\n- Duration: ${duration} days\n\nPlease provide reason for rejection (minimum 10 characters):`;
    
    const adminRemarks = prompt(confirmMessage);
    
    if (adminRemarks === null) {
        // User cancelled
        return;
    }
    
    if (!adminRemarks || adminRemarks.trim().length < 10) {
        alert('Error: Rejection reason is required and must be at least 10 characters long.');
        return;
    }
    
    if (adminRemarks.trim().length > 500) {
        alert('Error: Rejection reason cannot exceed 500 characters.');
        return;
    }
    
    // Show loading
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    button.disabled = true;
    
    // Submit the form
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PATCH');
    formData.append('admin_remarks', adminRemarks.trim());
    
    fetch(`/admin/leaves/${leaveId}/reject`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Success! Leave request rejected successfully. Email notification sent to employee.');
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'An error occurred while rejecting the leave request.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network Error: An error occurred while rejecting the leave request.');
    })
    .finally(() => {
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
});
</script>
@endpush
