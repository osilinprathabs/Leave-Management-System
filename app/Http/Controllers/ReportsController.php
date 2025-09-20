<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Admin');
    }

    public function index()
    {
        $statistics = [
            'total_requests' => LeaveRequest::count(),
            'pending_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'rejected_requests' => LeaveRequest::where('status', LeaveRequest::STATUS_REJECTED)->count(),
            'this_month_requests' => LeaveRequest::whereMonth('created_at', now()->month)->count(),
            'total_employees' => User::role('Employee')->count(),
        ];
        
        return view('admin.reports.index', compact('statistics'));
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $leaves = LeaveRequest::with('user')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();
            
        $statistics = [
            'total' => $leaves->count(),
            'pending' => $leaves->where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved' => $leaves->where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'rejected' => $leaves->where('status', LeaveRequest::STATUS_REJECTED)->count(),
        ];
        
        return view('admin.reports.monthly', compact('leaves', 'statistics', 'month', 'year'));
    }

    public function userReport(Request $request)
    {
        $userId = $request->get('user_id');
        
        if ($userId) {
            $user = User::findOrFail($userId);
            $leaves = $user->leaveRequests()->latest()->get();
        } else {
            $leaves = collect();
            $user = null;
        }
        
        $employees = User::role('Employee')->select('id', 'name', 'email')->get();
        
        return view('admin.reports.user', compact('leaves', 'user', 'employees'));
    }

    public function statusReport(Request $request)
    {
        $status = $request->get('status', LeaveRequest::STATUS_PENDING);
        
        $leaves = LeaveRequest::with('user')
            ->where('status', $status)
            ->latest()
            ->get();
            
        $statuses = LeaveRequest::getStatuses();
        
        return view('admin.reports.status', compact('leaves', 'status', 'statuses'));
    }

    public function yearlyOverview(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = [
                'month' => Carbon::create()->month($i)->format('F'),
                'total' => LeaveRequest::whereMonth('created_at', $i)->whereYear('created_at', $year)->count(),
                'approved' => LeaveRequest::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('status', LeaveRequest::STATUS_APPROVED)->count(),
                'pending' => LeaveRequest::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('status', LeaveRequest::STATUS_PENDING)->count(),
                'rejected' => LeaveRequest::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('status', LeaveRequest::STATUS_REJECTED)->count(),
            ];
        }
        
        return view('admin.reports.yearly', compact('monthlyData', 'year'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'all');
        $format = $request->get('format', 'csv');
        
        $query = LeaveRequest::with('user');
        
        if ($type === 'pending') {
            $query->where('status', LeaveRequest::STATUS_PENDING);
        } elseif ($type === 'approved') {
            $query->where('status', LeaveRequest::STATUS_APPROVED);
        } elseif ($type === 'rejected') {
            $query->where('status', LeaveRequest::STATUS_REJECTED);
        }
        
        $leaves = $query->latest()->get();
        
        if ($format === 'csv') {
            $filename = 'leave_requests_' . $type . '_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($leaves) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Employee', 'Email', 'Type', 'Start Date', 'End Date', 'Duration', 'Status', 'Reason', 'Admin Remarks', 'Created At']);
                
                foreach ($leaves as $leave) {
                    fputcsv($file, [
                        $leave->user->name,
                        $leave->user->email,
                        $leave->type,
                        $leave->start_date->format('Y-m-d'),
                        $leave->end_date->format('Y-m-d'),
                        $leave->duration,
                        $leave->status,
                        $leave->reason,
                        $leave->admin_remarks,
                        $leave->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return back()->with('error', 'Export format not supported.');
    }
}
