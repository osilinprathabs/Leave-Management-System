<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Events\LeaveRequestStatusUpdated;
use Illuminate\Http\Request;

class AdminLeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Admin');
    }

    public function index(Request $request)
    {
        $query = LeaveRequest::with('user');

        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->employee) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->employee}%")
                  ->orWhere('email', 'like', "%{$request->employee}%");
            });
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }

        $leaves = $query->latest()->paginate(15);
        
        // Get statistics
        $statistics = [
            'total_requests' => LeaveRequest::count(),
            'pending_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'rejected_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_REJECTED)->count(),
            'this_month_requests' => LeaveRequest::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Get employees for filter dropdown
        $employees = User::role('Employee')->select('id', 'name', 'email')->get();
        
        // Get statuses for filter dropdown
        $statuses = LeaveRequest::getStatuses();
        
        return view('admin.leaves.index', compact('leaves', 'statistics', 'employees', 'statuses'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load('user');
        return view('admin.leaves.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== LeaveRequest::STATUS_PENDING) {
            return back()->withErrors(['status' => 'Only pending requests can be approved.']);
        }

        $previousStatus = $leaveRequest->status;
        $leaveRequest->update(['status' => LeaveRequest::STATUS_APPROVED]);
        
        // Dispatch event for email notification
        event(new LeaveRequestStatusUpdated($leaveRequest, $previousStatus));

        return back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'admin_remarks' => 'required|string|max:500'
        ]);

        if ($leaveRequest->status !== LeaveRequest::STATUS_PENDING) {
            return back()->withErrors(['status' => 'Only pending requests can be rejected.']);
        }

        $previousStatus = $leaveRequest->status;
        $leaveRequest->update([
            'status' => LeaveRequest::STATUS_REJECTED,
            'admin_remarks' => $request->admin_remarks,
        ]);
        
        // Dispatch event for email notification
        event(new LeaveRequestStatusUpdated($leaveRequest, $previousStatus));

        return back()->with('success', 'Leave request rejected successfully.');
    }
}
