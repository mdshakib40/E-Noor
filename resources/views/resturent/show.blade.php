@extends('layouts.app')

@section('title', 'Kacchi Bhai — NOOR')

@section('content')

<!-- =========================
     RESTAURANT HERO
========================= -->
<section class="relative overflow-hidden border-b border-white/10">

    <div class="h-[340px] sm:h-[430px] relative">
        <img
            src="https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=1800&q=85"
            alt="Kacchi Bhai"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-gradient-to-t from-[#11110f] via-[#11110f]/55 to-black/10"></div>

        <div class="absolute top-6 left-5 sm:left-10">
            <a
                href="{{ url('/restaurants') }}"
                class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-black/30 backdrop-blur-md px-4 py-2 text-xs text-[#f4f0e8] hover:border-[#d8a85a]/50 transition"
            >
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to kitchens
            </a>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 pb-8">

                <div class="mb-4">
                    <span class="inline-flex items-center rounded-full bg-[#d8a85a] px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#11110f]">
                        Open now
                    </span>
                </div>

                <h1 class="font-noor-serif text-5xl sm:text-6xl lg:text-7xl text-[#f4f0e8] leading-none">
                    Kacchi Bhai
                </h1>

                <p class="mt-3 text-sm sm:text-base text-[#d7cabb]">
                    Traditional Bangladeshi cuisine · Biryani · Small plates
                </p>

            </div>
        </div>
    </div>

</section>


<!-- =========================
     RESTAURANT INFO
========================= -->
<section class="border-b border-white/10 bg-[#11110f]">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-7">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            <div class="flex flex-wrap items-center gap-x-7 gap-y-4">

                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-[#f2d092]">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <span class="font-semibold">4.8</span>
                    </div>

                    <span class="text-xs text-[#9d978e]">
                        (248 reviews)
                    </span>
                </div>

                <div class="h-5 w-px bg-white/10"></div>

                <div class="flex items-center gap-2 text-[#d7cabb] text-sm">
                    <i data-lucide="clock-3" class="w-4 h-4 text-[#d8a85a]"></i>
                    25–35 min
                </div>

                <div class="h-5 w-px bg-white/10"></div>

                <div class="flex items-center gap-2 text-[#d7cabb] text-sm">
                    <i data-lucide="bike" class="w-4 h-4 text-[#d8a85a]"></i>
                    ৳40 delivery
                </div>

                <div class="h-5 w-px bg-white/10"></div>

                <div class="flex items-center gap-2 text-[#d7cabb] text-sm">
                    <i data-lucide="map-pin" class="w-4 h-4 text-[#d8a85a]"></i>
                    Pabna
                </div>

            </div>

            <button
                type="button"
                class="w-11 h-11 rounded-full border border-white/10 flex items-center justify-center text-[#d7cabb] hover:text-[#d8a85a] hover:border-[#d8a85a]/40 transition"
            >
                <i data-lucide="heart" class="w-5 h-5"></i>
            </button>

        </div>

    </div>
</section>


<!-- =========================
     RESTAURANT CONTENT
========================= -->
<main class="bg-[#11110f]">

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-12">

        <div class="grid lg:grid-cols-[1fr_320px] gap-10 lg:gap-16">

            <!-- =====================
                 MENU
            ====================== -->
            <div>

                <!-- Intro -->
                <div class="max-w-2xl mb-12">

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.25em] text-[#d8a85a] mb-4">
                        The kitchen
                    </p>

                    <h2 class="font-noor-serif text-4xl sm:text-5xl text-[#f4f0e8] leading-tight">
                        Food worth<br class="hidden sm:block">
                        coming back for.
                    </h2>

                    <p class="mt-5 text-sm leading-7 text-[#9d978e] max-w-xl">
                        Slow-cooked classics, fragrant rice and plates made
                        with the kind of patience good food deserves.
                    </p>

                </div>


                <!-- Menu Navigation -->
                <div class="sticky top-0 z-20 -mx-5 px-5 sm:mx-0 sm:px-0 py-4 bg-[#11110f]/95 backdrop-blur-md border-b border-white/10 mb-10">

                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">

                        <a
                            href="#popular"
                            class="shrink-0 rounded-full bg-[#d8a85a] text-[#11110f] px-5 py-2.5 text-xs font-semibold"
                        >
                            Popular
                        </a>

                        <a
                            href="#biryani"
                            class="shrink-0 rounded-full border border-white/10 text-[#d7cabb] px-5 py-2.5 text-xs hover:border-[#d8a85a]/50 transition"
                        >
                            Biryani
                        </a>

                        <a
                            href="#small-plates"
                            class="shrink-0 rounded-full border border-white/10 text-[#d7cabb] px-5 py-2.5 text-xs hover:border-[#d8a85a]/50 transition"
                        >
                            Small plates
                        </a>

                        <a
                            href="#drinks"
                            class="shrink-0 rounded-full border border-white/10 text-[#d7cabb] px-5 py-2.5 text-xs hover:border-[#d8a85a]/50 transition"
                        >
                            Drinks
                        </a>

                    </div>

                </div>


                <!-- =====================
                     POPULAR
                ====================== -->
                <section id="popular" class="mb-14">

                    <div class="flex items-end justify-between mb-6">

                        <div>
                            <p class="font-noor-mono text-[10px] uppercase tracking-[0.22em] text-[#9d978e] mb-2">
                                Start here
                            </p>

                            <h3 class="font-noor-serif text-3xl text-[#f4f0e8]">
                                Most loved
                            </h3>
                        </div>

                    </div>


                    <div class="grid sm:grid-cols-2 gap-5">

                        <!-- Food Card -->
                        <article class="group rounded-[22px] overflow-hidden border border-white/10 bg-[#1a1916] hover:border-[#d8a85a]/30 transition">

                            <div class="relative h-56 overflow-hidden">

                                <img
                                    src="https://images.unsplash.com/photo-1563379091339-03246963d96c?auto=format&fit=crop&w=900&q=85"
                                    alt="Kacchi Biryani"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                >

                                <div class="absolute top-4 left-4">
                                    <span class="rounded-full bg-[#11110f]/80 backdrop-blur-md px-3 py-1.5 text-[10px] uppercase tracking-wider text-[#f2d092]">
                                        Bestseller
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    class="absolute right-4 bottom-4 w-11 h-11 rounded-full bg-[#f4f0e8] text-[#11110f] flex items-center justify-center shadow-lg hover:bg-[#f2d092] transition"
                                >
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                </button>

                            </div>

                            <div class="p-5">

                                <div class="flex items-start justify-between gap-4">

                                    <div>
                                        <h4 class="font-noor-serif text-xl text-[#f4f0e8]">
                                            Kacchi Biryani
                                        </h4>

                                        <p class="mt-2 text-xs leading-5 text-[#9d978e]">
                                            Fragrant basmati rice, tender mutton and slow-cooked spices.
                                        </p>
                                    </div>

                                    <span class="font-noor-mono text-sm text-[#f2d092] whitespace-nowrap">
                                        ৳280
                                    </span>

                                </div>

                            </div>

                        </article>


                        <!-- Food Card -->
                        <article class="group rounded-[22px] overflow-hidden border border-white/10 bg-[#1a1916] hover:border-[#d8a85a]/30 transition">

                            <div class="relative h-56 overflow-hidden">

                                <img
                                    src="https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?auto=format&fit=crop&w=900&q=85"
                                    alt="Chicken Roast"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                >

                                <button
                                    type="button"
                                    class="absolute right-4 bottom-4 w-11 h-11 rounded-full bg-[#f4f0e8] text-[#11110f] flex items-center justify-center shadow-lg hover:bg-[#f2d092] transition"
                                >
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                </button>

                            </div>

                            <div class="p-5">

                                <div class="flex items-start justify-between gap-4">

                                    <div>
                                        <h4 class="font-noor-serif text-xl text-[#f4f0e8]">
                                            Chicken Roast
                                        </h4>

                                        <p class="mt-2 text-xs leading-5 text-[#9d978e]">
                                            Rich, smoky roast with a warm spice glaze.
                                        </p>
                                    </div>

                                    <span class="font-noor-mono text-sm text-[#f2d092] whitespace-nowrap">
                                        ৳220
                                    </span>

                                </div>

                            </div>

                        </article>

                    </div>

                </section>


                <!-- =====================
                     BIRYANI
                ====================== -->
                <section id="biryani" class="mb-14">

                    <div class="mb-6">

                        <p class="font-noor-mono text-[10px] uppercase tracking-[0.22em] text-[#9d978e] mb-2">
                            Rice & fire
                        </p>

                        <h3 class="font-noor-serif text-3xl text-[#f4f0e8]">
                            Biryani
                        </h3>

                    </div>


                    <div class="space-y-4">

                        <article class="flex gap-4 p-4 rounded-[20px] border border-white/10 bg-[#1a1916] hover:border-[#d8a85a]/30 transition">

                            <img
                                src="https://images.unsplash.com/photo-1631515242808-497c3fbd3972?auto=format&fit=crop&w=500&q=85"
                                alt="Mutton Biryani"
                                class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl object-cover shrink-0"
                            >

                            <div class="flex-1 min-w-0 py-1">

                                <div class="flex justify-between gap-4">

                                    <div>
                                        <h4 class="font-noor-serif text-xl text-[#f4f0e8]">
                                            Mutton Biryani
                                        </h4>

                                        <p class="mt-2 text-xs leading-5 text-[#9d978e] max-w-md">
                                            Long-grain rice layered with tender mutton and aromatic spices.
                                        </p>
                                    </div>

                                    <span class="font-noor-mono text-sm text-[#f2d092]">
                                        ৳320
                                    </span>

                                </div>

                                <button
                                    type="button"
                                    class="mt-4 inline-flex items-center gap-2 text-xs text-[#d8a85a] hover:text-[#f2d092] transition"
                                >
                                    Add to bag
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                </button>

                            </div>

                        </article>


                        <article class="flex gap-4 p-4 rounded-[20px] border border-white/10 bg-[#1a1916] hover:border-[#d8a85a]/30 transition">

                            <img
                                src="https://images.unsplash.com/photo-1599043513900-ed6fe01d3833?auto=format&fit=crop&w=500&q=85"
                                alt="Chicken Biryani"
                                class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl object-cover shrink-0"
                            >

                            <div class="flex-1 min-w-0 py-1">

                                <div class="flex justify-between gap-4">

                                    <div>
                                        <h4 class="font-noor-serif text-xl text-[#f4f0e8]">
                                            Chicken Biryani
                                        </h4>

                                        <p class="mt-2 text-xs leading-5 text-[#9d978e] max-w-md">
                                            Tender chicken, fragrant rice and a slow-built spice base.
                                        </p>
                                    </div>

                                    <span class="font-noor-mono text-sm text-[#f2d092]">
                                        ৳240
                                    </span>

                                </div>

                                <button
                                    type="button"
                                    class="mt-4 inline-flex items-center gap-2 text-xs text-[#d8a85a] hover:text-[#f2d092] transition"
                                >
                                    Add to bag
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                </button>

                            </div>

                        </article>

                    </div>

                </section>


                <!-- =====================
                     SMALL PLATES
                ====================== -->
                <section id="small-plates" class="mb-14">

                    <div class="mb-6">

                        <p class="font-noor-mono text-[10px] uppercase tracking-[0.22em] text-[#9d978e] mb-2">
                            A little extra
                        </p>

                        <h3 class="font-noor-serif text-3xl text-[#f4f0e8]">
                            Small plates
                        </h3>

                    </div>


                    <div class="grid sm:grid-cols-2 gap-5">

                        <article class="group rounded-[20px] border border-white/10 bg-[#1a1916] overflow-hidden">

                            <div class="h-44 overflow-hidden">
                                <img
                                    src="https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=700&q=85"
                                    alt="Fuchka"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                >
                            </div>

                            <div class="p-5 flex justify-between gap-4">

                                <div>
                                    <h4 class="font-noor-serif text-lg text-[#f4f0e8]">
                                        Fuchka Plate
                                    </h4>

                                    <p class="mt-1 text-xs text-[#9d978e]">
                                        Tamarind · spice · crunch
                                    </p>
                                </div>

                                <span class="font-noor-mono text-sm text-[#f2d092]">
                                    ৳120
                                </span>

                            </div>

                        </article>


                        <article class="group rounded-[20px] border border-white/10 bg-[#1a1916] overflow-hidden">

                            <div class="h-44 overflow-hidden">
                                <img
                                    src="https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?auto=format&fit=crop&w=700&q=85"
                                    alt="Chui Jhal Chicken"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                >
                            </div>

                            <div class="p-5 flex justify-between gap-4">

                                <div>
                                    <h4 class="font-noor-serif text-lg text-[#f4f0e8]">
                                        Chui Jhal Chicken
                                    </h4>

                                    <p class="mt-1 text-xs text-[#9d978e]">
                                        Slow cooked · fiery · Bengali
                                    </p>
                                </div>

                                <span class="font-noor-mono text-sm text-[#f2d092]">
                                    ৳260
                                </span>

                            </div>

                        </article>

                    </div>

                </section>


                <!-- =====================
                     DRINKS
                ====================== -->
                <section id="drinks">

                    <div class="mb-6">

                        <p class="font-noor-mono text-[10px] uppercase tracking-[0.22em] text-[#9d978e] mb-2">
                            Something to sip
                        </p>

                        <h3 class="font-noor-serif text-3xl text-[#f4f0e8]">
                            Drinks
                        </h3>

                    </div>


                    <div class="divide-y divide-white/10 border-y border-white/10">

                        <div class="flex items-center justify-between py-5">

                            <div>
                                <h4 class="text-sm text-[#f4f0e8]">
                                    Borhani
                                </h4>

                                <p class="mt-1 text-xs text-[#9d978e]">
                                    Cold · spiced · refreshing
                                </p>
                            </div>

                            <div class="flex items-center gap-5">
                                <span class="font-noor-mono text-sm text-[#f2d092]">
                                    ৳80
                                </span>

                                <button class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center text-[#d8a85a] hover:bg-[#d8a85a] hover:text-[#11110f] transition">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>

                        </div>


                        <div class="flex items-center justify-between py-5">

                            <div>
                                <h4 class="text-sm text-[#f4f0e8]">
                                    Lemon Mint
                                </h4>

                                <p class="mt-1 text-xs text-[#9d978e]">
                                    Fresh lemon · mint · ice
                                </p>
                            </div>

                            <div class="flex items-center gap-5">
                                <span class="font-noor-mono text-sm text-[#f2d092]">
                                    ৳90
                                </span>

                                <button class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center text-[#d8a85a] hover:bg-[#d8a85a] hover:text-[#11110f] transition">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>

                        </div>

                    </div>

                </section>

            </div>


            <!-- =====================
                 ORDER SUMMARY / INFO
            ====================== -->
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
                        class="mt-6 w-full rounded-full bg-[#d8a85a] py-3.5 text-sm font-semibold text-[#11110f] hover:bg-[#f2d092] transition"
                    >
                        View bag
                    </button>

                </div>


                <!-- Restaurant Info -->
                <div class="mt-5 rounded-[24px] border border-white/10 p-6">

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.2em] text-[#9d978e] mb-5">
                        Kitchen details
                    </p>

                    <div class="space-y-5">

                        <div class="flex gap-3">
                            <i data-lucide="clock-3" class="w-4 h-4 text-[#d8a85a] mt-0.5"></i>

                            <div>
                                <p class="text-xs text-[#f4f0e8]">
                                    Delivery hours
                                </p>

                                <p class="text-xs text-[#9d978e] mt-1">
                                    11:00 AM — 11:00 PM
                                </p>
                            </div>
                        </div>


                        <div class="flex gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#d8a85a] mt-0.5"></i>

                            <div>
                                <p class="text-xs text-[#f4f0e8]">
                                    Location
                                </p>

                                <p class="text-xs text-[#9d978e] mt-1">
                                    Pabna, Bangladesh
                                </p>
                            </div>
                        </div>


                        <div class="flex gap-3">
                            <i data-lucide="badge-check" class="w-4 h-4 text-[#d8a85a] mt-0.5"></i>

                            <div>
                                <p class="text-xs text-[#f4f0e8]">
                                    NOOR verified
                                </p>

                                <p class="text-xs text-[#9d978e] mt-1">
                                    Quality checked kitchen
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </aside>

        </div>

    </div>

</main>


<!-- =========================
     FOOTER
========================= -->


@endsection