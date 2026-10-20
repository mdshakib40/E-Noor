
@extends('layouts.app')

@section('title', 'Checkout — NOOR')

@section('content')
@php
    $inputBase = 'w-full rounded-xl border bg-[#11120f] px-4 py-3 text-sm text-[#f6f2e9] placeholder-stone-600 outline-none transition focus:border-[#d7a858] focus:ring-4 focus:ring-[#d7a858]/10';

    $cls = fn (string $field) =>
        $inputBase . ' ' . (
            $errors->has($field)
                ? 'is-invalid border-[#ed8b79]/60'
                : 'border-white/10'
        );

    $money = fn ($amount) => '৳' . number_format((float) $amount, 2);
@endphp

<style>
    .ck-serif { font-family: "Playfair Display", Georgia, serif; }
    .is-invalid { border-color: rgba(237,139,121,.7) !important; }
    [data-lucide] { display: inline-block; }

    @keyframes ck-drive {
        from { left: -3rem; }
        to { left: calc(100% + .5rem); }
    }

    @keyframes ck-fill {
        from { width: 0; }
        to { width: 100%; }
    }

    @keyframes ck-road {
        to { background-position-x: -16px; }
    }

    @keyframes ck-bob {
        from { transform: translateY(0); }
        to { transform: translateY(-1.5px); }
    }

    @keyframes ck-draw {
        to { stroke-dashoffset: 0; }
    }

    @keyframes ck-pop {
        0% { transform: scale(.5); opacity: 0; }
        70% { transform: scale(1.12); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes ck-pulse {
        from { box-shadow: 0 0 0 0 rgba(184,217,133,.55); }
        to { box-shadow: 0 0 0 20px rgba(184,217,133,0); }
    }

    @keyframes ck-flash {
        0% { color: #f1cf8b; transform: scale(1.06); }
        100% { transform: scale(1); }
    }

    .ck-cta {
        position: relative;
        display: grid;
        overflow: hidden;
        isolation: isolate;
        width: 100%;
        min-height: 58px;
        border: 0;
        border-radius: .9rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg,#f1cf8b,#d7a858);
        color: #161714;
        font-size: .95rem;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 14px 34px -12px rgba(215,168,88,.65);
        transition: transform .2s, box-shadow .4s;
    }

    .ck-cta:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -12px rgba(215,168,88,.8);
    }

    .ck-cta:focus-visible {
        outline: 2px solid #f1cf8b;
        outline-offset: 3px;
    }

    .ck-cta:disabled { cursor: wait; }

    .ck-cta::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -2;
        opacity: 0;
        background: linear-gradient(135deg,#d9ec9f,#b8d985);
        transition: opacity .4s ease;
    }

    .ck-cta[data-state="success"]::before { opacity: 1; }

    .ck-cta[data-state="success"] {
        cursor: default;
        animation: ck-pulse 1.1s ease-out 1;
        box-shadow: 0 16px 40px -10px rgba(184,217,133,.6);
    }

    .ck-fill {
        position: absolute;
        inset: 0 auto 0 0;
        z-index: -1;
        width: 0;
        background: rgba(22,23,20,.13);
    }

    .ck-road {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 8px;
        height: 2px;
        opacity: 0;
        background-image: linear-gradient(90deg,rgba(22,23,20,.5) 50%,transparent 50%);
        background-size: 16px 2px;
        transition: opacity .3s;
    }

    .ck-truck {
        position: absolute;
        top: 50%;
        left: -3rem;
        z-index: 1;
        opacity: 0;
        transform: translateY(-50%);
        color: #161714;
        pointer-events: none;
    }

    .ck-truck svg {
        animation: ck-bob .28s ease-in-out infinite alternate;
    }

    .ck-cta[data-state="driving"] .ck-fill {
        animation: ck-fill 2.4s cubic-bezier(.45,.05,.4,1) forwards;
    }

    .ck-cta[data-state="driving"] .ck-road {
        opacity: 1;
        animation: ck-road .45s linear infinite;
    }

    .ck-cta[data-state="driving"] .ck-truck {
        opacity: 1;
        animation: ck-drive 2.4s cubic-bezier(.45,.05,.4,1) forwards;
    }

    .ck-st {
        grid-area: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: opacity .3s, transform .3s, visibility 0s .3s;
    }

    .ck-cta[data-state="idle"] .ck-idle,
    .ck-cta[data-state="driving"] .ck-drive,
    .ck-cta[data-state="success"] .ck-ok {
        opacity: 1;
        visibility: visible;
        transform: none;
        transition: opacity .3s .1s, transform .3s .1s, visibility 0s;
    }

    .ck-cta[data-state="driving"] .ck-drive { opacity: .45; }

    .ck-ring { stroke-dasharray: 63; stroke-dashoffset: 63; }
    .ck-mark { stroke-dasharray: 16; stroke-dashoffset: 16; }

    .ck-cta[data-state="success"] .ck-checkicon {
        animation: ck-pop .45s ease-out;
    }

    .ck-cta[data-state="success"] .ck-ring {
        animation: ck-draw .55s ease-out forwards;
    }

    .ck-cta[data-state="success"] .ck-mark {
        animation: ck-draw .35s .4s ease-out forwards;
    }

    .ck-flash {
        display: inline-block;
        animation: ck-flash .6s ease-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .ck-cta, .ck-st, .ck-cta::before, .ck-road {
            transition: none !important;
        }

        .ck-cta:hover:not(:disabled) { transform: none; }

        .ck-cta[data-state="success"],
        .ck-cta[data-state="success"] .ck-checkicon,
        .ck-flash {
            animation: none !important;
        }

        .ck-cta[data-state="driving"] .ck-fill {
            animation: none !important;
            width: 100%;
        }

        .ck-cta[data-state="driving"] .ck-road {
            animation: none !important;
        }

        .ck-cta[data-state="driving"] .ck-truck {
            animation: none !important;
            left: 50%;
            transform: translate(-50%,-50%);
        }

        .ck-truck svg { animation: none !important; }

        .ck-ring, .ck-mark {
            animation: none !important;
            stroke-dashoffset: 0 !important;
        }
    }
</style>

<div class="bg-[#0e0f0d] text-[#f6f2e9]">
    <div class="mx-auto max-w-7xl px-4 pb-20 pt-8 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb"
             class="mb-6 flex flex-wrap items-center gap-2 text-[12px] text-stone-500">
            <a href="{{ url('/') }}" class="transition hover:text-[#f1cf8b]">Home</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <a href="{{ route('cart.index') }}" class="transition hover:text-[#f1cf8b]">Cart</a>
            <i data-lucide="chevron-right" class="h-3 w-3"></i>
            <span class="font-semibold text-[#f1cf8b]" aria-current="page">Checkout</span>
        </nav>

        {{-- Header --}}
        <header class="mb-8">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#f1cf8b]">
                Almost there
            </p>
            <h1 class="ck-serif mt-3 text-4xl leading-[1.02] tracking-tight sm:text-5xl">
                Checkout
            </h1>
            <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                Tell us where to deliver, choose how you'd like to pay, and review your order.
            </p>
        </header>

        {{-- Server validation errors --}}
        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4"
                 role="alert">
                <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 flex-none text-[#ed8b79]"></i>
                <div class="text-xs font-semibold text-[#f3b3a7]">
                    <p>Please check your checkout details.</p>
                    @foreach ($errors->all() as $error)
                        <p class="mt-1">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Success message after the server has created the order --}}
        @if (session('order_success'))
            <section class="mb-8 rounded-3xl border border-[#b8d985]/25 bg-[#b8d985]/10 p-6 sm:p-8"
                     role="status">
                <div class="flex items-start gap-4">
                    <span class="grid h-12 w-12 flex-none place-items-center rounded-2xl bg-[#b8d985]/15 text-[#b8d985]">
                        <i data-lucide="check-circle-2" class="h-7 w-7"></i>
                    </span>
                    <div>
                        <h2 class="ck-serif text-2xl text-[#d9ec9f]">Order placed successfully!</h2>
                        <p class="mt-2 text-sm text-stone-300">
                            Thank you, {{ auth()->user()->name }}. Your order has been recorded.
                        </p>
                        <p class="mt-3 text-sm text-stone-400">
                            Order number:
                            <strong class="text-[#f1cf8b]">{{ session('order_success.number') }}</strong>
                        </p>
                        <p class="mt-1 text-sm text-stone-400">
                            Total:
                            <strong class="text-[#f1cf8b]">
                                {{ $money(session('order_success.total')) }}
                            </strong>
                        </p>
                        <a href="{{ url('/') }}#discover"
                           class="mt-5 inline-flex items-center gap-2 rounded-full bg-[#C6A66B] px-5 py-3 text-sm font-bold text-[#17130D] transition hover:bg-[#D7B77E]">
                            Continue browsing
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        @if ($items->isEmpty())
            <section class="rounded-3xl border border-white/[.075] bg-[#191815] p-8 text-center sm:p-12">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-[#d7a858]/10 text-[#d7a858]">
                    <i data-lucide="shopping-bag" class="h-8 w-8"></i>
                </span>
                <h2 class="ck-serif mt-5 text-2xl">Your cart is empty</h2>
                <p class="mt-2 text-sm text-stone-400">
                    Add some delicious food before checking out.
                </p>
                <a href="{{ url('/') }}#discover"
                   class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#C6A66B] px-6 py-3 text-sm font-bold text-[#17130D] transition hover:bg-[#D7B77E]">
                    Explore menu
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </section>
        @else

            <form id="checkoutForm"
                  method="POST"
                  action="{{ route('checkout.store') }}"
                  class="grid gap-6 lg:grid-cols-[1.25fr_.9fr] lg:gap-8">
                @csrf

                <div class="space-y-6">

                    {{-- Delivery information --}}
                    <section class="rounded-3xl border border-white/[.075] bg-[#191815] p-5 sm:p-8"
                             aria-labelledby="deliveryTitle">
                        <div class="mb-6 flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#d7a858]/15 text-[#d7a858]">
                                <i data-lucide="map-pin" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h2 id="deliveryTitle" class="ck-serif text-2xl tracking-tight">
                                    Delivery information
                                </h2>
                                <p class="text-[11px] text-stone-500">
                                    Fields marked <span class="text-[#ed8b79]">*</span> are required
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">

                            <div>
                                <label for="name" class="mb-2 flex items-center gap-2 text-xs font-bold">
                                    <i data-lucide="user" class="h-3.5 w-3.5 text-[#d7a858]"></i>
                                    Full name <span class="text-[#ed8b79]">*</span>
                                </label>
                                <input id="name" name="name" type="text" maxlength="255"
                                       autocomplete="name" required
                                       value="{{ old('name', auth()->user()?->name) }}"
                                       placeholder="Your full name"
                                       class="{{ $cls('name') }}">
                                <p data-error="name"
                                   class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('name') ? '' : 'hidden' }}">
                                    {{ $errors->first('name') }}
                                </p>
                            </div>

                            <div>
                                <label for="phone" class="mb-2 flex items-center gap-2 text-xs font-bold">
                                    <i data-lucide="phone" class="h-3.5 w-3.5 text-[#d7a858]"></i>
                                    Phone number <span class="text-[#ed8b79]">*</span>
                                </label>
                                <input id="phone" name="phone" type="tel" maxlength="20"
                                       inputmode="tel" autocomplete="tel" required
                                       value="{{ old('phone') }}"
                                       placeholder="01XXXXXXXXX"
                                       class="{{ $cls('phone') }}">
                                <p data-error="phone"
                                   class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('phone') ? '' : 'hidden' }}">
                                    {{ $errors->first('phone') }}
                                </p>
                            </div>

                            {{-- Delivery zone --}}
                            <fieldset class="sm:col-span-2">
                                <legend class="mb-2 flex items-center gap-2 text-xs font-bold">
                                    <i data-lucide="map" class="h-3.5 w-3.5 text-[#d7a858]"></i>
                                    Delivery location <span class="text-[#ed8b79]">*</span>
                                </legend>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    @foreach ($zones as $key => $z)
                                        <label class="relative block cursor-pointer">
                                            <input type="radio"
                                                   name="delivery_zone"
                                                   value="{{ $key }}"
                                                   data-fee="{{ $z['fee'] }}"
                                                   data-label="{{ $z['label'] }}"
                                                   class="peer sr-only"
                                                   @checked($zone === $key)>

                                            <span class="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#11120f] p-4 pr-11 transition hover:border-white/25 peer-checked:border-[#d7a858] peer-checked:bg-[#d7a858]/10 peer-focus-visible:ring-4 peer-focus-visible:ring-[#d7a858]/20">
                                                <span class="grid h-10 w-10 flex-none place-items-center rounded-xl bg-[#d7a858]/15 text-[#d7a858]">
                                                    <i data-lucide="{{ $key === 'inside_dhaka' ? 'building-2' : 'map' }}"
                                                       class="h-5 w-5"></i>
                                                </span>
                                                <span class="min-w-0">
                                                    <span class="block text-sm font-extrabold">{{ $z['label'] }}</span>
                                                    <span class="mt-0.5 block text-[11px] text-stone-500">
                                                        Delivery charge
                                                        <b class="font-extrabold text-[#f1cf8b]">{{ $money($z['fee']) }}</b>
                                                    </span>
                                                </span>
                                            </span>

                                            <span class="pointer-events-none absolute right-4 top-1/2 grid h-5 w-5 -translate-y-1/2 place-items-center rounded-full border border-white/25 text-[#161714] transition [&>svg]:opacity-0 peer-checked:border-[#d7a858] peer-checked:bg-[#d7a858] peer-checked:[&>svg]:opacity-100">
                                                <i data-lucide="check" class="h-3 w-3"></i>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <p data-error="delivery_zone"
                                   class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('delivery_zone') ? '' : 'hidden' }}">
                                    {{ $errors->first('delivery_zone') }}
                                </p>
                            </fieldset>

                            <div class="sm:col-span-2">
                                <label for="address" class="mb-2 flex items-center gap-2 text-xs font-bold">
                                    <i data-lucide="home" class="h-3.5 w-3.5 text-[#d7a858]"></i>
                                    Delivery address <span class="text-[#ed8b79]">*</span>
                                </label>
                                <textarea id="address" name="address" rows="3" maxlength="2000"
                                          autocomplete="street-address" required
                                          placeholder="House, road, area, city"
                                          class="{{ $cls('address') }} resize-y">{{ old('address') }}</textarea>
                                <p data-error="address"
                                   class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('address') ? '' : 'hidden' }}">
                                    {{ $errors->first('address') }}
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="instructions"
                                       class="mb-2 flex items-center justify-between gap-2 text-xs font-bold">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="sticky-note" class="h-3.5 w-3.5 text-[#d7a858]"></i>
                                        Delivery instructions
                                        <span class="text-[10px] font-medium text-stone-600">Optional</span>
                                    </span>
                                    <span id="noteCount" class="font-mono text-[10px] font-medium text-stone-600">0/300</span>
                                </label>
                                <textarea id="instructions" name="instructions" rows="3" maxlength="300"
                                          placeholder="Landmark, gate code, or anything the rider should know"
                                          class="{{ $cls('instructions') }} resize-y">{{ old('instructions') }}</textarea>
                                <p data-error="instructions"
                                   class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('instructions') ? '' : 'hidden' }}">
                                    {{ $errors->first('instructions') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Payment method --}}
                    <section class="rounded-3xl border border-white/[.075] bg-[#191815] p-5 sm:p-8"
                             aria-labelledby="payTitle">
                        <div class="mb-6 flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#d7a858]/15 text-[#d7a858]">
                                <i data-lucide="wallet" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h2 id="payTitle" class="ck-serif text-2xl tracking-tight">Payment method</h2>
                                <p class="text-[11px] text-stone-500">Choose how you'd like to pay</p>
                            </div>
                        </div>

                        <label class="relative block cursor-pointer">
                            <input
                                type="radio"
                                name="payment_method"
                                value="cash_on_delivery"
                                class="peer sr-only"
                               @checked(old('payment_method', 'cash_on_delivery') === 'cash_on_delivery')
                            >

                            <span class="flex items-center gap-4 rounded-2xl border border-white/10 bg-[#11120f] p-4 pr-12 transition hover:border-white/25 peer-checked:border-[#d7a858] peer-checked:bg-[#d7a858]/10 peer-focus-visible:ring-4 peer-focus-visible:ring-[#d7a858]/20">
                                <span class="grid h-11 w-11 flex-none place-items-center rounded-xl bg-[#d7a858]/15 text-[#d7a858]">
                                    <i data-lucide="banknote" class="h-6 w-6"></i>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-sm font-extrabold">Cash on Delivery</span>
                                    <span class="mt-0.5 block text-[11px] leading-5 text-stone-500">
                                        Pay in cash when your order arrives at your door.
                                    </span>
                                </span>
                            </span>

                            <span class="pointer-events-none absolute right-4 top-1/2 grid h-5 w-5 -translate-y-1/2 place-items-center rounded-full border border-white/25 text-[#161714] transition [&>svg]:opacity-0 peer-checked:border-[#d7a858] peer-checked:bg-[#d7a858] peer-checked:[&>svg]:opacity-100">
                                <i data-lucide="check" class="h-3 w-3"></i>
                            </span>
                        </label>

                        <p data-error="payment_method"
                           class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('payment_method') ? '' : 'hidden' }}">
                            {{ $errors->first('payment_method') }}
                        </p>

                        <p class="mt-4 flex items-start gap-2 text-[11px] leading-5 text-stone-500">
                            <i data-lucide="info" class="mt-0.5 h-3.5 w-3.5 flex-none text-[#d7a858]"></i>
                            More payment options will appear here when they become available.
                        </p>
                    </section>
                </div>

                {{-- Order summary --}}
                <aside class="lg:sticky lg:top-6 lg:self-start"
                       aria-labelledby="summaryTitle"
                       data-subtotal="{{ $subtotal }}">

                    <section class="rounded-3xl border border-white/[.075] bg-[#191815] p-5 sm:p-7">
                        <div class="mb-5 flex items-center justify-between gap-3">
                            <h2 id="summaryTitle" class="ck-serif text-2xl tracking-tight">Order summary</h2>
                            <span class="rounded-full bg-white/5 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-wider text-stone-500">
                                {{ $items->sum('qty') }} items
                            </span>
                        </div>

                        <ul class="max-h-[340px] space-y-4 overflow-y-auto pr-1">
                            @foreach ($items as $item)
                                <li class="flex items-center gap-3">
                                    @if ($item['image'])
                                        <img src="{{ $item['image'] }}"
                                             alt="{{ $item['name'] }}"
                                             loading="lazy"
                                             class="h-14 w-14 flex-none rounded-xl object-cover">
                                    @else
                                        <span class="grid h-14 w-14 flex-none place-items-center rounded-xl bg-gradient-to-br from-[#24251e] to-[#11120f] text-[#d7a858]/80">
                                            <i data-lucide="utensils" class="h-6 w-6"></i>
                                        </span>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-[13px] font-extrabold">{{ $item['name'] }}</p>
                                        <p class="mt-0.5 truncate text-[11px] text-stone-500">{{ $item['restaurant'] }}</p>
                                        <p class="mt-1 text-[11px] text-stone-400">
                                            Qty {{ $item['qty'] }} × {{ $money($item['price']) }}
                                        </p>
                                    </div>

                                    <p class="flex-none text-[13px] font-extrabold">
                                        {{ $money($item['qty'] * $item['price']) }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="mt-6 space-y-3 border-t border-white/10 pt-5 text-[13px]">
                            <div class="flex justify-between">
                                <dt class="text-stone-500">Subtotal</dt>
                                <dd class="font-semibold">{{ $money($subtotal) }}</dd>
                            </div>

                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-stone-500">
                                    Delivery fee
                                    <span id="feeZone" class="block text-[11px] text-stone-600">
                                        {{ $zones[$zone]['label'] }}
                                    </span>
                                </dt>
                                <dd id="feeValue" class="font-semibold">{{ $money($deliveryFee) }}</dd>
                            </div>

                            <div class="flex items-center justify-between border-t border-white/10 pt-4">
                                <dt class="text-sm font-extrabold">Total</dt>
                                <dd id="totalValue" class="text-2xl font-extrabold text-[#f1cf8b]">
                                    {{ $money($total) }}
                                </dd>
                            </div>
                        </dl>

                        <button type="submit"
                                id="placeBtn"
                                class="ck-cta mt-6"
                                data-state="idle">
                            <span class="ck-fill"></span>
                            <span class="ck-road"></span>
                            <span class="ck-truck" aria-hidden="true">
                                <i data-lucide="truck" class="h-7 w-7"></i>
                            </span>

                            <span class="ck-st ck-idle">
                                <i data-lucide="lock" class="h-[18px] w-[18px]"></i>
                                Place Order
                            </span>

                            <span class="ck-st ck-drive">Placing your order...</span>

                            <span class="ck-st ck-ok">
                                <svg class="ck-checkicon h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle class="ck-ring" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="2"/>
                                    <path class="ck-mark" d="M7 12.5l3.2 3.2L17 9"
                                          stroke="currentColor" stroke-width="2.4"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Order Placed Successfully!
                            </span>
                        </button>

                        <span id="placeLive" class="sr-only" aria-live="polite"></span>

                        <p id="ckHint" class="mt-4 flex items-center justify-center gap-2 text-center text-[11px] text-stone-500">
                            <i data-lucide="shield-check" class="h-3.5 w-3.5 text-[#d7a858]"></i>
                            Pay in cash when your food arrives
                        </p>

                        <p id="submitError"
                           class="mt-3 hidden text-center text-xs text-[#ed8b79]"
                           role="alert"></p>
                    </section>

                    <a href="{{ route('cart.index') }}"
                       class="mt-4 flex items-center justify-center gap-2 text-[12px] font-bold text-stone-400 transition hover:text-[#f1cf8b]">
                        <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                        Back to cart
                    </a>
                </aside>
            </form>
        @endif
    </div>
</div>


<script>
(() => {
    const renderIcons = () => {
        if (window.lucide?.createIcons) {
            window.lucide.createIcons();
        }
    };

    if (window.lucide) {
        renderIcons();
    } else {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/lucide@0.469.0';
        script.onload = renderIcons;
        document.head.appendChild(script);
    }

    const form = document.getElementById('checkoutForm');
    if (!form) return;

    const btn = document.getElementById('placeBtn');
    const live = document.getElementById('placeLive');
    const note = document.getElementById('instructions');
    const noteCount = document.getElementById('noteCount');
    const feeEl = document.getElementById('feeValue');
    const feeZoneEl = document.getElementById('feeZone');
    const totalEl = document.getElementById('totalValue');
    const submitError = document.getElementById('submitError');

    if (!btn) return;

    const subtotal = Number(
        document.querySelector('[data-subtotal]')?.dataset.subtotal ?? 0
    );

    const reduceMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    const wait = ms => new Promise(resolve => setTimeout(resolve, ms));

    const money = n => '৳' + Number(n).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    let busy = false;

    let shownFee = Number(
        form.querySelector(
            'input[name="delivery_zone"]:checked'
        )?.dataset.fee ?? 0
    );

    const selectedZone = () =>
        form.querySelector('input[name="delivery_zone"]:checked');

    const tween = (element, from, to) => {
        if (!element) return;

        if (reduceMotion || from === to) {
            element.textContent = money(to);
            return;
        }

        const start = performance.now();
        const duration = 350;

        const step = now => {
            const progress = Math.min(1, (now - start) / duration);

            element.textContent = money(
                from + (to - from) * progress
            );

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };

        requestAnimationFrame(step);
    };

    const applyZone = () => {
        const radio = selectedZone();
        if (!radio) return;

        const fee = Number(radio.dataset.fee);

        tween(feeEl, shownFee, fee);
        tween(totalEl, subtotal + shownFee, subtotal + fee);

        if (feeZoneEl) {
            feeZoneEl.textContent = radio.dataset.label;
        }

        shownFee = fee;
    };

    form.querySelectorAll(
        'input[name="delivery_zone"]'
    ).forEach(radio => {
        radio.addEventListener('change', applyZone);
    });

    const setState = (state, message = '') => {
        btn.dataset.state = state;
        btn.setAttribute(
            'aria-busy',
            state === 'driving' ? 'true' : 'false'
        );

        if (live) {
            live.textContent = message;
        }
    };

    const setBusy = locked => {
        busy = locked;
        btn.disabled = locked;
        btn.setAttribute('aria-busy', locked ? 'true' : 'false');
    };

    const clearErrors = () => {
        submitError?.classList.add('hidden');

        form.querySelectorAll('[data-error]').forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });

        form.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
    };

    const showErrors = errors => {
        Object.entries(errors).forEach(([key, messages]) => {
            const message = Array.isArray(messages)
                ? messages[0]
                : messages;

            const slot = form.querySelector(
                `[data-error="${key}"]`
            );

            const input = form.elements.namedItem(key);

            if (slot) {
                slot.textContent = message;
                slot.classList.remove('hidden');
            } else if (key === 'cart' || key === 'checkout') {
                if (submitError) {
                    submitError.textContent = message;
                    submitError.classList.remove('hidden');
                }
            }

            if (input?.classList) {
                input.classList.add('is-invalid');
            }
        });

        const firstKey = Object.keys(errors)[0];
        const firstInput = firstKey
            ? form.elements.namedItem(firstKey)
            : null;

        if (firstInput?.focus) {
            firstInput.focus({ preventScroll: true });
        }
    };

    const validate = () => {
        const errors = {};

        const name = form.elements.namedItem('name')?.value.trim() ?? '';
        const phone = form.elements.namedItem('phone')?.value.trim() ?? '';
        const address = form.elements.namedItem('address')?.value.trim() ?? '';

        const digits = phone.replace(/\D/g, '');

        if (!name) {
            errors.name = ['Please enter your full name.'];
        }

        if (!phone) {
            errors.phone = ['Please enter your phone number.'];
        } else if (digits.length < 10 || digits.length > 15) {
            errors.phone = ['Enter a valid phone number.'];
        }

        if (!selectedZone()) {
            errors.delivery_zone = [
                'Please choose your delivery location.'
            ];
        }

        if (!address) {
            errors.address = [
                'Please enter your delivery address.'
            ];
        } else if (address.length < 10) {
            errors.address = [
                'Please add a few more address details.'
            ];
        }

        if (
            !form.querySelector(
                'input[name="payment_method"]:checked'
            )
        ) {
            errors.payment_method = [
                'Please choose a payment method.'
            ];
        }

        return errors;
    };

    form.addEventListener('submit', async event => {
        event.preventDefault();

        if (busy) return;

        clearErrors();

        const clientErrors = validate();

        if (Object.keys(clientErrors).length) {
            showErrors(clientErrors);
            return;
        }

        setBusy(true);
        setState('driving', 'Placing your order...');

        const startedAt = Date.now();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const contentType =
                response.headers.get('content-type') || '';

            let data = {};

            if (contentType.includes('application/json')) {
                data = await response.json();
            }

            if (response.status === 422) {
                const remaining = Math.max(
                    0,
                    2400 - (Date.now() - startedAt)
                );

                if (!reduceMotion) {
                    await wait(remaining);
                }

                setBusy(false);
                setState('idle');

                showErrors(data.errors || {
                    checkout: [
                        'Please review your checkout details.'
                    ]
                });

                return;
            }

            if (!response.ok) {
                throw new Error(
                    data.message ||
                    'Could not place your order. Please try again.'
                );
            }

            const remaining = Math.max(
                0,
                2400 - (Date.now() - startedAt)
            );

            if (!reduceMotion) {
                await wait(remaining);
            }

            // SUCCESS: keep the user on this checkout page.
            if (data.success) {
    setState(
        'success',
        `Order placed successfully! #${data.order_number} · Total ${money(data.total)}`
    );

    btn.disabled = true;

    if (live) {
        live.textContent =
            `Order placed successfully! #${data.order_number}`;
    }

    btn.setAttribute(
        'aria-label',
        `Order placed successfully. Order number ${data.order_number}`
    );

    await wait(2000);

window.location.assign(
    data.redirect || '/dashboard'
);

return;
}

            // Compatibility with any existing redirect response.
            if (data.redirect) {
                window.location.assign(data.redirect);
                return;
            }

            if (response.redirected) {
                window.location.assign(response.url);
                return;
            }

            throw new Error(
                'The server response did not confirm the order. Please check your orders before trying again.'
            );

        } catch (error) {
            setBusy(false);
            setState('idle');

            if (submitError) {
                submitError.textContent =
                    error.message ||
                    'Something went wrong. Please try again.';

                submitError.classList.remove('hidden');
            }
        }
    });

    form.querySelectorAll('input, textarea').forEach(element => {
        element.addEventListener('input', () => {
            element.classList.remove('is-invalid');

            const slot = form.querySelector(
                `[data-error="${element.name}"]`
            );

            if (slot) {
                slot.textContent = '';
                slot.classList.add('hidden');
            }

            submitError?.classList.add('hidden');
        });
    });

    const updateCount = () => {
        if (note && noteCount) {
            noteCount.textContent = note.value.length + '/300';
        }
    };

    note?.addEventListener('input', updateCount);
    updateCount();
})();
</script>

@endsection
