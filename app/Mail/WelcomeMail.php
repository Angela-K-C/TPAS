<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $status)
    {
        $this->userName = $userName;
        $this->status = $status;
    }

    /**
     * Get the message envelope [Header information for emailW]
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Temporary Pass Application $this->status",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status',
            with: [
                'userName' => $this->userName,
                'status'   => $this->status,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
