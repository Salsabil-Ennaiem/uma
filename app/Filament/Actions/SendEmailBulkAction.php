<?php

namespace App\Filament\Actions;

use App\Mail\FilteredListEmail;
use App\Models\User;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Action bulk « Envoyer un email » à la liste filtrée/sélectionnée.
 *
 * - Re-cadre sur les Policies : n'expédie jamais un email à un utilisateur
 *   que l'acteur n'a pas le droit de voir (aucun contournement de Policy).
 * - Tags supportés : {{name}}, {{email}}, {{role}}.
 * - Envoi via file de jobs ; une erreur d'envoi ne bloque jamais le flux.
 */
class SendEmailBulkAction extends BulkAction
{
    public static function make(?string $name = null): static
    {
        $name ??= 'sendEmail';

        return parent::make($name)
            ->label('Envoyer un email à la sélection')
            ->icon('heroicon-o-envelope')
            ->color('info')
            ->deselectRecordsAfterCompletion()
            ->modalHeading('Envoi d\'email personnalisé')
            ->modalDescription('Les tags {{name}}, {{email}} et {{role}} sont remplacés pour chaque destinataire.')
            ->form([
                TextInput::make('subject')
                    ->label('Sujet')
                    ->required()
                    ->string()
                    ->maxLength(180),
                Textarea::make('body')
                    ->label('Corps du message')
                    ->required()
                    ->string()
                    ->rows(8),
            ])
            ->action(function (EloquentCollection $records, array $data): void {
                $destinataires = $records
                    ->filter(fn (User $user) => Gate::forUser(auth()->user())->allows('view', $user))
                    ->filter(fn (User $user) => filled($user->email))
                    ->values();

                foreach ($destinataires as $user) {
                    try {
                        Mail::to($user->email)->queue(new FilteredListEmail(
                            subjectLine: static::resolveTags($data['subject'], $user),
                            bodyHtml: nl2br(e(static::resolveTags($data['body'], $user))),
                        ));
                    } catch (\Throwable $e) {
                        Log::warning("Envoi d'email de masse échoué vers {$user->email}: {$e->getMessage()}");
                    }
                }

                if ($destinataires->isEmpty()) {
                    Notification::make()
                        ->title('Aucun destinataire éligible dans la sélection.')
                        ->danger()
                        ->send();

                    return;
                }

                Log::info('Envoi email filtré', [
                    'actor_id' => auth()->id(),
                    'destinataires' => $destinataires->pluck('email')->all(),
                    'sujet' => $data['subject'] ?? null,
                ]);

                Notification::make()
                    ->title('Emails envoyés à '.$destinataires->count().' destinataire(s).')
                    ->success()
                    ->send();
            });
    }

    private static function resolveTags(string $template, User $user): string
    {
        return str_replace(
            ['{{name}}', '{{email}}', '{{role}}'],
            [
                $user->name,
                $user->email,
                $user->role?->label() ?? '',
            ],
            $template,
        );
    }
}