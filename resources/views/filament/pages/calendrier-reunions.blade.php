<x-filament-panels::page>
    @php
        $byDay = $this->rows();
        $sel = $this->selectedId ? \App\Models\Reunion::with(['commission'])->find($this->selectedId) : null;
    @endphp
    <div class="mb-3 flex items-center gap-2">
        <x-filament::button wire:click="prevMonth" size="sm">Mois precedent</x-filament::button>
        <div class="font-semibold">{{ $this->month }}/{{ $this->year }}</div>
        <x-filament::button wire:click="nextMonth" size="sm">Mois suivant</x-filament::button>
    </div>
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 overflow-x-auto rounded-xl border bg-white p-3 dark:bg-gray-900">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left opacity-70">
                        <th class="p-2">Lun</th><th class="p-2">Mar</th><th class="p-2">Mer</th><th class="p-2">Jeu</th><th class="p-2">Ven</th><th class="p-2">Sam</th><th class="p-2">Dim</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->weeks() as $w)
                        <tr>
                            @foreach ($w as $d)
                                @php $k = $d->format('Y-m-d'); @endphp
                                <td class="border p-1 align-top {{ $d->month !== $this->month ? 'opacity-40' : '' }}">
                                    <button type="button" wire:click="selDay('{{ $k }}')" class="text-xs font-semibold">{{ $d->format('d') }}</button>
                                    @foreach (($byDay[$k] ?? []) as $r)
                                        <button type="button" wire:click="selReunion({{ $r->getKey() }})" class="mt-1 block w-full truncate rounded bg-amber-100 px-1 py-0.5 text-left text-xs dark:bg-amber-900">
                                            {{ $r->date_debut?->format('H:i') }} {{ $r->objet }}
                                        </button>
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-2 text-xs opacity-70">Astuce : cliquez un jour puis « Creer une reunion » (le creneau se choisit ensuite via le selecteur date + slot), ou cliquez une reunion pour la voir / modifier / supprimer.</p>
        </div>
        <div class="rounded-xl border bg-white p-4 dark:bg-gray-900">
            <h3 class="font-semibold">Reunion</h3>
            @if ($sel)
                <p class="mt-2 text-sm font-semibold">{{ $sel->objet }}</p>
                <p class="text-xs opacity-70">{{ $sel->commission?->nom }} — {{ $sel->date_debut?->format('d/m/Y H:i') }} → {{ $sel->date_fin?->format('H:i') }}</p>
                @if ($this->selectedDay)
                    <p class="mt-2 text-xs">Jour selectionne : <strong>{{ $this->selectedDay }}</strong>.</p>
                @endif
                <div class="mt-3 flex flex-wrap gap-2">
                    @can('update', $sel)
                        <x-filament::button size="sm" tag="a" :href="\App\Filament\Resources\Reunions\ReunionResource::getUrl('edit', ['record' => $sel])">Modifier</x-filament::button>
                    @endcan
                    @can('delete', $sel)
                        <x-filament::button size="sm" color="danger" wire:click="delReunion({{ $sel->getKey() }})" wire:confirm="Supprimer cette reunion ?">Supprimer</x-filament::button>
                    @endcan
                </div>
            @else
                <p class="mt-2 text-sm opacity-70">Selectionnez une reunion dans le calendrier.</p>
                @if ($this->selectedDay)
                    <p class="mt-2 text-xs">Jour selectionne : <strong>{{ $this->selectedDay }}</strong>.</p>
                @endif
            @endif
        </div>
    </div>
</x-filament-panels::page>
