{{-- Expects: $o (presented order array), optional $i (animation index) --}}
<article class="r-card r-lift r-in flex flex-col p-5" style="--i:{{ $i ?? 0 }}">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-stone-500">Order {{ $o['number'] }}</p>
            <p class="mt-2 truncate font-bold">{{ $o['restaurant'] ?: '—' }}</p>
        </div>
        <div class="shrink-0 text-right">
            <p class="text-lg font-extrabold text-[#f1cf8b]">{{ $o['fee'] ?? '—' }}</p>
            <p class="text-[10px] text-stone-500">{{ $o['fee'] ? 'Delivery fee' : 'Fee not set' }}</p>
        </div>
    </div>

    <div class="mt-4 space-y-2.5 text-sm text-stone-400">
        <div class="flex items-start gap-2.5"><i data-lucide="store" class="mt-0.5 h-4 w-4 shrink-0 text-[#d7a858]"></i><span>{{ $o['pickup_address'] ?: 'Pickup address not provided' }}</span></div>
        <div class="flex items-start gap-2.5"><i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-[#b8d985]"></i><span>{{ $o['address'] ?: 'Destination not provided' }}</span></div>
        @if ($o['items'] || $o['total'])
            <div class="flex items-center gap-2.5 text-xs text-stone-500"><i data-lucide="shopping-bag" class="h-4 w-4 shrink-0"></i>
                {{ $o['items'] ? $o['items'] . ' ' . ($o['items'] == 1 ? 'item' : 'items') : '' }}{{ $o['items'] && $o['total'] ? ' · ' : '' }}{{ $o['total'] }}
            </div>
        @endif
    </div>

    <div class="mt-5 flex items-center justify-between gap-3 border-t border-white/[.07] pt-4">
        <span class="text-[11px] text-stone-500">{{ $o['posted']?->diffForHumans() }}</span>
        @if ($o['action'])
            <form method="POST" action="{{ $o['action'][1] }}" data-once>
                @csrf
                <button type="submit" class="r-btn"><i data-lucide="check" class="h-4 w-4"></i>Accept order</button>
            </form>
        @endif
    </div>
</article>