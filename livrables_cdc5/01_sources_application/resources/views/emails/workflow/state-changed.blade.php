<x-mail::message>
# Mise à jour du workflow

{{ $message }}

- **Workflow** : {{ $instance->definition?->name }}
- **État actuel** : {{ $instance->current_state }}

Merci de vous connecter à la plateforme pour suivre l'avancement de votre dossier.

{{ config('app.name') }}
</x-mail::message>