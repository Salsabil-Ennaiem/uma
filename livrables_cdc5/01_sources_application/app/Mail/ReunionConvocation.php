<?php

namespace App\Mail;

use App\Models\Reunion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReunionConvocation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Reunion $reunion,
        public User $invite,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convocation — '.$this->reunion->objet,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reunion.convocation',
            with: [
                'reunion' => $this->reunion,
                'invite' => $this->invite,
            ],
        );
    }
}
