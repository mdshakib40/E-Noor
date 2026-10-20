{{-- Expects: $o (presented order array), optional $i (animation index) --}}
@php
    $steps = ['Accepted', 'Picked up', 'Out for delivery', 'Delivered'];
    $label = ['Head to pickup', 'Picked up', 'Out for delivery', 'Delivered'][$o['step']];
@endphp

<article class="r-card r-in overflow-hidden" style="--i:{{ $i ?? 0 }}">
    <div class="flex flex-col gap-3 border-b border-white/[.07] p-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-[#d7a858]/10 text-[#d7a858]"><i data-lucide="navigation" class="h-5 w-5"></i></span>
            <div>
                <p class="text-sm font-extrabold">Order {{ $o['number'] }}</p>
                <p class="mt-0.5 text-[11px] text-stone-500">
                    {{ $o['items'] ? $o['items'] . ' ' . ($o['items'] == 1 ? 'item' : 'items') : 'Order' }}@if ($o['total']) · {{ $o['total'] }}@endif
                    @if ($o['fee']) · Delivery fee {{ $o['fee'] }}@endif
                </p>
            </div>
        </div>
        <span class="r-badge b-gold w-fit"><i class="h-1.5 w-1.5 rounded-full bg-current"></i>{{ $label }}</span>
    </div>

    <div class="grid gap-3 p-5 lg:grid-cols-2">
        <div class="rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
            <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[.16em] text-stone-500"><i data-lucide="store" class="h-3.5 w-3.5 text-[#d7a858]"></i>Pickup</div>
            <p class="mt-2 font-bold">{{ $o['restaurant'] ?: '—' }}</p>
            <p class="mt-1 text-sm leading-6 text-stone-400">{{ $o['pickup_address'] ?: 'Address not provided' }}</p>
            @if ($o['pickup_map'])
                <a href="{{ $o['pickup_map'] }}" target="_blank" rel="noopener noreferrer" class="r-focus mt-3 inline-flex items-center gap-2 text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858]"><i data-lucide="map" class="h-3.5 w-3.5"></i>Open in Maps</a>
            @endif
        </div>

        <div class="rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
            <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[.16em] text-stone-500"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-[#b8d985]"></i>Delivery</div>
            <p class="mt-2 font-bold">{{ $o['customer'] ?: '—' }}</p>
            <p class="mt-1 text-sm leading-6 text-stone-400">{{ $o['address'] ?: 'Address not provided' }}</p>
            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2">
                @if ($o['address_map'])
                    <a href="{{ $o['address_map'] }}" target="_blank" rel="noopener noreferrer" class="r-focus inline-flex items-center gap-2 text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858]"><i data-lucide="map" class="h-3.5 w-3.5"></i>Open in Maps</a>
                @endif
                @if ($o['phone'])
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $o['phone']) }}" class="r-focus inline-flex items-center gap-2 text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858]"><i data-lucide="phone" class="h-3.5 w-3.5"></i>Call customer</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <ol class="grid grid-cols-4 gap-2 px-5" aria-label="Delivery progress">
        @foreach ($steps as $n => $name)
            @php $reached = $n <= $o['step']; @endphp
            <li @if ($n === $o['step']) aria-current="step" @endif>
                <div class="h-1 overflow-hidden rounded-full bg-white/[.08]">
                    @if ($reached)<div class="r-bar h-full w-full rounded-full bg-[#d7a858]" style="--i:{{ $n }}"></div>@endif
                </div>
                <p class="mt-2 text-[10px] leading-4 sm:text-xs {{ $reached ? 'text-[#f1cf8b]' : 'text-stone-600' }}">{{ $name }}</p>
            </li>
        @endforeach
    </ol>

    {{-- Next action --}}
    @if ($o['action'])
        <div class="p-5">
            <form method="POST" action="{{ $o['action'][1] }}" data-once>
                @csrf
                <button type="submit" class="r-btn w-full py-3.5 text-sm sm:w-auto sm:min-w-[250px]">
                    <i data-lucide="{{ $o['action'][2] }}" class="h-4 w-4"></i>{{ $o['action'][0] }}
                </button>
            </form>
        </div>
    @else
        <div class="h-5"></div>
    @endif
</article>