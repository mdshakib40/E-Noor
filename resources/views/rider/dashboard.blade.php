@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

        <div>

            <div class="font-monoNoor text-[10px] uppercase tracking-[0.25em] text-gold">
                Rider
            </div>

            <h2 class="font-serifNoor text-4xl md:text-5xl mt-2">
                Let's get it there.
            </h2>

            <p class="text-muted mt-2">
                Your deliveries and earnings for today.
            </p>

        </div>


        <button class="px-5 py-3 rounded-xl bg-greenNoor text-night font-semibold flex items-center gap-2">

            <span class="w-2 h-2 rounded-full bg-night"></span>

            Online

        </button>

    </div>


    <!-- STATS -->

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="stat-card">

            <div class="text-muted text-sm">
                Active Delivery
            </div>

            <div class="text-4xl font-semibold mt-3">
                01
            </div>

            <div class="text-gold2 text-xs mt-2">
                On the way
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Today's Deliveries
            </div>

            <div class="text-4xl font-semibold mt-3">
                18
            </div>

            <div class="text-greenNoor text-xs mt-2">
                Completed
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Today's Earnings
            </div>

            <div class="text-3xl font-semibold mt-3">
                ৳1,280
            </div>

            <div class="text-greenNoor text-xs mt-2">
                +৳180 tips
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Rating
            </div>

            <div class="text-4xl font-semibold mt-3">
                4.9
            </div>

            <div class="text-gold2 text-xs mt-2">
                ★ Excellent
            </div>

        </div>

    </div>


    <!-- ACTIVE DELIVERY -->

    <div class="dashboard-panel">

        <div class="panel-header">

            <div>

                <div class="font-serifNoor text-2xl">
                    Active delivery
                </div>

                <div class="text-xs text-muted mt-1">
                    Order #NO-8491
                </div>

            </div>

            <span class="px-3 py-1.5 rounded-full bg-gold/10 text-gold2 text-xs">
                On the way
            </span>

        </div>


        <div class="grid md:grid-cols-2 gap-5">

            <div class="space-y-4">

                <div class="p-5 rounded-2xl bg-white/[0.025] border border-white/[0.05]">

                    <div class="text-xs text-muted">
                        PICKUP
                    </div>

                    <div class="font-semibold mt-2">
                        The Grill Room
                    </div>

                    <div class="text-sm text-muted mt-1">
                        24 Station Road
                    </div>

                </div>


                <div class="flex justify-center">

                    <i data-lucide="arrow-down" class="text-gold"></i>

                </div>


                <div class="p-5 rounded-2xl bg-white/[0.025] border border-white/[0.05]">

                    <div class="text-xs text-muted">
                        DELIVERY
                    </div>

                    <div class="font-semibold mt-2">
                        Rahim Ahmed
                    </div>

                    <div class="text-sm text-muted mt-1">
                        17 College Road
                    </div>

                </div>

            </div>


            <div class="rounded-2xl bg-white/[0.025] border border-white/[0.05] min-h-[300px] flex items-center justify-center">

                <div class="text-center">

                    <i data-lucide="map" class="w-10 h-10 text-gold mx-auto"></i>

                    <div class="font-serifNoor text-xl mt-4">
                        Route map
                    </div>

                    <div class="text-sm text-muted mt-1">
                        Map integration will live here
                    </div>

                </div>

            </div>

        </div>


        <div class="grid grid-cols-2 gap-3 mt-5">

            <button class="py-3 rounded-xl border border-white/[0.07]">
                Call customer
            </button>

            <button class="py-3 rounded-xl bg-gold text-night font-semibold">
                Mark as delivered
            </button>

        </div>

    </div>

</div>

@endsection