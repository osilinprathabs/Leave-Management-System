<?php

namespace App\Console\Commands;

use App\Mail\LeaveStatusUpdated;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test email functionality by sending a test email';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Create a test leave request
        $user = User::first();
        if (!$user) {
            $this->error('No users found in database. Please run php artisan db:seed first.');
            return;
        }

        $leaveRequest = new LeaveRequest([
            'user_id' => $user->id,
            'type' => 'Annual',
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'reason' => 'Test leave request for email testing',
            'status' => 'Approved',
            'duration' => 5,
        ]);

        try {
            Mail::to($email)->send(new LeaveStatusUpdated($leaveRequest));
            $this->info("Test email sent successfully to {$email}");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }
    }
}
