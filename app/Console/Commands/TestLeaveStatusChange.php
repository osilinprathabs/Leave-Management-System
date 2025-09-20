<?php

namespace App\Console\Commands;

use App\Events\LeaveRequestStatusUpdated;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Console\Command;

class TestLeaveStatusChange extends Command
{
    protected $signature = 'test:leave-status-change';
    protected $description = 'Test leave status change event and email notification';

    public function handle()
    {
        // Find an existing leave request or create one
        $leaveRequest = LeaveRequest::first();
        
        if (!$leaveRequest) {
            // Create a test leave request
            $user = User::role('Employee')->first();
            if (!$user) {
                $this->error('No employee users found. Please run php artisan db:seed first.');
                return;
            }

            $leaveRequest = LeaveRequest::create([
                'user_id' => $user->id,
                'type' => 'Annual',
                'start_date' => now(),
                'end_date' => now()->addDays(3),
                'reason' => 'Test leave request for status change testing',
                'status' => 'Pending',
                'duration' => 3,
            ]);
        }

        $this->info("Testing leave status change for leave request ID: {$leaveRequest->id}");
        $this->info("Employee: {$leaveRequest->user->name} ({$leaveRequest->user->email})");
        $this->info("Current status: {$leaveRequest->status}");

        // Simulate status change
        $previousStatus = $leaveRequest->status;
        $leaveRequest->update(['status' => 'Approved']);

        // Fire the event
        event(new LeaveRequestStatusUpdated($leaveRequest, $previousStatus));

        $this->info("✅ Event fired successfully!");
        $this->info("Status changed from '{$previousStatus}' to '{$leaveRequest->status}'");
        $this->info("📧 Email notification should be sent to: {$leaveRequest->user->email}");
    }
}
