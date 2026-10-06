<?php

namespace App\Mail;

use App\Models\OneTimePassword;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OneTimePasswordMail extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $code,
        public string $purpose,
        public string $recipientName,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->purpose === OneTimePassword::PURPOSE_PASSWORD_RESET
            ? 'Your password reset OTP'
            : 'Your email verification OTP';

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.one-time-password',
            with: [
                'code' => $this->code,
                'purpose' => $this->purpose,
                'recipientName' => $this->recipientName,
                'expiryMinutes' => OneTimePassword::EXPIRY_MINUTES,
            ],
        );
    }
}
