<?php

namespace App\Notifications;

use App\Models\WorkflowInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WorkflowTransitioned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public WorkflowInstance $instance,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'workflow_instance_id' => $this->instance->getKey(),
            'definition' => $this->instance->definition?->code,
            'state' => $this->instance->current_state,
            'message' => $this->message,
        ];
    }
}