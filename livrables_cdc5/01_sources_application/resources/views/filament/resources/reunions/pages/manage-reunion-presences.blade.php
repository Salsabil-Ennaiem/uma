<x-filament-panels::page>
    <x-filament::section>
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Commission : <strong>{{ $this->record->commission?->nom ?? '—' }}</strong> ·
            Statut : <strong>{{ $this->record->statut?->label() }}</strong> ·
            Date : <strong>{{ $this->record->date_debut?->format('d/m/Y H:i') }}</strong>
        </div>
    </x-filament::section>

    <form wire:submit="save">
        {{ $this->form }}
        <div class="mt-4">
            <x-filament::button type="submit">
                Enregistrer les présences
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>