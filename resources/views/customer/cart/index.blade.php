
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#100F0D] px-5 py-12 text-[#F7F1E7] sm:px-8">
    <div class="mx-auto max-w-6xl">

        <a href="{{ url('/') }}"
           class="text-sm text-[#C6A66B] hover:text-white">
            ← Continue browsing
        </a>

        <div class="mb-10 mt-6">
            <p class="text-xs uppercase tracking-[.35em] text-[#C6A66B]">
                NOOR / YOUR SELECTION
            
<a
    href="{{ route('cart.index') }}"
    aria-label="Open shopping bag"
    class="relative
           grid
           h-[38px]
           w-[38px]
           place-items-center
           rounded-full
           border
           border-white/[.094]
           bg-transparent
           transition
           hover:border-gold/40
           hover:bg-white/[.04]">

    <i
        data-lucide="shopping-bag"
        class="w-4">
    </i>

    <span
        id="bagCount"
        data-cart-count
        class="absolute
               -right-[3px]
               -top-[3px]
               grid
               h-4
               min-w-4
               place-items-center
               rounded-full
               bg-gold
               px-1
               text-[8px]
               font-extrabold
               text-night">

        {{ auth()->check() ? auth()->user()->cartItems()->sum('quantity') : 0 }}

    </span>

</a>

            <p class="mt-3 text-sm text-white/50">
                Everything you picked, gathered in one place.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-500/20 bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="rounded-3xl border border-white/10 bg-white/[.025] px-6 py-20 text-center">
                <div class="text-5xl">🛍️</div>
                <h2 class="mt-5 text-2xl font-semibold">Your bag is empty</h2>
                <p class="mt-3 text-sm text-white/50">
                    Discover something delicious and add it to your bag.
                </p>
                <a href="{{ url('/') }}#discover"
                   class="mt-7 inline-flex rounded-full bg-[#C6A66B] px-7 py-3 font-semibold text-[#17130D] transition hover:bg-[#DFC38E]">
                    Explore food
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1fr_350px]">

                <div class="space-y-4">
                    @foreach ($cartItems as $item)
                        @php
                            $food = $item->menuItem;
                            $image = $food->image
                                ? \Illuminate\Support\Facades\Storage::url($food->image)
                                : null;
                        @endphp

                        <article
                            class="flex flex-col gap-5 rounded-2xl border border-white/10 bg-white/[.025] p-4 sm:flex-row sm:items-center sm:p-5">

                            <div class="h-28 w-full shrink-0 overflow-hidden rounded-xl bg-white/5 sm:w-28">
                                @if ($image)
                                    <img src="{{ $image }}"
                                         alt="{{ $food->name }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-3xl">
                                        🍽️
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg font-semibold">
                                    {{ $food->name }}
                                </h2>

                                @if ($food->restaurant)
                                    <p class="mt-1 text-sm text-white/45">
                                        {{ $food->restaurant->name }}
                                    </p>
                                @endif

                                <p class="mt-3 text-sm text-[#C6A66B]">
                                    ৳{{ number_format((float) $food->price, 2) }} each
                                </p>
                            </div>

                            <div class="flex items-center justify-between gap-4 sm:flex-col sm:items-end">
                                <form action="{{ route('cart.update', $item) }}"
                                      method="POST"
                                      class="flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')

                                    <label class="sr-only" for="quantity-{{ $item->id }}">
                                        Quantity
                                    </label>

                                    <input
                                        id="quantity-{{ $item->id }}"
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        max="99"
                                        value="{{ $item->quantity }}"
                                        class="w-20 rounded-lg border border-white/10 bg-[#191815] px-3 py-2 text-center text-white outline-none focus:border-[#C6A66B]">

                                    <button type="submit"
                                            class="text-xs text-[#C6A66B] hover:text-white">
                                        Update
                                    </button>
                                </form>

                                <p class="font-semibold">
                                    ৳{{ number_format((float) $food->price * $item->quantity, 2) }}
                                </p>

                                <form action="{{ route('cart.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remove this item from your bag?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-xs text-red-400/80 transition hover:text-red-300">
                                        Remove item
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="h-fit rounded-2xl border border-white/10 bg-white/[.025] p-6 lg:sticky lg:top-8">
                    <h2 class="text-xl font-semibold">Order summary</h2>

                    <div class="mt-6 flex justify-between text-sm text-white/60">
                        <span>Items</span>
                        <span>{{ $cartItems->sum('quantity') }}</span>
                    </div>

                    <div class="mt-4 flex justify-between text-sm text-white/60">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="my-6 border-t border-white/10"></div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold">Total</span>
                        <span class="text-xl font-semibold text-[#C6A66B]">
                            ৳{{ number_format($subtotal, 2) }}
                        </span>
                    </div>

                    <p class="mt-4 text-xs leading-5 text-white/40">
                        Delivery charges, if applicable, will be calculated at checkout.
                    </p>

                    <a href="{{ route('checkout.index') }}"
                        class="mt-7 flex w-full items-center justify-center gap-2 rounded-full bg-[#C6A66B] px-5 py-3 font-semibold text-[#17130D] transition hover:bg-[#D7B77E] focus:outline-none focus:ring-2 focus:ring-[#C6A66B]/50">
                            <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                            Proceed to Checkout
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>

                        <a href="{{ url('/') }}#discover"
                        class="mt-4 block text-center text-sm text-white/60 transition hover:text-[#C6A66B]">
                            Continue browsing
                        </a>
                </aside>

            </div>
        @endif
    </div>
</div>
@endsection
