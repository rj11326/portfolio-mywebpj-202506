<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '新しい応募が届きました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application_submitted',
            with: [
                'application' => $this->application,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

