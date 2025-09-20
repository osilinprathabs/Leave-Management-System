<?php

namespace App\Events;

use App\Models\LeaveRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leaveRequest;
    public $previousStatus;

    public function __construct(LeaveRequest $leaveRequest, $previousStatus = null)
    {
        $this->leaveRequest = $leaveRequest;
        $this->previousStatus = $previousStatus;
    }
}
