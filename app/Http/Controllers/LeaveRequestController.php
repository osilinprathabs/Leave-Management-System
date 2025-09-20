<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use App\Models\LeaveRequest;
use App\Events\LeaveRequestSubmitted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Employee');
    }

    public function index()
    {
        $user = Auth::user();
        $leaves = $user->leaveRequests()->latest()->get();
        
        // Get leave balances
        $leaveBalances = [
            'annual' => $user->getAnnualLeaveBalance(),
            'sick' => $user->getSickLeaveBalance(),
            'casual' => $user->getCasualLeaveBalance(),
            'total_used' => $user->getTotalUsedLeaveDays(),
        ];
        
        return view('leaves.index', compact('leaves', 'leaveBalances'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Get leave balances
        $leaveBalances = [
            'annual' => $user->getAnnualLeaveBalance(),
            'sick' => $user->getSickLeaveBalance(),
            'casual' => $user->getCasualLeaveBalance(),
        ];
        
        // Get leave types
        $leaveTypes = LeaveRequest::getLeaveTypes();
        
        return view('leaves.create', compact('leaveBalances', 'leaveTypes'));
    }

    public function store(StoreLeaveRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        
        // Calculate duration
        $duration = Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;

        // Validate max 30 days
        if ($duration > 30) {
            return back()->withErrors(['end_date' => 'Leave cannot exceed 30 days.']);
        }

        // Check leave balance
        if (!$user->canTakeLeave($data['type'], $duration)) {
            return back()->withErrors(['type' => 'Insufficient leave balance for this leave type.']);
        }

        // Check for overlapping leaves
        if (LeaveRequest::hasOverlappingLeave($user->id, $data['start_date'], $data['end_date'])) {
            return back()->withErrors(['start_date' => 'Leave overlaps with an existing approved leave.']);
        }

        // Create leave request
        $leave = $user->leaveRequests()->create([
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
            'status' => LeaveRequest::STATUS_PENDING,
            'duration' => $duration,
        ]);

        // Dispatch event for email notification
        event(new LeaveRequestSubmitted($leave));

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }
}
