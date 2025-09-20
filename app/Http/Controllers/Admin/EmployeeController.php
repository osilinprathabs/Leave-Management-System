<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Admin');
    }

    public function index()
    {
        $employees = User::role('Employee')->with('leaveRequests')->paginate(15);
        
        $statistics = [
            'total_employees' => User::role('Employee')->count(),
            'active_employees' => User::role('Employee')->where('email_verified_at', '!=', null)->count(),
            'total_leave_requests' => \App\Models\LeaveRequest::count(),
            'pending_requests' => \App\Models\LeaveRequest::where('status', 'Pending')->count(),
        ];
        
        return view('admin.employees.index', compact('employees', 'statistics'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
       
        ]);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
          
        ]);

        $employee->assignRole('Employee');

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(User $employee)
    {
        $employee->load('leaveRequests');
        
        $leaveStats = [
            'total_requests' => $employee->leaveRequests()->count(),
            'pending_requests' => $employee->leaveRequests()->where('status', 'Pending')->count(),
            'approved_requests' => $employee->leaveRequests()->where('status', 'Approved')->count(),
            'rejected_requests' => $employee->leaveRequests()->where('status', 'Rejected')->count(),
            'total_used_days' => $employee->getTotalUsedLeaveDays(),
        ];
        
        return view('admin.employees.show', compact('employee', 'leaveStats'));
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
 
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        // Check if employee has any leave requests
        if ($employee->leaveRequests()->count() > 0) {
            return back()->withErrors(['employee' => 'Cannot delete employee with existing leave requests.']);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function resetPassword(Request $request, User $employee)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $employee->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Employee password reset successfully.');
    }
}
