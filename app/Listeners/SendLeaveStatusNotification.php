<?php

namespace App\Listeners;

use App\Events\LeaveRequestStatusUpdated;
use App\Mail\LeaveStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLeaveStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LeaveRequestStatusUpdated $event): void
    {
        $leaveRequest = $event->leaveRequest;
        
        // Send email to the employee
        Mail::to($leaveRequest->user->email)->send(new LeaveStatusUpdated($leaveRequest));
    }
}
