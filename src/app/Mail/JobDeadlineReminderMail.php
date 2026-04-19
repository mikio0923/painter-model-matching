<?php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobDeadlineReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Job $job) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【' . config('app.name') . '】依頼の応募期限が近づいています',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-deadline-reminder',
        );
    }
}
