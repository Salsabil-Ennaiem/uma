<x-filament-panels::page>
    <style>
        .orgL{list-style:none;margin:0;padding-left:22px;border-left:1px dashed #d1d5db}
        .orgR > .orgL{padding-left:0;border-left:none}
        .orgN{display:inline-flex;flex-direction:column;border:1px solid #e5e7eb;border-radius:10px;padding:8px 12px;background:#fff;cursor:pointer;min-width:180px;margin:6px 0}
        .orgS{outline:2px solid #f59e0b}
        .orgNm{font-weight:600;font-size:13px}
        .orgTt{font-size:11px;opacity:.7}
    </style>
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 rounded-xl border bg-white p-3 dark:bg-gray-900">
            <div id="uma-org-chart" class="orgR" wire:ignore></div>
        </div>
        <div class="rounded-xl border bg-white p-4 dark:bg-gray-900">
            <h3 class="font-semibold">Detail</h3>
            <p class="text-sm opacity-70">Selectionnez un noeud.</p>
@php
    $selected = $this->getSelectedProperty();
@endphp

@if ($selected)                <p class="mt-2 text-sm font-semibold">{{ $this->selectedType }} #{{ $this->selectedId }}</p>
                <dl class="mt-2 space-y-1 text-sm">
                    @foreach ($this->detail() as $k => $v)
                        <div class="flex justify-between gap-3"><dt class="opacity-60">{{ $k }}</dt><dd class="font-medium">{{ $v }}</dd></div>
                    @endforeach
                </dl>
                @if ($this->selectedResourceUrl())
                    <a href="{{ $this->selectedResourceUrl() }}" class="fi-link mt-3 inline-block text-sm">Voir dans le resource</a>
                @endif
                @if ($this->canEdit())
                    <div class="mt-3 space-y-2">
                        @if ($this->selectedType === 'universite')
                            <input type="text" wire:model="editData.nom" class="fi-input w-full" />
                            <input type="text" wire:model="editData.code" class="fi-input w-full" />
                        @elseif ($this->selectedType === 'membre')
                            <input type="text" wire:model="editData.name" class="fi-input w-full" />
                            <input type="email" wire:model="editData.email" class="fi-input w-full" />
                        @else
                            <input type="text" wire:model="editData.nom" class="fi-input w-full" />
                        @endif
                        <button type="button" wire:click="saveSel" class="fi-btn">Enregistrer</button>
                        @if ($this->selectedResourceEditUrl())
                            <a href="{{ $this->selectedResourceEditUrl() }}" class="fi-link ml-2 text-sm">Modifier dans le resource</a>
                        @endif
                    </div>
                @else
                    <p class="mt-2 text-xs opacity-70">Lecture seule (pas d'autorite update).</p>
                @endif
            @endif
        </div>
    </div>
    <script>
        (function () {
            function render() {
                const root = document.getElementById('uma-org-chart');
                if (!root) return;
                const nodes = @js($this->nodes());
                const byP = {};
                nodes.forEach((n) => {
                    const k = n.parent === null || n.parent === undefined ? '__r__' : String(n.parent);
                    (byP[k] = byP[k] || []).push(n);
                });
                function branch(pk) {
                    const ul = document.createElement('ul');
                    ul.className = 'orgL';
                    (byP[pk] || []).forEach((n) => {
                        const li = document.createElement('li');
                        const b = document.createElement('button');
                        b.type = 'button';
                        b.className = 'orgN';
                        b.dataset.nid = n.id;
                        b.innerHTML = '<span class="orgNm"></span><span class="orgTt"></span>';
                        b.querySelector('.orgNm').textContent = n.name;
                        b.querySelector('.orgTt').textContent = n.title || '';
                        b.addEventListener('click', () => {
                            root.querySelectorAll('.orgS').forEach((x) => x.classList.remove('orgS'));
                            b.classList.add('orgS');
                            $wire.call('sel', n.type, n.modelId);
                        });
                        li.appendChild(b);
                        if (byP[String(n.id)]) li.appendChild(branch(String(n.id)));
                        ul.appendChild(li);
                    });
                    return ul;
                }
                root.innerHTML = '';
                root.appendChild(branch('__r__'));
            }
            document.addEventListener('DOMContentLoaded', render);
            document.addEventListener('livewire:navigated', render);
        })();
    </script>
</x-filament-panels::page>


