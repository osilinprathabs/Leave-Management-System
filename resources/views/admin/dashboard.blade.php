@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted">
                <i class="fas fa-user me-1"></i>Welcome, <strong>{{ auth()->user()->name }}</strong>
            </span>
            <a href="{{ route('admin.employees.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm me-2">
                <i class="fas fa-users fa-sm text-white-50"></i> Manage Employees
            </a>
            <a href="{{ route('admin.leaves.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-tasks fa-sm text-white-50"></i> Manage Leaves
            </a>
            <a href="{{ route('admin.reports.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm me-2">
                <i class="fas fa-chart-bar fa-sm text-white-50"></i> View Reports
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" onclick="return confirmLogout()">
                    <i class="fas fa-sign-out-alt fa-sm text-white-50"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <!-- Total Leave Requests Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Leave Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Requests Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['approved_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Requests Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['rejected_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Leave Requests -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history"></i> Recent Leave Requests</h6>
                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-primary btn-sm">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    @if($recentLeaves->isEmpty())
                        <p class="text-center">No recent leave requests.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Dates</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves as $leave)
                                        <tr>
                                            <td>{{ $leave->user->name }}</td>
                                            <td>{{ $leave->type }}</td>
                                            <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->duration }} days</td>
                                            <td>
                                                <span class="badge 
                                                    @if($leave->status == 'Pending') bg-warning text-dark
                                                    @elseif($leave->status == 'Approved') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ $leave->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Leave Requests (Quick Actions) -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-exclamation-triangle"></i> Pending Requests</h6>
                    <a href="{{ route('admin.leaves.index', ['status' => 'Pending']) }}" class="btn btn-warning btn-sm">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    @if($pendingLeaves->isEmpty())
                        <p class="text-center">No pending leave requests.</p>
                    @else
                        <div class="list-group">
                            @foreach($pendingLeaves as $leave)
                                <div class="list-group-item list-group-item-action flex-column align-items-start mb-2">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $leave->user->name }} - {{ $leave->type }}</h5>
                                        <small class="text-muted">{{ $leave->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <i class="fas fa-calendar-alt me-1"></i> {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }} ({{ $leave->duration }} days)
                                    </p>
                                    <small class="text-muted">{{ Str::limit($leave->reason, 50) }}</small>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-success btn-sm me-1" onclick="openApproveModal({{ $leave->id }}, '{{ $leave->user->name }}', '{{ $leave->type }}', {{ $leave->duration }})">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="openRejectModal({{ $leave->id }}, '{{ $leave->user->name }}', '{{ $leave->type }}', {{ $leave->duration }})">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Please provide remarks for approving this leave request.
                    </div>
                    <div id="approveModalContent">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                    <div class="mb-3">
                        <label for="approve_admin_remarks" class="form-label">
                            <i class="fas fa-comment-alt me-1"></i> Approval Remarks <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="approve_admin_remarks" name="admin_remarks" rows="4" required 
                                  minlength="10" maxlength="500" 
                                  placeholder="Please provide remarks for approving this leave request (minimum 10 characters)..."></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i> 
                            Minimum 10 characters, maximum 500 characters. This remark will be sent to the employee.
                        </div>
                        <div class="invalid-feedback">
                            Please provide approval remarks (minimum 10 characters).
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="approveBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Approve Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> You must provide a detailed reason for rejecting this leave request.
                    </div>
                    <div id="rejectModalContent">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                    <div class="mb-3">
                        <label for="reject_admin_remarks" class="form-label">
                            <i class="fas fa-comment-alt me-1"></i> Reason for Rejection <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="reject_admin_remarks" name="admin_remarks" rows="4" required 
                                  minlength="10" maxlength="500" 
                                  placeholder="Please provide a detailed reason for rejecting this leave request (minimum 10 characters)..."></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i> 
                            Minimum 10 characters, maximum 500 characters. This reason will be sent to the employee.
                        </div>
                        <div class="invalid-feedback">
                            Please provide a detailed reason for rejection (minimum 10 characters).
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="rejectBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Reject Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: .25rem solid #4e73df!important; }
.border-left-success { border-left: .25rem solid #1cc88a!important; }
.border-left-info { border-left: .25rem solid #36b9cc!important; }
.border-left-warning { border-left: .25rem solid #f6c23e!important; }
.border-left-danger { border-left: .25rem solid #e74a3b!important; }
.text-primary { color: #4e73df!important; }
.text-success { color: #1cc88a!important; }
.text-info { color: #36b9cc!important; }
.text-warning { color: #f6c23e!important; }
.text-danger { color: #e74a3b!important; }
.text-gray-300 { color: #dddfeb!important; }
.text-gray-800 { color: #5a5c69!important; }
.bg-primary { background-color: #4e73df!important; }
.bg-success { background-color: #1cc88a!important; }
.bg-info { background-color: #36b9cc!important; }
.bg-warning { background-color: #f6c23e!important; }
.bg-danger { background-color: #e74a3b!important; }
.bg-secondary { background-color: #858796!important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentLeaveId = null;

function openApproveModal(leaveId, userName, leaveType, duration) {
    currentLeaveId = leaveId;
    
    // Update modal content
    document.getElementById('approveModalContent').innerHTML = `
        <p>You are about to approve a leave request from <strong>${userName}</strong> for <strong>${leaveType}</strong> (${duration} days).</p>
    `;
    
    // Update form action
    document.getElementById('approveForm').action = `/admin/leaves/${leaveId}/approve`;
    
    // Clear previous values
    document.getElementById('approve_admin_remarks').value = '';
    document.getElementById('approve_admin_remarks').classList.remove('is-invalid');
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function openRejectModal(leaveId, userName, leaveType, duration) {
    currentLeaveId = leaveId;
    
    // Update modal content
    document.getElementById('rejectModalContent').innerHTML = `
        <p>You are about to reject a leave request from <strong>${userName}</strong> for <strong>${leaveType}</strong> (${duration} days).</p>
    `;
    
    // Update form action
    document.getElementById('rejectForm').action = `/admin/leaves/${leaveId}/reject`;
    
    // Clear previous values
    document.getElementById('reject_admin_remarks').value = '';
    document.getElementById('reject_admin_remarks').classList.remove('is-invalid');
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Function to show SweetAlert
    function showAlert(icon, title, text = '') {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            timer: 3000,
            showConfirmButton: false
        });
    }

    // Handle approve form submission
    document.getElementById('approveForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const textarea = document.getElementById('approve_admin_remarks');
        const submitBtn = document.getElementById('approveBtn');
        const spinner = submitBtn.querySelector('.spinner-border');
        const btnText = submitBtn.querySelector('.btn-text');
        
        // Client-side validation
        if (!textarea.value.trim()) {
            textarea.classList.add('is-invalid');
            showAlert('error', 'Validation Error', 'Please provide approval remarks.');
            return;
        }
        
        if (textarea.value.trim().length < 10) {
            textarea.classList.add('is-invalid');
            showAlert('error', 'Validation Error', 'Approval remarks must be at least 10 characters long.');
            return;
        }
        
        if (textarea.value.trim().length > 500) {
            textarea.classList.add('is-invalid');
            showAlert('error', 'Validation Error', 'Approval remarks cannot exceed 500 characters.');
            return;
        }
        
        // Remove validation classes
        textarea.classList.remove('is-invalid');
        
        const formData = new FormData(this);
        
        // Show loading state
        spinner.classList.remove('d-none');
        btnText.textContent = 'Processing...';
        submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('approveModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Show success message
                showAlert('success', 'Success!', 'Leave request approved successfully! Email notification sent to employee.');
                
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('error', 'Error', data.error || 'An error occurred while approving the leave request.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Network Error', 'An error occurred while approving the leave request.');
        })
        .finally(() => {
            // Reset button state
            spinner.classList.add('d-none');
            btnText.textContent = 'Approve Request';
            submitBtn.disabled = false;
        });
    });

    // Handle reject form submission
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const textarea = document.getElementById('reject_admin_remarks');
        const submitBtn = document.getElementById('rejectBtn');
        const spinner = submitBtn.querySelector('.spinner-border');
        const btnText = submitBtn.querySelector('.btn-text');
        
        // Client-side validation
        if (!textarea.value.trim()) {
            textarea.classList.add('is-invalid');
            showAlert('error', 'Validation Error', 'Please provide a reason for rejection.');
            return;
        }
        
        if (textarea.value.trim().length < 10) {
            textarea.classList.add('is-invalid');
            showAlert('error', 'Validation Error', 'Rejection reason must be at least 10 characters long.');
            return;
        }
        
        if (textarea.value.trim().length > 500) {
            textarea.classList.add('is-invalid');
            showAlert('error', 'Validation Error', 'Rejection reason cannot exceed 500 characters.');
            return;
        }
        
        // Remove validation classes
        textarea.classList.remove('is-invalid');
        
        const formData = new FormData(this);
        
        // Show loading state
        spinner.classList.remove('d-none');
        btnText.textContent = 'Processing...';
        submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Show success message
                showAlert('success', 'Success!', 'Leave request rejected successfully! Email notification sent to employee.');
                
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('error', 'Error', data.error || 'An error occurred while rejecting the leave request.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Network Error', 'An error occurred while rejecting the leave request.');
        })
        .finally(() => {
            // Reset button state
            spinner.classList.add('d-none');
            btnText.textContent = 'Reject Request';
            submitBtn.disabled = false;
        });
    });
});
</script>
@endsection
