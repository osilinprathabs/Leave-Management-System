<?php

namespace App\Listeners;

use App\Events\LeaveRequestSubmitted;
use App\Mail\LeaveSubmitted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLeaveRequestNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LeaveRequestSubmitted $event): void
    {
        $leaveRequest = $event->leaveRequest;
        
        // Get all admin users
        $admins = User::role('Admin')->get();
        
        // Send email to each admin
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new LeaveSubmitted($leaveRequest));
        }
    }
}
