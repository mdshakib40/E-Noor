@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <div>

        <div class="font-monoNoor text-[10px] uppercase tracking-[0.25em] text-gold">
            Your NOOR
        </div>

        <h2 class="font-serifNoor text-4xl md:text-5xl mt-2">
            Welcome back, Shamim.
        </h2>

        <p class="text-muted mt-2">
            Good food is closer than you think.
        </p>

    </div>


    <!-- ACTIVE ORDER -->

    <div class="rounded-[24px] bg-cream text-night p-6 md:p-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">

            <div>

                <div class="font-monoNoor text-[10px] uppercase tracking-[0.2em] opacity-50">
                    Your active order
                </div>

                <div class="font-serifNoor text-3xl mt-2">
                    Dinner is on its way.
                </div>

                <div class="text-sm opacity-60 mt-2">
                    Order #NO-8491 · The Grill Room
                </div>

            </div>


            <button class="px-5 py-3 rounded-xl bg-night text-cream font-semibold">
                Track order
            </button>

        </div>


        <!-- PROGRESS -->

        <div class="grid grid-cols-4 gap-2 mt-8">

            <div>

                <div class="h-1 bg-night rounded-full"></div>

                <div class="text-xs mt-2">
                    Confirmed
                </div>

            </div>

            <div>

                <div class="h-1 bg-night rounded-full"></div>

                <div class="text-xs mt-2">
                    Preparing
                </div>

            </div>

            <div>

                <div class="h-1 bg-night/20 rounded-full"></div>

                <div class="text-xs mt-2 opacity-50">
                    On the way
                </div>

            </div>

            <div>

                <div class="h-1 bg-night/20 rounded-full"></div>

                <div class="text-xs mt-2 opacity-50">
                    Delivered
                </div>

            </div>

        </div>

    </div>


    <!-- STATS -->

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="stat-card">

            <div class="text-muted text-sm">
                Total Orders
            </div>

            <div class="text-3xl font-semibold mt-3">
                48
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Saved Places
            </div>

            <div class="text-3xl font-semibold mt-3">
                12
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                NOOR Points
            </div>

            <div class="text-3xl font-semibold mt-3">
                820
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Active Coupons
            </div>

            <div class="text-3xl font-semibold mt-3">
                04
            </div>

        </div>

    </div>


    <!-- RECENT ORDERS -->

    <div class="dashboard-panel">

        <div class="panel-header">

            <div>

                <div class="font-serifNoor text-2xl">
                    Recent orders
                </div>

                <div class="text-xs text-muted mt-1">
                    A little history
                </div>

            </div>

            <a href="#" class="text-gold2 text-sm">
                View all
            </a>

        </div>


        <div class="space-y-3">

            @foreach([
                ['The Grill Room','Chicken Steak + Fries','৳680','Delivered'],
                ['Kacchi Bhai','Kacchi Biryani × 2','৳920','Delivered'],
                ['Rong Kitchen','Chow Mein + Chicken','৳540','Delivered']
            ] as $order)

            <div class="flex items-center justify-between p-4 rounded-xl bg-white/[0.025] border border-white/[0.05]">

                <div class="flex items-center gap-4">

                    <div class="w-11 h-11 rounded-xl bg-gold/10 flex items-center justify-center">

                        <i data-lucide="utensils" class="text-gold w-4"></i>

                    </div>

                    <div>

                        <div class="font-semibold">
                            {{ $order[0] }}
                        </div>

                        <div class="text-xs text-muted mt-1">
                            {{ $order[1] }}
                        </div>

                    </div>

                </div>


                <div class="text-right">

                    <div>
                        {{ $order[2] }}
                    </div>

                    <div class="text-xs text-greenNoor mt-1">
                        {{ $order[3] }}
                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>


    <!-- FAVORITES -->

    <div class="dashboard-panel">

        <div class="panel-header">

            <div>

                <div class="font-serifNoor text-2xl">
                    Saved for later
                </div>

                <div class="text-xs text-muted mt-1">
                    Places you keep coming back to
                </div>

            </div>

        </div>


        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @foreach([
                ['Kacchi Bhai','Kacchi · Bangla','4.9'],
                ['The Grill Room','Charcoal · Grill','4.8'],
                ['Rong Kitchen','Chinese · Asian','4.7']
            ] as $place)

            <div class="p-5 rounded-2xl bg-white/[0.025] border border-white/[0.05]">

                <div class="flex justify-between">

                    <div class="w-11 h-11 rounded-xl bg-gold/10 flex items-center justify-center">
                        <i data-lucide="heart" class="text-gold fill-current"></i>
                    </div>

                    <div class="text-gold2">
                        ★ {{ $place[2] }}
                    </div>

                </div>

                <div class="font-semibold mt-5">
                    {{ $place[0] }}
                </div>

                <div class="text-xs text-muted mt-1">
                    {{ $place[1] }}
                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

@endsection