@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">

    <div>

        <div class="font-monoNoor text-[10px] uppercase tracking-[0.25em] text-gold">
            Operations
        </div>

        <h2 class="font-serifNoor text-4xl md:text-5xl mt-2">
            Keep the kitchen moving.
        </h2>

        <p class="text-muted mt-2">
            Your restaurant's daily operations at a glance.
        </p>

    </div>


    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="stat-card">
            <div class="text-muted text-sm">New Orders</div>
            <div class="text-4xl font-semibold mt-3">18</div>
            <div class="text-gold2 text-xs mt-2">Need action</div>
        </div>

        <div class="stat-card">
            <div class="text-muted text-sm">Preparing</div>
            <div class="text-4xl font-semibold mt-3">12</div>
            <div class="text-gold2 text-xs mt-2">In kitchen</div>
        </div>

        <div class="stat-card">
            <div class="text-muted text-sm">Ready</div>
            <div class="text-4xl font-semibold mt-3">7</div>
            <div class="text-greenNoor text-xs mt-2">Awaiting rider</div>
        </div>

        <div class="stat-card">
            <div class="text-muted text-sm">Completed</div>
            <div class="text-4xl font-semibold mt-3">64</div>
            <div class="text-greenNoor text-xs mt-2">Today</div>
        </div>

    </div>


    <div class="dashboard-panel">

        <div class="panel-header">

            <div>

                <div class="font-serifNoor text-2xl">
                    Kitchen queue
                </div>

                <div class="text-xs text-muted mt-1">
                    Manage today's incoming orders
                </div>

            </div>

            <button class="px-4 py-2 rounded-xl bg-gold text-night text-sm font-semibold">
                View orders
            </button>

        </div>


        <div class="space-y-3">

            @foreach([
                ['#NO-8492','Kacchi Biryani × 2','New','5 min ago'],
                ['#NO-8491','Chicken Roast × 1','Preparing','8 min ago'],
                ['#NO-8490','Kacchi Biryani × 3','Ready','12 min ago'],
                ['#NO-8488','Fuchka Plate × 2','Preparing','15 min ago']
            ] as $order)

            <div class="p-4 rounded-xl border border-white/[0.05] bg-white/[0.02] flex items-center justify-between">

                <div>

                    <div class="font-semibold">
                        {{ $order[0] }}
                    </div>

                    <div class="text-xs text-muted mt-1">
                        {{ $order[1] }}
                    </div>

                </div>

                <div class="text-right">

                    <div class="text-xs text-gold2">
                        {{ $order[2] }}
                    </div>

                    <div class="text-xs text-muted mt-1">
                        {{ $order[3] }}
                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

@endsection