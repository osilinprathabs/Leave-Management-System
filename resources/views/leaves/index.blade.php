@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-calendar-alt text-primary"></i> My Leave Requests
                </h1>
                <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> New Leave Request
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

             <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Annual Leave</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['annual'] ?? 0 }}</div>
                                    <div class="small text-muted">Available</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Used</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['total_used'] ?? 0 }}</div>
                                    <div class="small text-muted">This Year</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Leave History
                    </h6>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Admin Remarks</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary">{{ $leave->type }}</span>
                                            </td>
                                            <td>{{ $leave->duration }} day{{ $leave->duration > 1 ? 's' : '' }}</td>
                                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                            <td>{{ Str::limit($leave->reason ?? 'No reason provided', 50) }}</td>
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
                                            <td>{{ Str::limit($leave->admin_remarks ?? '-', 30) }}</td>
                                            <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">No Leave Requests Found</h5>
                            <p class="text-muted">You haven't submitted any leave requests yet.</p>
                            <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i> Submit Your First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
