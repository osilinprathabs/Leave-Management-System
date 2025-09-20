<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Leave Request Submitted - ' . $this->leaveRequest->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-submitted',
            with: [
                'leaveRequest' => $this->leaveRequest,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
