<x-filament-panels::page>
    <x-filament::section
        heading="{{ $this->getHeading() }}"
        description="Formats acceptés : CSV, XLSX, XML, JSON. Mappage automatique et validation avant intégration — aucune ligne en erreur n'est écrite en base."
    >
        <form wire:submit="analyze" class="space-y-4">
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700">
                    Fichier à importer
                </label>
                <input
                    type="file"
                    wire:model="file"
                    id="file"
                    accept=".csv,.xlsx,.xml,.json,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/xml,application/json"
                    class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-primary-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-500"
                >
                @error('file')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-wrap gap-3">
                <x-filament::button type="submit">
                    Analyser le fichier
                </x-filament::button>

                @if ($sourcePath !== null)
                    <x-filament::button wire:click="commit" color="success">
                        Committer les lignes valides
                    </x-filament::button>
                    <x-filament::button wire:click="downloadErrors" color="warning">
                        Rapport d'erreurs (CSV)
                    </x-filament::button>
                @endif
            </div>
        </form>
    </x-filament::section>

    @if ($committed !== null && $preview !== [])
        <x-filament::section heading="Résultat de l'intégration">
            <p class="text-sm">
                <strong>{{ $committed }}</strong> ligne(s) intégrée(s) dans la transaction ;
                <strong>{{ collect($preview)->filter(fn ($r) => $r['errors'] !== [])->count() }}</strong> ligne(s)
                rejetée(s) (jamais écrites en base).
            </p>
        </x-filament::section>
    @endif

    @if ($preview !== [])
        <x-filament::section heading="Aperçu / validation des lignes">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="px-2 py-2">Ligne</th>
                            <th class="px-2 py-2">Données importées</th>
                            <th class="px-2 py-2">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview as $row)
                            <tr class="border-b">
                                <td class="px-2 py-2 align-top">{{ $row['row'] }}</td>
                                <td class="px-2 py-2 align-top">
                                    @foreach ($row['data'] as $field => $value)
                                        <span class="mr-3">
                                            <strong>{{ $field }}</strong> : {{ $value === null || $value === '' ? '—' : $value }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-2 py-2 align-top">
                                    @if ($row['errors'] === [])
                                        <x-filament::badge color="success">
                                            Valide
                                        </x-filament::badge>
                                    @else
                                        <x-filament::badge color="danger">
                                            En erreur
                                        </x-filament::badge>
                                        <ul class="mt-1 list-disc pl-5 text-xs text-danger-600">
                                            @foreach ($row['errors'] as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>