<?php

namespace App\Services;

use App\Mail\ReunionConvocation;
use App\Models\Reunion;
use App\Notifications\ReunionPlanifiee;
use App\Notifications\ReunionTerminee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Notifications des réunions : canaux mail + base (principes du package).
 * Les erreurs d'envoi ne bloquent jamais le flux métier (log uniquement).
 */
class NotificationService
{
    public function sendReunionPlanifiee(Reunion $reunion): void
    {
        $invites = $reunion->invitations()
            ->with('participant')
            ->get()
            ->filter(fn ($inv) => $inv->participant !== null);

        foreach ($invites as $invitation) {
            try {
                $user = $invitation->participant;
                $user->notify(new ReunionPlanifiee($reunion));
                Mail::to($user->email)->queue(new ReunionConvocation($reunion, $user));
                $invitation->update(['sent_at' => now()]);
            } catch (\Exception $e) {
                Log::warning("Notification de convocation échouée pour {$user->email}: ".$e->getMessage());
            }
        }
    }

    public function sendReunionTerminee(Reunion $reunion): void
    {
        $destinataires = collect([$reunion->commission?->president])
            ->filter();

        foreach ($destinataires as $user) {
            try {
                $user->notify(new ReunionTerminee($reunion));
            } catch (\Exception $e) {
                Log::warning('Notification de fin de réunion échouée: '.$e->getMessage());
            }
        }
    }
}
