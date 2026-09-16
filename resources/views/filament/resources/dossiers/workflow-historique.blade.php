<div class="space-y-2">
    @forelse ($trails as $trail)
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center justify-between gap-3">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $trail->transition_code }}
                    @if ($trail->actor !== null)
                        <span class="font-normal text-gray-500 dark:text-gray-400">— {{ $trail->actor->name }}</span>
                    @endif
                </span>
                <span class="text-xs text-gray-500">
                    {{ $trail->created_at?->format('d/m/Y H:i') }}
                </span>
            </div>
            <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                {{ $trail->from_state ?: '—' }} → {{ $trail->to_state }}
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">Aucun événement de workflow.</p>
    @endforelse
</div>
