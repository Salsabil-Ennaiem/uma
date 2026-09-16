<?php

namespace App\Mail;

use App\Models\WorkflowInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkflowStateChanged extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public WorkflowInstance $instance,
        public string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour du workflow — '.$this->instance->definition?->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.workflow.state-changed',
            with: [
                'instance' => $this->instance,
                'message' => $this->message,
            ],
        );
    }
}