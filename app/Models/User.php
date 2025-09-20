<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    // Leave balance constants
    const MAX_ANNUAL_LEAVE_DAYS = 20;
    const MAX_SICK_LEAVE_DAYS = 10;
    const MAX_CASUAL_LEAVE_DAYS = 15;

    protected $fillable = [
        'name',
        'email',
        'password',
        'annual_leave_balance',
        'sick_leave_balance',
        'casual_leave_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Leave balance methods
    public function getAnnualLeaveBalance()
    {
        return $this->annual_leave_balance ?? self::MAX_ANNUAL_LEAVE_DAYS;
    }

    public function getSickLeaveBalance()
    {
        return $this->sick_leave_balance ?? self::MAX_SICK_LEAVE_DAYS;
    }

    public function getCasualLeaveBalance()
    {
        return $this->casual_leave_balance ?? self::MAX_CASUAL_LEAVE_DAYS;
    }

    public function getTotalUsedLeaveDays()
    {
        return $this->leaveRequests()
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->sum('duration');
    }

    public function canTakeLeave($type, $duration)
    {
        switch ($type) {
            case LeaveRequest::TYPE_ANNUAL:
                return $this->getAnnualLeaveBalance() >= $duration;
            case LeaveRequest::TYPE_SICK:
                return $this->getSickLeaveBalance() >= $duration;
            case LeaveRequest::TYPE_CASUAL:
                return $this->getCasualLeaveBalance() >= $duration;
            case LeaveRequest::TYPE_MATERNITY:
            case LeaveRequest::TYPE_PATERNITY:
                return true; // No limit for these types
            default:
                return false;
        }
    }

    // Helper methods
    public function pendingLeaveRequests()
    {
        return $this->leaveRequests()->where('status', LeaveRequest::STATUS_PENDING);
    }

    public function approvedLeaveRequests()
    {
        return $this->leaveRequests()->where('status', LeaveRequest::STATUS_APPROVED);
    }

    public function rejectedLeaveRequests()
    {
        return $this->leaveRequests()->where('status', LeaveRequest::STATUS_REJECTED);
    }

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function isEmployee()
    {
        return $this->hasRole('Employee');
    }
}
