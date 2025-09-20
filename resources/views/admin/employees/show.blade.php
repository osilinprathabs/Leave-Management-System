@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user"></i> Employee Details</h1>
        <div class="d-flex">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-arrow-left"></i> Back to Employees
            </a>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning btn-sm me-2">
                <i class="fas fa-edit"></i> Edit Employee
            </a>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEmployee({{ $employee->id }})">
                <i class="fas fa-trash"></i> Delete Employee
            </button>
        </div>
    </div>

    <!-- Employee Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employee Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $employee->name }}</p>
                            <p><strong>Email:</strong> {{ $employee->email }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge {{ $employee->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                    {{ $employee->email_verified_at ? 'Active' : 'Pending' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Created:</strong> {{ $employee->created_at->format('M d, Y') }}</p>
                            <p><strong>Last Updated:</strong> {{ $employee->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Leave Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="border-left-primary p-3">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['total_requests'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-left-warning p-3">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['pending_requests'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-left-success p-3">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['approved_requests'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-left-danger p-3">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveStats['rejected_requests'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Requests -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Leave Requests</h6>
                </div>
                <div class="card-body">
                    @if($employee->leaveRequests->isEmpty())
                        <p class="text-center">No leave requests found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->leaveRequests as $leave)
                                        <tr>
                                            <td>{{ $leave->type }}</td>
                                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
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
                                            <td>{{ Str::limit($leave->reason, 50) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reset Password -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reset Password</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employees.reset-password', $employee) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this employee? This action cannot be undone.</p>
                <p class="text-danger"><strong>Note:</strong> Employees with existing leave requests cannot be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteEmployee(employeeId) {
    document.getElementById('deleteForm').action = '/admin/employees/' + employeeId;
    $('#deleteModal').modal('show');
}
</script>

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
</style>
@endsection
