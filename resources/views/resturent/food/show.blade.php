
@extends('layouts.app')

@section('title', $menuItem->name . ' — NOOR')

@section('content')
@php
    $restaurant = $menuItem->restaurant;
    $price = (float) $menuItem->price;
    $maxQty = 20;
    $cartUrl = route('cart.add');

    $imageUrl = function ($path) {
        if (!$path) return null;

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    };

    $foodImage = $imageUrl($menuItem->image);
    $restaurantImage = $imageUrl($restaurant?->image);

    $restaurantUrl = $restaurant
        ? route('restaurants.show', $restaurant)
        : null;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap');

    .noor-food {
        --gold: #d7a858;
        --gold-light: #f1cf8b;
        --ink: #0e0f0d;
        --muted: #a09b8f;
        color: #f6f2e9;
        background: var(--ink);
        font-family: 'Manrope', sans-serif;
    }

    .noor-food * { box-sizing: border-box; }
    .noor-food .nf-serif { font-family: 'Playfair Display', Georgia, serif; }
    .noor-food .nf-muted { color: var(--muted); }
    .noor-food .nf-gold { color: var(--gold-light); }

    .noor-food .nf-eyebrow {
        color: var(--gold-light);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .2em;
        text-transform: uppercase;
    }

    .noor-food .nf-card,
    .noor-food .nf-related-card {
        background: #191a16;
        border: 1px solid rgba(255,255,255,.075);
        border-radius: 22px;
    }

    .noor-food .nf-crumb {
        color: #99958a;
        transition: color .2s;
    }

    .noor-food .nf-crumb:hover { color: var(--gold-light); }

    .noor-food .nf-quantity {
        display: flex;
        align-items: center;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 15px;
        background: #11120f;
    }

    .noor-food .nf-qty-btn {
        display: flex;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: #f1cf8b;
        cursor: pointer;
    }

    .noor-food .nf-qty-btn:hover { background: rgba(215,168,88,.12); }
    .noor-food .nf-qty-btn svg { width: 19px; height: 19px; }

    .noor-food .nf-qty-input {
        width: 48px;
        height: 48px;
        border: 0;
        outline: none;
        background: transparent;
        color: white;
        text-align: center;
        font: 800 15px 'Manrope', sans-serif;
        appearance: textfield;
        -moz-appearance: textfield;
    }

    .noor-food .nf-qty-input::-webkit-inner-spin-button,
    .noor-food .nf-qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .noor-food .nf-action {
        display: flex;
        width: 100%;
        min-height: 58px;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: 1px solid transparent;
        border-radius: 15px;
        padding: 14px 20px;
        background: linear-gradient(120deg, #f1cf8b, #d7a858);
        color: #19160f;
        font: 800 13px 'Manrope', sans-serif;
        cursor: pointer;
        transition: transform .2s, opacity .2s;
    }

    .noor-food .nf-action:hover:not(:disabled) { transform: translateY(-2px); }

    .noor-food .nf-action:disabled {
        cursor: wait;
        opacity: .7;
    }

    .noor-food .nf-action-state {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .noor-food .nf-action[data-state="idle"] .nf-idle,
    .noor-food .nf-action[data-state="loading"] .nf-loading,
    .noor-food .nf-action[data-state="success"] .nf-success {
        display: flex;
    }

    .noor-food .nf-spinner {
        width: 19px;
        height: 19px;
        border: 2px solid rgba(25,22,15,.25);
        border-top-color: #19160f;
        border-radius: 50%;
        animation: nf-spin .7s linear infinite;
    }

    .noor-food .nf-toast {
        position: fixed;
        z-index: 9999;
        right: 20px;
        bottom: 24px;
        max-width: calc(100vw - 40px);
        border: 1px solid rgba(215,168,88,.3);
        border-radius: 14px;
        padding: 14px 18px;
        background: #1b1c18;
        color: #f6f2e9;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 16px 50px rgba(0,0,0,.4);
        opacity: 0;
        pointer-events: none;
        transform: translateY(10px);
        transition: opacity .2s, transform .2s;
    }

    .noor-food .nf-toast-show { opacity: 1; transform: translateY(0); }
    .noor-food .nf-toast-error { border-color: #b76c5b; }

    .noor-food .nf-related-card {
        position: relative;
        overflow: hidden;
        transition: transform .25s, border-color .25s;
    }

    .noor-food .nf-related-card:hover {
        transform: translateY(-4px);
        border-color: rgba(215,168,88,.45);
    }

    .noor-food .nf-related-link { position: static; }
    .noor-food .nf-related-link::after {
        position: absolute;
        inset: 0;
        content: '';
    }

    .noor-food .nf-restaurant-link { position: relative; z-index: 2; }

    @keyframes nf-spin { to { transform: rotate(360deg); } }

    @media (max-width: 640px) {
        .noor-food .nf-page { padding-top: 22px; padding-bottom: 55px; }
        .noor-food .nf-title { font-size: 39px; }
        .noor-food .nf-image { aspect-ratio: 1 / .88; }
    }
</style>

<div class="noor-food">
    <div class="nf-page mx-auto max-w-7xl px-4 pb-24 pt-8 sm:px-6 lg:px-8">

        <nav aria-label="Breadcrumb" class="mb-7 flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('home') }}" class="nf-crumb">Home</a>
            <span class="nf-muted" aria-hidden="true">/</span>

            @if ($restaurant && $restaurantUrl)
                <a href="{{ $restaurantUrl }}" class="nf-crumb">{{ $restaurant->name }}</a>
            @elseif ($restaurant)
                <span class="nf-muted">{{ $restaurant->name }}</span>
            @endif

            <span class="nf-muted" aria-hidden="true">/</span>
            <span class="nf-gold font-bold" aria-current="page">{{ $menuItem->name }}</span>
        </nav>

        <section class="grid items-start gap-8 lg:grid-cols-2 lg:gap-14">

            <div class="nf-image relative aspect-[4/3] overflow-hidden rounded-[25px] border border-white/[.08] bg-[#191a16]">
                @if ($foodImage)
                    <img
                        src="{{ $foodImage }}"
                        alt="{{ $menuItem->name }}"
                        fetchpriority="high"
                        class="h-full w-full object-cover"
                    >
                @else
                    <div class="flex h-full min-h-64 flex-col items-center justify-center gap-4 text-[#d7a858]">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1">
                            <path d="M3 11h18M5 11a7 7 0 0 0 14 0M12 4V2M7 5 5 3M17 5l2-2M4 21h16"/>
                        </svg>
                        <span class="text-xs font-semibold text-stone-500">No food photo yet</span>
                    </div>
                @endif

                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/30 to-transparent"></div>
                <div class="absolute left-4 top-4 rounded-full border border-white/10 bg-black/55 px-3 py-2 text-[10px] font-extrabold uppercase tracking-[.15em] text-[#f1cf8b]">
                    Made with care
                </div>
            </div>

            <div class="flex min-w-0 flex-col pt-1">
                @if ($restaurant)
                    <a
                        href="{{ $restaurantUrl ?? '#' }}"
                        @if (!$restaurantUrl) aria-disabled="true" @endif
                        class="group flex w-fit max-w-full items-center gap-3 rounded-full border border-white/[.09] bg-[#191a16] p-1.5 pr-4 transition hover:border-[#d7a858]/40"
                    >
                        @if ($restaurantImage)
                            <img src="{{ $restaurantImage }}" alt="" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#d7a858]/10 text-[#f1cf8b]">N</span>
                        @endif

                        <span class="min-w-0">
                            <span class="block text-[9px] font-bold uppercase tracking-[.15em] text-stone-500">From our kitchen</span>
                            <span class="mt-0.5 block truncate text-xs font-extrabold text-stone-200">{{ $restaurant->name }}</span>
                        </span>
                    </a>
                @endif

                <p class="nf-eyebrow mt-7">A little something special</p>

                <h1 class="nf-serif nf-title mt-3 break-words text-5xl leading-[1.12] tracking-[-.035em] sm:text-6xl">
                    {{ $menuItem->name }}
                </h1>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <p class="text-3xl font-extrabold tracking-tight text-[#f1cf8b]">
                        ৳{{ number_format($price, 2) }}
                    </p>
                    <span class="rounded-full border border-[#d7a858]/20 bg-[#d7a858]/[.07] px-3 py-1.5 text-[10px] font-bold text-[#d7a858]">
                        Freshly prepared
                    </span>
                </div>

                <p class="mt-5 whitespace-pre-line text-sm leading-7 text-stone-400">
                    {{ $menuItem->description ?: 'A delicious dish, thoughtfully prepared by our kitchen.' }}
                </p>

                <div class="my-7 border-t border-white/[.09]"></div>

                <div
                    id="bagPanel"
                    data-price="{{ $price }}"
                    data-max="{{ $maxQty }}"
                    data-csrf="{{ csrf_token() }}"
                >
                    <div class="flex flex-wrap items-center justify-between gap-5">
                        <div>
                            <label for="qty" class="mb-3 block text-[10px] font-extrabold uppercase tracking-[.18em] text-stone-500">
                                Choose quantity
                            </label>

                            <div class="nf-quantity">
                                <button type="button" id="qtyMinus" class="nf-qty-btn" aria-label="Decrease quantity">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M5 12h14"/>
                                    </svg>
                                </button>

                                <input
                                    id="qty"
                                    class="nf-qty-input"
                                    type="number"
                                    inputmode="numeric"
                                    min="1"
                                    max="{{ $maxQty }}"
                                    step="1"
                                    value="1"
                                    required
                                >

                                <button type="button" id="qtyPlus" class="nf-qty-btn" aria-label="Increase quantity">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                </button>
                            </div>

                            <p class="mt-2 text-[10px] text-stone-600">Choose 1–{{ $maxQty }} items</p>
                        </div>

                        <div class="min-w-[125px] text-right">
                            <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-stone-500">Your total</p>
                            <p id="lineTotal" aria-live="polite" class="nf-serif mt-2 text-3xl font-bold text-stone-100">
                                ৳{{ number_format($price, 2) }}
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="bagBtn"
                        class="nf-action mt-6"
                        data-state="idle"
                        data-url="{{ $cartUrl }}"
                        data-item="{{ $menuItem->id }}"
                        data-name="{{ $menuItem->name }}"
                    >
                        <span class="nf-action-state nf-idle">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M5 8h14l1 13H4L5 8Z"/>
                                <path d="M9 9V6a3 3 0 0 1 6 0v3"/>
                            </svg>
                            Add to Bag
                        </span>

                        <span class="nf-action-state nf-loading">
                            <span class="nf-spinner" aria-hidden="true"></span>
                            Adding to bag...
                        </span>

                        <span class="nf-action-state nf-success">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                            Added to Bag!
                        </span>
                    </button>

                    <p id="bagLive" class="sr-only" aria-live="polite"></p>
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-white/[.07] pt-5 text-[11px] leading-5 text-stone-500">
                    <svg class="h-5 w-5 flex-shrink-0 text-[#d7a858]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span>Prepared by the restaurant. Final availability is subject to the kitchen.</span>
                </div>
            </div>
        </section>

        <section class="mt-20 border-t border-white/[.07] pt-12" aria-labelledby="alsoLove">
            <div class="mb-7">
                <p class="nf-eyebrow">Keep exploring</p>
                <h2 id="alsoLove" class="nf-serif mt-3 text-3xl tracking-tight sm:text-4xl">You might also love</h2>
                <p class="mt-2 text-xs leading-6 text-stone-500">Discover more dishes from our kitchens.</p>
            </div>

            @if ($relatedItems->isEmpty())
                <div class="nf-card px-5 py-12 text-center">
                    <p class="text-sm font-extrabold">More dishes are on the way</p>
                    <p class="mt-2 text-xs leading-6 text-stone-500">Add more active and available dishes to discover them here.</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
                    @foreach ($relatedItems as $item)
                        @php
                            $itemImage = $imageUrl($item->image);
                            $itemRestaurantUrl = $item->restaurant
                                ? route('restaurants.show', $item->restaurant)
                                : null;
                        @endphp

                        <article class="nf-related-card flex min-w-0 flex-col">
                            <div class="relative aspect-[4/3] overflow-hidden bg-[#22231e]">
                                @if ($itemImage)
                                    <img src="{{ $itemImage }}" alt="{{ $item->name }}" loading="lazy" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-[#d7a858]">NOOR</div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-3.5 sm:p-4">
                                <h3 class="text-sm font-extrabold leading-6">
                                    <a href="{{ route('foods.show', $item->id) }}" class="nf-related-link">{{ $item->name }}</a>
                                </h3>

                                @if ($item->restaurant)
                                    <p class="mt-1 text-[10px] text-stone-500">{{ $item->restaurant->name }}</p>
                                @endif

                                <p class="mt-4 text-sm font-extrabold text-[#f1cf8b]">
                                    ৳{{ number_format((float) $item->price, 2) }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</div>

<script>
(() => {
    const panel = document.getElementById('bagPanel');
    const btn = document.getElementById('bagBtn');
    const qty = document.getElementById('qty');
    const total = document.getElementById('lineTotal');
    const live = document.getElementById('bagLive');

    if (!panel || !btn || !qty || !total) return;

    const url = btn.dataset.url;
    const csrf = panel.dataset.csrf;
    const price = Number(panel.dataset.price) || 0;
    const max = Number(panel.dataset.max) || 20;

    let busy = false;
    let toastTimer = null;

    const clamp = value =>
        Math.min(max, Math.max(1, Number.parseInt(value, 10) || 1));

    const money = value => '৳' + value.toLocaleString('en-BD', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const currentQty = () => clamp(qty.value);

    const refreshTotal = () => {
        total.textContent = money(price * currentQty());
    };

    const setQty = value => {
        qty.value = String(clamp(value));
        refreshTotal();
    };

    document.getElementById('qtyMinus')?.addEventListener('click', () => {
        setQty(currentQty() - 1);
    });

    document.getElementById('qtyPlus')?.addEventListener('click', () => {
        setQty(currentQty() + 1);
    });

    qty.addEventListener('input', refreshTotal);
    qty.addEventListener('blur', () => setQty(qty.value));

    const toastEl = document.createElement('div');
    toastEl.className = 'nf-toast';
    toastEl.setAttribute('role', 'status');
    toastEl.setAttribute('aria-live', 'polite');
    document.body.appendChild(toastEl);

    const toast = (message, error = false) => {
        clearTimeout(toastTimer);
        toastEl.classList.toggle('nf-toast-error', error);
        toastEl.textContent = (error ? '⚠ ' : '✓ ') + message;
        toastEl.classList.add('nf-toast-show');

        toastTimer = setTimeout(() => {
            toastEl.classList.remove('nf-toast-show');
        }, 3200);
    };

    const setState = (state, message = '') => {
        btn.dataset.state = state;
        btn.setAttribute('aria-busy', state === 'loading' ? 'true' : 'false');
        if (live) live.textContent = message;
    };

    btn.addEventListener('click', async () => {
        if (busy) return;

        busy = true;
        btn.disabled = true;

        const quantity = currentQty();
        setQty(quantity);
        setState('loading', 'Adding to bag');

        try {
            const response = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    menu_item_id: Number(btn.dataset.item),
                    quantity: quantity
                })
            });

            const data = await response.json().catch(() => null);

            if (!response.ok || !data || data.success !== true) {
                let message = data?.message || 'Could not add this item. Please try again.';

                if (response.status === 401) {
                    message = 'Please sign in to add items to your bag.';
                } else if (response.status === 419) {
                    message = 'Session expired. Refresh the page and try again.';
                } else if (response.status === 422 && data?.errors) {
                    const firstError = Object.values(data.errors)[0];
                    if (Array.isArray(firstError) && firstError[0]) {
                        message = firstError[0];
                    }
                }

                toast(message, true);
                return;
            }

            setState('success', 'Added to bag');

            document.querySelectorAll('[data-cart-count]').forEach(element => {
                element.textContent = String(data.cart_count ?? 0);
                element.classList.remove('hidden');
            });

            window.dispatchEvent(new CustomEvent('noor:cart-updated', {
                detail: data
            }));

            toast(`${quantity > 1 ? quantity + ' × ' : ''}${btn.dataset.name} added to your bag`);

            await new Promise(resolve => setTimeout(resolve, 1200));

        } catch (error) {
            console.error('NOOR cart error:', error);
            toast('Could not connect to the server. Please try again.', true);
        } finally {
            setState('idle');
            btn.disabled = false;
            busy = false;
        }
    });

    refreshTotal();
})();
</script>
@endsection
