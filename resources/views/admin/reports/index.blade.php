@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-bar text-primary"></i> Reports & Analytics
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Statistics -->
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

    <!-- Report Types -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar"></i> Monthly Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">View leave requests by month and year</p>
                    <a href="{{ route('admin.reports.monthly') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line"></i> View Monthly Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Employee Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">View leave history for specific employees</p>
                    <a href="{{ route('admin.reports.user') }}" class="btn btn-primary">
                        <i class="fas fa-user-chart"></i> View Employee Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter"></i> Status Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Filter leave requests by status</p>
                    <a href="{{ route('admin.reports.status') }}" class="btn btn-primary">
                        <i class="fas fa-filter"></i> View Status Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Yearly Overview
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Annual statistics and trends</p>
                    <a href="{{ route('admin.reports.yearly') }}" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> View Yearly Overview
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-download"></i> Export Data
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Export Leave Requests</h6>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.reports.export', ['type' => 'all', 'format' => 'csv']) }}" class="btn btn-success">
                                    <i class="fas fa-file-csv"></i> All Requests (CSV)
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'pending', 'format' => 'csv']) }}" class="btn btn-warning">
                                    <i class="fas fa-file-csv"></i> Pending (CSV)
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'approved', 'format' => 'csv']) }}" class="btn btn-success">
                                    <i class="fas fa-file-csv"></i> Approved (CSV)
                                </a>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <h6>Quick Actions</h6>
                            <a href="{{ route('admin.leaves.index') }}" class="btn btn-primary">
                                <i class="fas fa-tasks"></i> Manage Leave Requests
                            </a>
                        </div> -->
                    </div>
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
</style>
@endsection
