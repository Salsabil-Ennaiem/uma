<x-mail::message>
# Convocation à une réunion de commission

Bonjour **{{ $invite->name }}**,

Vous êtes convoqué(e) à la réunion de la commission **{{ $reunion->commission?->nom }}**.

**Objet :** {{ $reunion->objet }}

{{ $reunion->description ? '**Description :** '.$reunion->description : '' }}

**Date :** {{ $reunion->date_debut?->format('d/m/Y à H:i') }}  
**Lieu :** {{ $reunion->lieu ?: '—' }}

## Ordre du jour

{!! nl2br(e($reunion->ordre_du_jour ?: 'À définir')) !!}

Merci de confirmer votre présence.

Cordialement,  
La commission doctorale
</x-mail::message>