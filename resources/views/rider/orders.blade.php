
@extends('layouts.rider')

@section('title', $page)
@section('page', $page)

@section('content')
@php
    $badge = fn ($s) => match ($s) {
        'delivered' => ['b-green', 'Delivered'],
        'out_for_delivery' => ['b-blue', 'Out for delivery'],
        'picked_up' => ['b-blue', 'Picked up'],
        'ready_for_pickup', 'ready' => ['b-gold', 'Accepted'],
        default => ['b-mute', \Illuminate\Support\Str::headline($s)],
    };
@endphp

<section class="r-in mb-8">
    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#f1cf8b]">
        Rider
    </p>

    <h1 class="serif mt-3 text-4xl leading-tight tracking-tight sm:text-5xl">
        {{ $title }}
    </h1>

    <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
        {{ $subtitle }}
    </p>
</section>

@if ($orders->isEmpty())
    @include('rider.partials.empty', [
        'icon' => $icon,
        'title' => 'Nothing here yet',
        'text' => $page === 'Delivery History'
            ? 'Orders you complete will be listed here.'
            : 'Orders you accept will be listed here.',
    ])
@else
    <div class="r-card divide-y divide-white/[.06] overflow-hidden">
        @foreach ($orders as $o)
            @php
                [$class, $text] = $badge($o['status']);
                $isActive = $o['status'] !== 'delivered';

                $isCodPending = $o['status'] === 'delivered'
                    && ($o['payment_method'] ?? null) === 'cash_on_delivery'
                    && ($o['payment_status'] ?? null) === 'pending';

                $isPaid = ($o['payment_status'] ?? null) === 'paid';
            @endphp

            <div
                class="r-in flex flex-col gap-3 px-5 py-4 transition hover:bg-white/[.02] sm:flex-row sm:items-center sm:justify-between"
                style="--i:{{ min($loop->index, 6) }}"
            >
                <div class="flex min-w-0 items-center gap-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-[#d7a858]/10 text-[#d7a858]">
                        <i data-lucide="utensils" class="h-4 w-4"></i>
                    </span>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold">
                            {{ $o['restaurant'] ?: 'Order' }}
                            <span class="font-medium text-stone-500">
                                · {{ $o['number'] }}
                            </span>
                        </p>

                        <p class="mt-1 truncate text-xs text-stone-500">
                            {{ $o['customer'] ?: '—' }}
                            @if (!empty($o['address']))
                                · {{ $o['address'] }}
                            @endif
                        </p>

                        @if (($o['payment_method'] ?? null) === 'cash_on_delivery')
                            <p class="mt-1 text-xs {{ $isPaid ? 'text-green-400' : 'text-amber-300' }}">
                                {{ $isPaid ? 'COD · Payment paid' : 'COD · Payment pending' }}
                            </p>
                        @elseif ($isPaid)
                            <p class="mt-1 text-xs text-green-400">
                                Online payment · Paid
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-end">
                    <div class="text-right">
                        <p class="text-sm font-bold">
                            {{ $o['fee'] ?? $o['total'] ?? '—' }}
                        </p>

                        <p class="mt-0.5 text-[11px] text-stone-500">
                            {{ ($o['delivered_at'] ?? $o['accepted_at'])?->format('d M, h:i A') ?? '—' }}
                        </p>
                    </div>

                    <span class="r-badge {{ $class }}">
                        {{ $text }}
                    </span>

                    @if ($isCodPending)
                        <form
                            method="POST"
                            action="{{ $o['cash_collected_url'] }}"
                            onsubmit="return confirm('আপনি কি সত্যিই গ্রাহকের কাছ থেকে টাকা সংগ্রহ করেছেন?')"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="r-btn px-4 py-2 text-xs"
                            >
                                <i data-lucide="banknote" class="mr-1 inline h-4 w-4"></i>
                                Cash Collected
                            </button>
                        </form>
                    @elseif ($isPaid && $o['status'] === 'delivered')
                        <span class="r-badge b-green">
                            Payment Paid
                        </span>
                    @endif

                    @if ($isActive)
                        <a
                            href="{{ route('rider.active') }}"
                            class="r-focus hidden text-xs font-bold text-[#f1cf8b] hover:text-[#d7a858] sm:inline"
                        >
                            Open →
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($orders->hasPages())
        <div class="pager mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endif
@endsection
