<?php

namespace App\Notifications;

use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReunionPlanifiee extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reunion $reunion) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reunion_id' => $this->reunion->getKey(),
            'objet' => $this->reunion->objet,
            'date_debut' => $this->reunion->date_debut?->toIso8601String(),
            'lieu' => $this->reunion->lieu,
            'message' => 'Vous êtes convoqué(e) à une réunion : '.$this->reunion->objet,
        ];
    }
}
