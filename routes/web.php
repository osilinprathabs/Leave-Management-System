<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Admin\EmployeeController;

// Home route
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// Dashboard route - redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('employee.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Employee routes
Route::middleware(['auth', 'role:Employee'])->group(function () {
    Route::get('/employee/dashboard', function () {
        $user = auth()->user();
        $leaves = $user->leaveRequests()->latest()->take(5)->get();
        $leaveBalances = [
            'annual' => $user->getAnnualLeaveBalance(),
            'sick' => $user->getSickLeaveBalance(),
            'casual' => $user->getCasualLeaveBalance(),
            'total_used' => $user->getTotalUsedLeaveDays(),
        ];
        $pendingCount = $user->pendingLeaveRequests()->count();
        $approvedCount = $user->approvedLeaveRequests()->count();
        
        return view('employee.dashboard', compact('leaves', 'leaveBalances', 'pendingCount', 'approvedCount'));
    })->name('employee.dashboard');
    
    Route::get('/leaves', [LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveRequestController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveRequestController::class, 'store'])->name('leaves.store');
});

// Admin routes
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $statistics = [
            'total_requests' => \App\Models\LeaveRequest::count(),
            'pending_requests' => \App\Models\LeaveRequest::where('status', 'Pending')->count(),
            'approved_requests' => \App\Models\LeaveRequest::where('status', 'Approved')->count(),
            'rejected_requests' => \App\Models\LeaveRequest::where('status', 'Rejected')->count(),
            'total_employees' => \App\Models\User::role('Employee')->count(),
            'this_month_requests' => \App\Models\LeaveRequest::whereMonth('created_at', date('m'))->count(),
        ];
        
        $recentLeaves = \App\Models\LeaveRequest::with('user')->latest()->take(5)->get();
        $pendingLeaves = \App\Models\LeaveRequest::with('user')->where('status', 'Pending')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('statistics', 'recentLeaves', 'pendingLeaves'));
    })->name('dashboard');
    
    // Leave Management
    Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/{leaveRequest}', [AdminLeaveController::class, 'show'])->name('leaves.show');
    Route::patch('/leaves/{leaveRequest}/approve', [AdminLeaveController::class, 'approve'])->name('leaves.approve');
    Route::patch('/leaves/{leaveRequest}/reject', [AdminLeaveController::class, 'reject'])->name('leaves.reject');
    
    // Employee Management
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/reset-password', [EmployeeController::class, 'resetPassword'])->name('employees.reset-password');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly', [ReportsController::class, 'monthlyReport'])->name('reports.monthly');
    Route::get('/reports/user', [ReportsController::class, 'userReport'])->name('reports.user');
    Route::get('/reports/status', [ReportsController::class, 'statusReport'])->name('reports.status');
    Route::get('/reports/yearly', [ReportsController::class, 'yearlyOverview'])->name('reports.yearly');
    Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
});
