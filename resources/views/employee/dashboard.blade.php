@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-tachometer-alt"></i> Employee Dashboard</h1>
        <div class="d-flex align-items-center">
          
            <a href="{{ route('leaves.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Request New Leave
            </a>
            <a href="{{ route('leaves.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm me-2">
                <i class="fas fa-list fa-sm text-white-50"></i> View My Leaves
            </a>
            <span class="me-3 text-muted">
                <i class="fas fa-user me-1"></i>Welcome, <strong>{{ auth()->user()->name }}</strong>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" onclick="return confirmLogout()">
                    <i class="fas fa-sign-out-alt fa-sm text-white-50"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Leave Balance Summary Row -->
    <div class="row">
        <!-- Annual Leave Balance Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Annual Leave Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['annual'] }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

  

        <!-- Total Used Leave Days Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Leave Used (This Year)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['total_used'] }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plane-departure fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Request Status Overview -->
    <div class="row">
        <!-- Pending Requests Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Requests Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Available Leave Days Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Available Leave Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $leaveBalances['annual']  }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-umbrella-beach fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leave Requests Table -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history"></i> My Recent Leave Requests</h6>
                    <a href="{{ route('leaves.index') }}" class="btn btn-primary btn-sm">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    @if($leaves->isEmpty())
                        <p class="text-center">You have no recent leave requests.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Dates</th>
                                        <th>Duration</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Admin Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->type }}</td>
                                            <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                            <td>{{ $leave->duration }} days</td>
                                            <td>{{ Str::limit($leave->reason, 50) }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($leave->status == 'Pending') bg-warning text-dark
                                                    @elseif($leave->status == 'Approved') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ $leave->status }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($leave->admin_remarks ?? 'N/A', 50) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
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
@endsection
