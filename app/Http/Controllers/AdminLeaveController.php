<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Events\LeaveRequestStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        if ($request->status && $request->status !== 'All') {
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
        
        $statistics = [
            'total_requests' => LeaveRequest::count(),
            'pending_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'rejected_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_REJECTED)->count(),
            'this_month_requests' => LeaveRequest::whereMonth('created_at', now()->month)->count(),
        ];
        
        $employees = User::role('Employee')->select('id', 'name', 'email')->get();
        $statuses = LeaveRequest::getStatuses();
        
        return view('admin.leaves.index', compact('leaves', 'statistics', 'employees', 'statuses'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load('user');
        return view('admin.leaves.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        try {
            $validated = $request->validate([
                'admin_remarks' => 'required|string|min:10|max:500'
            ], [
                'admin_remarks.required' => 'Approval remarks are mandatory. Please provide a reason for approving this leave request.',
                'admin_remarks.min' => 'Approval remarks must be at least 10 characters long.',
                'admin_remarks.max' => 'Approval remarks may not exceed 500 characters.',
            ]);

            if ($leaveRequest->status !== LeaveRequest::STATUS_PENDING) {
                return response()->json(['error' => 'Only pending requests can be approved.'], 400);
            }

            $previousStatus = $leaveRequest->status;
            
            DB::transaction(function () use ($leaveRequest, $validated, $previousStatus) {
                $leaveRequest->update([
                    'status' => LeaveRequest::STATUS_APPROVED,
                    'admin_remarks' => $validated['admin_remarks'],
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                
                event(new LeaveRequestStatusUpdated($leaveRequest, $previousStatus));
            });

            Log::info('Leave request approved', [
                'leave_id' => $leaveRequest->id,
                'user_id' => $leaveRequest->user_id,
                'admin_id' => auth()->id(),
                'remarks' => substr($validated['admin_remarks'], 0, 100)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request from ' . $leaveRequest->user->name . ' approved successfully.',
                'data' => [
                    'status' => 'Approved',
                    'admin_remarks' => $validated['admin_remarks'],
                    'updated_at' => now()->format('M d, Y H:i')
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation error during leave approval: " . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error approving leave request: " . $e->getMessage(), [
                'leave_id' => $leaveRequest->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred while approving the leave request.'], 500);
        }
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        try {
            $validated = $request->validate([
                'admin_remarks' => 'required|string|min:10|max:500'
            ], [
                'admin_remarks.required' => 'Rejection reason is mandatory. Please provide a reason for rejecting this leave request.',
                'admin_remarks.min' => 'Rejection reason must be at least 10 characters long.',
                'admin_remarks.max' => 'Rejection reason may not exceed 500 characters.',
            ]);

            if ($leaveRequest->status !== LeaveRequest::STATUS_PENDING) {
                return response()->json([
                    'success' => false,
                    'error' => 'Only pending requests can be rejected.'
                ], 400);
            }

            $previousStatus = $leaveRequest->status;
            $adminRemarks = trim($validated['admin_remarks']);
            
            DB::transaction(function () use ($leaveRequest, $adminRemarks, $previousStatus) {
                $leaveRequest->update([
                    'status' => LeaveRequest::STATUS_REJECTED,
                    'admin_remarks' => $adminRemarks,
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id(),
                ]);
                
                event(new LeaveRequestStatusUpdated($leaveRequest, $previousStatus));
            });

            Log::info('Leave request rejected', [
                'leave_id' => $leaveRequest->id,
                'user_id' => $leaveRequest->user_id,
                'admin_id' => auth()->id(),
                'duration' => $leaveRequest->duration,
                'type' => $leaveRequest->type,
                'remarks' => substr($adminRemarks, 0, 100)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request from ' . $leaveRequest->user->name . ' rejected successfully.',
                'data' => [
                    'status' => 'Rejected',
                    'admin_remarks' => $adminRemarks,
                    'updated_at' => now()->format('M d, Y H:i')
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation error during leave rejection: " . $e->getMessage(), [
                'leave_id' => $leaveRequest->id,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error rejecting leave request: " . $e->getMessage(), [
                'leave_id' => $leaveRequest->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while rejecting the leave request.'
            ], 500);
        }
    }
}