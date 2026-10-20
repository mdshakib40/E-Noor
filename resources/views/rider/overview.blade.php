@extends('layouts.rider')

@section('title', 'Rider Dashboard')
@section('page', 'Overview')

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $first = \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first();
    $earnings = $stats['earnings_today'];

    $cards = [
        ['Available orders', 'clipboard-list', $stats['available'], $stats['available'] ? 'Ready for pickup' : 'None right now', 'text-[#d7a858] bg-[#d7a858]/10'],
        ['Active deliveries', 'navigation', $stats['active'], $stats['active'] ? 'In progress' : 'Nothing in progress', 'text-[#a7c4e9] bg-[#a7c4e9]/10'],
        ['Completed today', 'check-check', $stats['completed_today'], 'Delivered orders', 'text-[#b8d985] bg-[#b8d985]/10'],
        ["Today's earnings", 'wallet', $earnings === null ? '—' : '৳' . number_format($earnings), $earnings === null ? 'Not tracked yet' : 'From delivery fees', 'text-[#d7a858] bg-[#d7a858]/10'],
    ];
@endphp

{{-- Welcome --}}
<section class="r-in mb-8">
    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#f1cf8b]">{{ now()->format('l, d M') }}</p>
    <h1 class="serif mt-3 text-4xl leading-tight tracking-tight sm:text-5xl">{{ $greeting }}, <em class="text-[#f1cf8b]">{{ $first }}.</em></h1>
    <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">Pick up ready orders, keep them moving, and track everything you have delivered.</p>
</section>

{{-- Stats --}}
<section class="grid grid-cols-2 gap-3 xl:grid-cols-4">
    @foreach ($cards as $n => [$label, $icon, $value, $note, $tone])
        <div class="r-card r-lift r-in p-5" style="--i:{{ $n + 1 }}">
            <div class="flex items-center justify-between gap-2 text-[11px] text-stone-500">
                {{ $label }}<span class="grid h-9 w-9 place-items-center rounded-xl {{ $tone }}"><i data-lucide="{{ $icon }}" class="h-4 w-4"></i></span>
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight">{{ $value }}</p>
            <p class="mt-1.5 text-[11px] text-stone-500">{{ $note }}</p>
        </div>
    @endforeach
</section>

{{-- Active delivery --}}
<section class="mt-10" aria-labelledby="activeTitle">
    <div class="mb-4 flex items-end justify-between gap-3">
        <h2 id="activeTitle" class="serif text-2xl">Active delivery</h2>
        @if ($active->count() > 0)
            <a href="{{ route('rider.active') }}" class="r-focus text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858]">Open full view →</a>
        @endif
    </div>

    @if ($active->isEmpty())
        @include('rider.partials.empty', ['icon' => 'bike', 'title' => 'No active delivery', 'text' => 'Accept an available order below and it will appear here with its pickup and drop-off details.'])
    @else
        <div class="space-y-4">
            @foreach ($active as $o)
                @include('rider.partials.active-card', ['o' => $o, 'i' => $loop->index])
            @endforeach
        </div>
    @endif
</section>

{{-- Available orders --}}
<section class="mt-10" aria-labelledby="availTitle">
    <div class="mb-4 flex items-end justify-between gap-3">
        <h2 id="availTitle" class="serif text-2xl">Available orders</h2>
        @if ($stats['available'] > 0)
            <a href="{{ route('rider.available') }}" class="r-focus text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858]">View all {{ $stats['available'] }} →</a>
        @endif
    </div>

    @if ($available->isEmpty())
        @include('rider.partials.empty', ['icon' => 'clipboard-list', 'title' => 'No orders waiting', 'text' => 'Orders that restaurants mark as ready will show up here. Check back in a moment.'])
    @else
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($available as $o)
                @include('rider.partials.available-card', ['o' => $o, 'i' => $loop->index])
            @endforeach
        </div>
    @endif
</section>

{{-- Recent history --}}
<section class="mt-10" aria-labelledby="histTitle">
    <div class="mb-4 flex items-end justify-between gap-3">
        <h2 id="histTitle" class="serif text-2xl">Recent deliveries</h2>
        @if ($recent->isNotEmpty())
            <a href="{{ route('rider.history') }}" class="r-focus text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858]">Full history →</a>
        @endif
    </div>

    @if ($recent->isEmpty())
        @include('rider.partials.empty', ['icon' => 'history', 'title' => 'No deliveries yet', 'text' => 'Orders you complete will be listed here.'])
    @else
        <div class="r-card divide-y divide-white/[.06] overflow-hidden">
            @foreach ($recent as $o)
                <div class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold">{{ $o['restaurant'] ?: 'Order' }} <span class="font-medium text-stone-500">· {{ $o['number'] }}</span></p>
                        <p class="mt-1 truncate text-xs text-stone-500">{{ $o['address'] ?: '—' }}</p>
                    </div>
                    <div class="flex items-center gap-4 sm:justify-end">
                        <div class="text-right">
                            <p class="text-sm font-bold">{{ $o['fee'] ?? $o['total'] ?? '—' }}</p>
                            <p class="mt-0.5 text-[11px] text-stone-500">{{ $o['delivered_at']?->format('d M, h:i A') ?? '—' }}</p>
                        </div>
                        <span class="r-badge b-green">Delivered</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection