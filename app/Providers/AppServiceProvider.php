<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\LeaveRequestSubmitted;
use App\Events\LeaveRequestStatusUpdated;
use App\Listeners\SendLeaveRequestNotification;
use App\Listeners\SendLeaveStatusNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(LeaveRequestSubmitted::class, SendLeaveRequestNotification::class);
        Event::listen(LeaveRequestStatusUpdated::class, SendLeaveStatusNotification::class);
    }
}
