<?php

namespace App\Notifications;

use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReunionTerminee extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reunion $reunion) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Réunion terminée — '.$this->reunion->objet)
            ->line('La réunion « '.$this->reunion->objet.' » est terminée.')
            ->line('Les décisions et le procès-verbal sont en cours de traitement.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reunion_id' => $this->reunion->getKey(),
            'objet' => $this->reunion->objet,
            'message' => 'La réunion « '.$this->reunion->objet.' » est terminée.',
        ];
    }
}
