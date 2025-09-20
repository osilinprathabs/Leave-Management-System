@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section with Logout Button -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tachometer-alt text-primary"></i> Employee Dashboard
                </h1>
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
                    <div class="small text-muted">Welcome back, {{ auth()->user()->name }}!</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balance Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Annual Leave</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['annual'] ?? 0 }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Used</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['total_used'] ?? 0 }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
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
                            <button type="button" class="btn btn-primary btn-block btn-lg" data-bs-toggle="modal" data-bs-target="#quickLeaveModal">
                                <i class="fas fa-plus fa-2x mb-2"></i><br>
                                <strong>Quick Leave Request</strong><br>
                                <small>Submit a new leave request</small>
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('leaves.create') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fas fa-edit fa-2x mb-2"></i><br>
                                <strong>Detailed Request</strong><br>
                                <small>Create detailed leave request</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('leaves.index') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fas fa-list fa-2x mb-2"></i><br>
                                <strong>My Leave History</strong><br>
                                <small>View all your leave requests</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('leaves.index', ['status' => 'Pending']) }}" class="btn btn-warning btn-block btn-lg">
                                <i class="fas fa-clock fa-2x mb-2"></i><br>
                                <strong>Pending Requests</strong><br>
                                <small>{{ $pendingCount ?? 0 }} awaiting review</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leave Requests -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Recent Leave Requests
                    </h6>
                    <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td><span class="badge badge-secondary">{{ $leave->type }}</span></td>
                                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
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
                                            <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No leave requests yet</h6>
                            <p class="text-muted">Click "Quick Leave Request" to submit your first request.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Leave Request Modal -->
<div class="modal fade" id="quickLeaveModal" tabindex="-1" aria-labelledby="quickLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickLeaveModalLabel">
                    <i class="fas fa-plus-circle"></i> Quick Leave Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickLeaveForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quick_type" class="form-label">Leave Type *</label>
                            <select name="type" id="quick_type" class="form-select" required>
                                <option value="">Select Leave Type</option>
                                <option value="Sick">Sick Leave</option>
                                <option value="Casual">Casual Leave</option>
                                <option value="Annual">Annual Leave</option>
                                <option value="Maternity">Maternity Leave</option>
                                <option value="Paternity">Paternity Leave</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quick_duration" class="form-label">Duration (Days) *</label>
                            <input type="number" name="duration" id="quick_duration" class="form-control" min="1" max="30" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quick_start_date" class="form-label">Start Date *</label>
                            <input type="date" name="start_date" id="quick_start_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quick_end_date" class="form-label">End Date *</label>
                            <input type="date" name="end_date" id="quick_end_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="quick_reason" class="form-label">Reason (Optional)</label>
                            <textarea name="reason" id="quick_reason" class="form-control" rows="3" placeholder="Brief reason for leave request"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="quickSubmitBtn">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="quickSuccessModal" tabindex="-1" aria-labelledby="quickSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="quickSuccessModalLabel">
                    <i class="fas fa-check-circle"></i> Success!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>Leave Request Submitted!</h4>
                <p class="text-muted">Your leave request has been submitted successfully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.text-primary { color: #4e73df !important; }
.text-success { color: #1cc88a !important; }
.text-info { color: #36b9cc !important; }
.text-warning { color: #f6c23e !important; }
.badge-primary { background-color: #4e73df; }
.badge-success { background-color: #1cc88a; }
.badge-info { background-color: #36b9cc; }
.badge-warning { background-color: #f6c23e; }
.badge-danger { background-color: #e74a3b; }
.badge-secondary { background-color: #858796; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quickForm = document.getElementById('quickLeaveForm');
    const quickSubmitBtn = document.getElementById('quickSubmitBtn');
    const startDate = document.getElementById('quick_start_date');
    const endDate = document.getElementById('quick_end_date');
    const duration = document.getElementById('quick_duration');

    // Auto-calculate end date based on start date and duration
    function calculateEndDate() {
        if (startDate.value && duration.value) {
            const start = new Date(startDate.value);
            const days = parseInt(duration.value);
            const end = new Date(start);
            end.setDate(start.getDate() + days - 1);
            endDate.value = end.toISOString().split('T')[0];
        }
    }

    // Auto-calculate duration based on start and end dates
    function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            duration.value = diffDays;
        }
    }

    startDate.addEventListener('change', calculateEndDate);
    duration.addEventListener('change', calculateEndDate);
    endDate.addEventListener('change', calculateDuration);

    // Handle quick form submission
    quickForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        quickSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        quickSubmitBtn.disabled = true;
        
        // Submit the form
        fetch('{{ route("leaves.store") }}', {
            method: 'POST',
            body: new FormData(quickForm),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                // Close the quick modal
                const quickModal = bootstrap.Modal.getInstance(document.getElementById('quickLeaveModal'));
                quickModal.hide();
                
                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById('quickSuccessModal'));
                successModal.show();
                
                // Reset form and reload page after success modal is closed
                document.getElementById('quickSuccessModal').addEventListener('hidden.bs.modal', function() {
                    quickForm.reset();
                    window.location.reload();
                });
            } else {
                throw new Error('Server error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the leave request. Please try again.');
        })
        .finally(() => {
            // Reset button state
            quickSubmitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Request';
            quickSubmitBtn.disabled = false;
        });
    });
});
</script>
@endsection
