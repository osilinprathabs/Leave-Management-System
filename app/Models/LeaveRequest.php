<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    use SoftDeletes;

    // Leave types constants
    const TYPE_SICK = 'Sick';
    const TYPE_CASUAL = 'Casual';
    const TYPE_ANNUAL = 'Annual';
    const TYPE_MATERNITY = 'Maternity';
    const TYPE_PATERNITY = 'Paternity';

    // Status constants
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'admin_remarks',
        'duration'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static helper methods
    public static function getLeaveTypes()
    {
        return [
            self::TYPE_SICK => 'Sick Leave',
            self::TYPE_CASUAL => 'Casual Leave',
            self::TYPE_ANNUAL => 'Annual Leave',
            self::TYPE_MATERNITY => 'Maternity Leave',
            self::TYPE_PATERNITY => 'Paternity Leave',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    // Check for overlapping leave requests
    public static function hasOverlappingLeave($userId, $startDate, $endDate, $excludeId = null)
    {
        $query = self::where('user_id', $userId)
            ->where('status', self::STATUS_APPROVED)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($subQ) use ($startDate, $endDate) {
                      $subQ->where('start_date', '<=', $startDate)
                           ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // Get user's yearly leave days
    public static function getUserYearlyLeaveDays($userId, $year = null)
    {
        $year = $year ?? now()->year;
        
        return self::where('user_id', $userId)
            ->where('status', self::STATUS_APPROVED)
            ->whereYear('start_date', $year)
            ->sum('duration');
    }

    // Calculate duration
    public function calculateDuration()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 0;
    }

    // Scopes
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCurrentYear($query, $year = null)
    {
        $year = $year ?? now()->year;
        return $query->whereYear('start_date', $year);
    }

    // Boot method to auto-calculate duration
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($leaveRequest) {
            if ($leaveRequest->start_date && $leaveRequest->end_date) {
                $leaveRequest->duration = $leaveRequest->calculateDuration();
            }
        });
    }
}
