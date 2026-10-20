
@extends('layouts.app')

@section('title', $restaurant->name . ' — NOOR')

@section('content')

<!-- RESTAURANT HERO -->
<section class="relative overflow-hidden border-b border-white/10">

    <div class="h-[340px] sm:h-[430px] relative">

        <img
            src="{{ $restaurant->image ? asset('storage/' . $restaurant->image) : 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=1800&q=85' }}"
            alt="{{ $restaurant->name }}"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-gradient-to-t from-[#11110f] via-[#11110f]/55 to-black/10"></div>

        <div class="absolute top-6 left-5 sm:left-10">
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-black/30 backdrop-blur-md px-4 py-2 text-xs text-[#f4f0e8] hover:border-[#d8a85a]/50 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to kitchens
            </a>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 pb-8">

                <div class="mb-4">
                    <span class="inline-flex items-center rounded-full bg-[#d8a85a] px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#11110f]">
                        {{ ucfirst($restaurant->status) }}
                    </span>
                </div>

                <h1 class="font-noor-serif text-5xl sm:text-6xl lg:text-7xl text-[#f4f0e8] leading-none">
                    {{ $restaurant->name }}
                </h1>

                <p class="mt-3 text-sm sm:text-base text-[#d7cabb]">
                    {{ $restaurant->description ?: 'Discover the kitchen and explore its menu.' }}
                </p>

            </div>
        </div>
    </div>

</section>


<!-- RESTAURANT INFO -->
<section class="border-b border-white/10 bg-[#11110f]">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-7">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            <div class="flex flex-wrap items-center gap-x-7 gap-y-4">

                <div class="flex items-center gap-2 text-[#d7cabb] text-sm">
                    <i data-lucide="map-pin" class="w-4 h-4 text-[#d8a85a]"></i>
                    {{ $restaurant->address ?: 'Location not provided' }}
                </div>

                @if($restaurant->phone)
                    <div class="h-5 w-px bg-white/10"></div>

                    <div class="flex items-center gap-2 text-[#d7cabb] text-sm">
                        <i data-lucide="phone" class="w-4 h-4 text-[#d8a85a]"></i>
                        {{ $restaurant->phone }}
                    </div>
                @endif

                <div class="h-5 w-px bg-white/10"></div>

                <div class="flex items-center gap-2 text-[#d7cabb] text-sm">
                    <i data-lucide="utensils" class="w-4 h-4 text-[#d8a85a]"></i>
                    {{ $restaurant->menuItems->count() }} available dishes
                </div>

            </div>

            <span class="text-xs text-[#9d978e]">
                NOOR Kitchen
            </span>

        </div>

    </div>
</section>


<!-- RESTAURANT CONTENT -->
<main class="bg-[#11110f]">

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-12">

        <div class="grid lg:grid-cols-[1fr_320px] gap-10 lg:gap-16">

            <!-- MENU -->
            <div>

                <div class="max-w-2xl mb-12">

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.25em] text-[#d8a85a] mb-4">
                        The kitchen
                    </p>

                    <h2 class="font-noor-serif text-4xl sm:text-5xl text-[#f4f0e8] leading-tight">
                        Food worth<br class="hidden sm:block">
                        coming back for.
                    </h2>

                    <p class="mt-5 text-sm leading-7 text-[#9d978e] max-w-xl">
                        {{ $restaurant->description ?: 'Explore the dishes prepared by ' . $restaurant->name . '.' }}
                    </p>

                </div>


                <!-- MENU NAVIGATION -->
                <div class="sticky top-0 z-20 -mx-5 px-5 sm:mx-0 sm:px-0 py-4 bg-[#11110f]/95 backdrop-blur-md border-b border-white/10 mb-10">

                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">

                        <a href="#popular"
                           class="shrink-0 rounded-full bg-[#d8a85a] text-[#11110f] px-5 py-2.5 text-xs font-semibold">
                            All dishes
                        </a>

                    </div>

                </div>


                <!-- DATABASE MENU ITEMS -->
                <section id="popular" class="mb-14">

                    <div class="flex items-end justify-between mb-6">

                        <div>
                            <p class="font-noor-mono text-[10px] uppercase tracking-[0.22em] text-[#9d978e] mb-2">
                                Explore the menu
                            </p>

                            <h3 class="font-noor-serif text-3xl text-[#f4f0e8]">
                                Available dishes
                            </h3>
                        </div>

                    </div>


                    @forelse($restaurant->menuItems as $item)

                        <article class="group mb-5 rounded-[22px] overflow-hidden border border-white/10 bg-[#1a1916] hover:border-[#d8a85a]/30 transition">

                            <div class="flex gap-4 p-4">

                                @if($item->image)

                                    <img
                                        src="{{ asset('storage/' . $item->image) }}"
                                        alt="{{ $item->name }}"
                                        class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl object-cover shrink-0"
                                    >

                                @else

                                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-[#24221d] flex items-center justify-center shrink-0">
                                        <i data-lucide="utensils" class="w-7 h-7 text-[#d8a85a]"></i>
                                    </div>

                                @endif


                                <div class="flex-1 min-w-0 py-1">

                                    <div class="flex justify-between gap-4">

                                        <div class="min-w-0">

                                            <h4 class="font-noor-serif text-xl text-[#f4f0e8]">
                                                {{ $item->name }}
                                            </h4>

                                            <p class="mt-2 text-xs leading-5 text-[#9d978e]">
                                                {{ $item->description ?: 'Freshly prepared for you.' }}
                                            </p>

                                        </div>

                                        <span class="font-noor-mono text-sm text-[#f2d092] whitespace-nowrap">
                                            ৳{{ number_format((float) $item->price, 2) }}
                                        </span>

                                    </div>


                                    <button
                                        type="button"
                                        disabled
                                        title="Cart functionality will be connected next"
                                        class="mt-4 inline-flex items-center gap-2 text-xs text-[#d8a85a] opacity-60 cursor-not-allowed">

                                        Add to bag

                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>

                                    </button>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="rounded-[22px] border border-white/10 bg-[#1a1916] p-8 text-center">

                            <div class="w-14 h-14 rounded-full border border-white/10 mx-auto flex items-center justify-center mb-4">
                                <i data-lucide="utensils" class="w-6 h-6 text-[#d8a85a]"></i>
                            </div>

                            <h4 class="font-noor-serif text-xl text-[#f4f0e8]">
                                No dishes available yet
                            </h4>

                            <p class="mt-2 text-sm text-[#9d978e]">
                                This restaurant hasn't added any available menu items.
                            </p>

                        </div>

                    @endforelse

                </section>

            </div>


            <!-- ORDER SUMMARY -->
            <aside class="lg:sticky lg:top-24 h-fit">

                <div class="rounded-[24px] border border-white/10 bg-[#1a1916] p-6">

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.2em] text-[#9d978e] mb-4">
                        Your order
                    </p>

                    <div class="flex items-center justify-between mb-6">

                        <h3 class="font-noor-serif text-2xl text-[#f4f0e8]">
                            Your bag
                        </h3>

                        <span class="w-7 h-7 rounded-full bg-[#d8a85a] text-[#11110f] text-xs font-bold flex items-center justify-center">
                            0
                        </span>

                    </div>


                    <div class="py-10 border-y border-white/10 text-center">

                        <div class="w-12 h-12 rounded-full border border-white/10 mx-auto flex items-center justify-center mb-4">
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-[#9d978e]"></i>
                        </div>

                        <p class="text-sm text-[#d7cabb]">
                            Your bag is empty.
                        </p>

                        <p class="mt-2 text-xs text-[#9d978e]">
                            Add something delicious to get started.
                        </p>

                    </div>


                    <button
                        type="button"
                        disabled
                        class="mt-6 w-full rounded-full bg-[#d8a85a] py-3.5 text-sm font-semibold text-[#11110f] opacity-60 cursor-not-allowed">
                        View bag
                    </button>

                </div>


                <!-- RESTAURANT DETAILS -->
                <div class="mt-5 rounded-[24px] border border-white/10 p-6">

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.2em] text-[#9d978e] mb-5">
                        Kitchen details
                    </p>

                    <div class="space-y-5">

                        <div class="flex gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#d8a85a] mt-0.5"></i>

                            <div>
                                <p class="text-xs text-[#f4f0e8]">
                                    Location
                                </p>

                                <p class="text-xs text-[#9d978e] mt-1">
                                    {{ $restaurant->address ?: 'Address not provided' }}
                                </p>
                            </div>
                        </div>


                        @if($restaurant->phone)

                            <div class="flex gap-3">
                                <i data-lucide="phone" class="w-4 h-4 text-[#d8a85a] mt-0.5"></i>

                                <div>
                                    <p class="text-xs text-[#f4f0e8]">
                                        Contact
                                    </p>

                                    <p class="text-xs text-[#9d978e] mt-1">
                                        {{ $restaurant->phone }}
                                    </p>
                                </div>
                            </div>

                        @endif


                        <div class="flex gap-3">
                            <i data-lucide="badge-check" class="w-4 h-4 text-[#d8a85a] mt-0.5"></i>

                            <div>
                                <p class="text-xs text-[#f4f0e8]">
                                    NOOR kitchen
                                </p>

                                <p class="text-xs text-[#9d978e] mt-1">
                                    Restaurant status: {{ ucfirst($restaurant->status) }}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </aside>

        </div>

    </div>

</main>

@endsection
