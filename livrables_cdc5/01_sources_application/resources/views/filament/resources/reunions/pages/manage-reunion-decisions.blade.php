<x-filament-panels::page>
    <x-filament::section>
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Commission : <strong>{{ $this->record->commission?->nom ?? '—' }}</strong> ·
            Objet : <strong>{{ $this->record->objet }}</strong>
        </div>
    </x-filament::section>

    <form wire:submit="save">
        {{ $this->form }}
        <div class="mt-4">
            <x-filament::button type="submit">
                Enregistrer les décisions
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>