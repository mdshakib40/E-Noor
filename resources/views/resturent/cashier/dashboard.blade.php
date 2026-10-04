@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <div>

        <div class="font-monoNoor text-[10px] uppercase tracking-[0.25em] text-gold">
            Counter
        </div>

        <h2 class="font-serifNoor text-4xl md:text-5xl mt-2">
            Orders, simply handled.
        </h2>

        <p class="text-muted mt-2">
            Keep the counter moving without missing a beat.
        </p>

    </div>


    <!-- QUICK STATS -->

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="stat-card">

            <div class="text-muted text-sm">
                New
            </div>

            <div class="text-4xl font-semibold mt-3">
                8
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Preparing
            </div>

            <div class="text-4xl font-semibold mt-3">
                6
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Ready
            </div>

            <div class="text-4xl font-semibold mt-3">
                4
            </div>

        </div>


        <div class="stat-card">

            <div class="text-muted text-sm">
                Today's sales
            </div>

            <div class="text-3xl font-semibold mt-3">
                ৳18,420
            </div>

        </div>

    </div>


    <!-- POS -->

    <div class="grid lg:grid-cols-[1.5fr_1fr] gap-5">

        <div class="dashboard-panel">

            <div class="panel-header">

                <div class="font-serifNoor text-2xl">
                    Today's orders
                </div>

                <button class="px-4 py-2 rounded-xl bg-gold text-night font-semibold text-sm">
                    + Counter Order
                </button>

            </div>


            <div class="space-y-3">

                @foreach([
                    ['#8492','Rahim Ahmed','৳920','Preparing'],
                    ['#8491','Walk-in Customer','৳540','Ready'],
                    ['#8490','Sadia Khan','৳760','New'],
                    ['#8489','Arif Hasan','৳420','Delivered']
                ] as $order)

                <div class="p-4 rounded-xl bg-white/[0.025] border border-white/[0.05] flex items-center justify-between">

                    <div>

                        <div class="font-semibold">
                            {{ $order[0] }}
                        </div>

                        <div class="text-xs text-muted mt-1">
                            {{ $order[1] }}
                        </div>

                    </div>

                    <div class="text-right">

                        <div>
                            {{ $order[2] }}
                        </div>

                        <div class="text-xs text-gold2 mt-1">
                            {{ $order[3] }}
                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>


        <!-- QUICK ACTIONS -->

        <div class="dashboard-panel">

            <div class="font-serifNoor text-2xl mb-5">
                Quick actions
            </div>


            <div class="space-y-3">

                <button class="quick-action">
                    <i data-lucide="plus-circle"></i>
                    Create counter order
                </button>

                <button class="quick-action">
                    <i data-lucide="search"></i>
                    Find order
                </button>

                <button class="quick-action">
                    <i data-lucide="user-plus"></i>
                    Add customer
                </button>

                <button class="quick-action">
                    <i data-lucide="receipt"></i>
                    Today's receipt
                </button>

            </div>

        </div>

    </div>

</div>


<style>
    .quick-action {
        width:100%;
        display:flex;
        align-items:center;
        gap:12px;
        padding:15px;
        border-radius:14px;
        background:rgba(255,255,255,.025);
        border:1px solid rgba(255,255,255,.05);
        color:#f4f0e8;
        text-align:left;
    }

    .quick-action:hover {
        background:rgba(255,255,255,.05);
    }

    .quick-action svg {
        width:18px;
        color:#d8a85a;
    }
</style>

@endsection