<x-filament-panels::page>
    <style>
        /* ===== LAYOUT ARBRE HIÉRARCHIQUE (Niveaux verticaux, Frères horizontaux) ===== */
        .org-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            padding: 40px 20px;
            background: #0f172a;
            border-radius: 12px;
        }

        /* Arbre principal */
        .org-tree {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px; /* Espace entre les niveaux */
        }

        /* Niveau d'un nœud */
        .org-level {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        /* Conteneur des frères (côte à côte) */
        .org-siblings {
            display: flex;
            flex-direction: row;
            gap: 30px; /* Espace entre les frères */
            align-items: flex-start;
        }

        /* Chaque nœud avec ses enfants */
        .org-node-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        /* Connecteurs verticaux entre niveaux */
        .org-connector-vertical {
            width: 2px;
            height: 20px;
            background: #475569;
            margin: 10px 0;
        }

        /* Branche horizontale pour connecter les frères */
        .org-connector-horizontal {
            position: absolute;
            top: -10px;
            left: 50%;
            width: calc(100% + 30px); /* Ajuster selon le gap */
            height: 2px;
            background: #475569;
            transform: translateX(-50%);
            border-top: 2px solid #475569;
        }

        /* Connecteur descendant du parent vers les frères */
        .org-connector-parent {
            width: 2px;
            height: 15px;
            background: #475569;
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* ===== NOEUDS ===== */
        .org-node {
            display: inline-flex;
            flex-direction: column;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 12px 16px;
            background: #1e293b;
            cursor: pointer;
            min-width: 180px;
            max-width: 260px;
            color: #e2e8f0;
            transition: all 0.15s ease;
            text-align: left;
            font-family: inherit;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .org-node:hover {
            border-color: #f59e0b;
            background: #292524;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.3);
            transform: translateY(-2px);
        }

        .org-node.org-selected {
            outline: 2px solid #f59e0b;
            background: #292524;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
        }

        .org-name {
            font-weight: 700;
            font-size: 13px;
            line-height: 1.4;
            word-break: break-word;
        }

        .org-title {
            font-size: 11px;
            opacity: 0.6;
            margin-top: 4px;
        }

        /* Badge couleur par type */
        .org-node[data-type="universite"]     { border-left: 5px solid #3b82f6; }
        .org-node[data-type="ecole"]          { border-left: 5px solid #8b5cf6; }
        .org-node[data-type="etablissement"]  { border-left: 5px solid #10b981; }
        .org-node[data-type="commission"]     { border-left: 5px solid #f59e0b; }
        .org-node[data-type="membre"]         { border-left: 5px solid #ef4444; }

        /* ===== PANEL DETAIL ===== */
        .detail-panel {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 20px;
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            color: #e2e8f0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #334155;
            font-size: 13px;
        }

        .detail-item:last-child { border-bottom: none; }

        .detail-label { color: #94a3b8; }
        .detail-value { font-weight: 500; text-align: right; }

        .detail-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #475569;
            border-radius: 6px;
            margin-bottom: 8px;
            font-size: 13px;
            background: #0f172a;
            color: #e2e8f0;
        }

        .detail-input:focus {
            outline: none;
            border-color: #f59e0b;
        }

        .detail-btn {
            padding: 8px 16px;
            background: #f59e0b;
            color: #0f172a;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .detail-btn:hover { background: #d97706; }

        .detail-link {
            display: inline-block;
            margin-top: 8px;
            color: #60a5fa;
            text-decoration: none;
            font-size: 12px;
        }

        .detail-link:hover { text-decoration: underline; }
    </style>

    <div class="grid gap-4 lg:grid-cols-3">
        <!-- Arbre -->
        <div class="lg:col-span-2 rounded-xl border bg-white p-3 dark:bg-gray-900">
            <div id="uma-org-chart" class="org-scroll" wire:ignore></div>
        </div>

        <!-- Detail -->
        <div class="detail-panel">
            <h3 class="font-semibold mb-2">Detail</h3>
            <p class="text-sm opacity-70">Selectionnez un noeud.</p>

            @if ($this->selectedModel)
                <p class="mt-2 text-sm font-semibold">
                    {{ $this->selectedType }} #{{ $this->selectedId }}
                </p>

                <dl class="mt-2">
                    @foreach ($this->detail() as $k => $v)
                        <div class="detail-item">
                            <dt class="detail-label">{{ $k }}</dt>
                            <dd class="detail-value">{{ $v }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if ($this->selectedResourceUrl())
                    <a href="{{ $this->selectedResourceUrl() }}" class="detail-link">
                        Voir dans le resource →
                    </a>
                @endif

                @if ($this->canEdit())
                    <div class="mt-4 space-y-2">
                        @if ($this->selectedType === 'universite')
                            <input type="text" wire:model="editData.nom"  class="detail-input" placeholder="Nom" />
                            <input type="text" wire:model="editData.code" class="detail-input" placeholder="Code" />
                        @elseif ($this->selectedType === 'membre')
                            <input type="text"  wire:model="editData.name"  class="detail-input" placeholder="Nom" />
                            <input type="email" wire:model="editData.email" class="detail-input" placeholder="Email" />
                        @else
                            <input type="text" wire:model="editData.nom" class="detail-input" placeholder="Nom" />
                        @endif

                        <button type="button" wire:click="saveSel" class="detail-btn">
                            Enregistrer
                        </button>

                        @if ($this->selectedResourceEditUrl())
                            <a href="{{ $this->selectedResourceEditUrl() }}" class="detail-link ml-2">
                                Modifier dans le resource →
                            </a>
                        @endif
                    </div>
                @else
                    <p class="mt-3 text-xs opacity-60">Lecture seule.</p>
                @endif
            @endif
        </div>
    </div>

    <script>
        (function () {
            // Récupère le composant Livewire
            function getWire() {
                const el = document.getElementById('uma-org-chart');
                if (!el) return null;
                const comp = el.closest('[wire\\:id]');
                if (!comp) return null;
                return window.Livewire?.find(comp.getAttribute('wire:id')) ?? null;
            }

            function render() {
                const root = document.getElementById('uma-org-chart');
                if (!root) return;

                const nodes = @js($this->nodes());

                // Index par parent
                const byP = {};
                nodes.forEach((n) => {
                    const k = n.parent ?? '__r__';
                    (byP[k] = byP[k] || []).push(n);
                });

                function makeNode(n) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'org-node';
                    btn.dataset.nid = n.id;
                    btn.dataset.type = n.type;
                    btn.innerHTML =
                        '<span class="org-name"></span><span class="org-title"></span>';
                    btn.querySelector('.org-name').textContent = n.name;
                    btn.querySelector('.org-title').textContent = n.title || '';

                    btn.addEventListener('click', () => {
                        root.querySelectorAll('.org-selected')
                           .forEach((x) => x.classList.remove('org-selected'));
                        btn.classList.add('org-selected');

                        const wire = getWire();
                        if (wire) {
                            wire.call('sel', n.type, n.modelId);
                        } else {
                            console.error('Composant Livewire introuvable.');
                        }
                    });

                    return btn;
                }

                // Construit un sous-arbre (nœud + enfants)
                function buildTree(parentKey) {
                    const siblings = byP[parentKey] || [];
                    if (siblings.length === 0) return null;

                    const siblingsContainer = document.createElement('div');
                    siblingsContainer.className = 'org-siblings';

                    siblings.forEach((n) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'org-node-wrapper';

                        // Nœud
                        const nodeEl = makeNode(n);
                        wrapper.appendChild(nodeEl);

                        // Connecteur descendant si enfants
                        const children = byP[n.id];
                        if (children && children.length > 0) {
                            // Connecteur vertical parent → enfants
                            const connector = document.createElement('div');
                            connector.className = 'org-connector-vertical';
                            wrapper.appendChild(connector);

                            // Récursivement les enfants
                            const childrenTree = buildTree(n.id);
                            if (childrenTree) {
                                wrapper.appendChild(childrenTree);
                            }
                        }

                        siblingsContainer.appendChild(wrapper);
                    });

                    return siblingsContainer;
                }

                root.innerHTML = '';
                const tree = buildTree('__r__');
                if (tree) root.appendChild(tree);
            }

            // Rendu initial + re-rendu après navigation SPA
            document.addEventListener('DOMContentLoaded', render);
            document.addEventListener('livewire:navigated', () => setTimeout(render, 150));
        })();
    </script>
</x-filament-panels::page>