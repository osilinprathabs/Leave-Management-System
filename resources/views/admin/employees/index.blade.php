@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-users"></i> Manage Employees</h1>
        <div class="d-flex">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Employee
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_employees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['active_employees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Leave Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_leave_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending_requests'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Employees</h6>
        </div>
        <div class="card-body">
            @if($employees->isEmpty())
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i> No employees found.
                    <a href="{{ route('admin.employees.create') }}" class="alert-link">Add the first employee!</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Requests</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->leaveRequests->count() }}</td>
                                    <td>
                                        <span class="badge {{ $employee->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                            {{ $employee->email_verified_at ? 'Active' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.employees.show', $employee) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteEmployee({{ $employee->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $employees->links() }}
            @endif
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
.text-primary { color: #4e73df!important; }
.text-success { color: #1cc88a!important; }
.text-info { color: #36b9cc!important; }
.text-warning { color: #f6c23e!important; }
.text-gray-300 { color: #dddfeb!important; }
.text-gray-800 { color: #5a5c69!important; }
</style>
@endsection
