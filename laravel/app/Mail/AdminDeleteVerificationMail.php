<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminDeleteVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $updaterName;

    /**
     * Create a new message instance.
     */
    public function __construct($code, $updaterName)
    {
        $this->code = $code;
        $this->updaterName = $updaterName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CRITICAL: Kyusify Admin Account Deletion Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-delete-verification',
            with: [
                'code' => $this->code,
                'updaterName' => $this->updaterName,
            ],
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
