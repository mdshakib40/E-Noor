@extends('layouts.rider')

@section('title', 'Active Deliveries')
@section('page', 'Active Deliveries')

@section('content')
<section class="mb-8">
    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#f1cf8b]">
        Delivery queue
    </p>

    <h1 class="serif mt-3 text-4xl tracking-tight sm:text-5xl">
        Active deliveries
    </h1>

    <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
        Track accepted orders and manage each delivery step.
    </p>
</section>

@if ($orders->isEmpty())
    @include('rider.partials.empty', [
        'icon' => 'bike',
        'title' => 'No active deliveries',
        'text' => 'Orders you accept will appear here.'
    ])
@else
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($orders as $o)
            @include('rider.partials.active-card', [
                'o' => $o,
                'i' => $loop->index
            ])
        @endforeach
    </div>

    @if (method_exists($orders, 'hasPages') && $orders->hasPages())
        <div class="pager mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deliveries = [];

    @foreach ($orders as $o)
        @if (in_array($o['status'], ['picked_up', 'out_for_delivery'], true))
            deliveries.push({
                url: @json(route('rider.orders.location', $o['id']))
            });
        @endif
    @endforeach

    console.log('GPS tracking deliveries:', deliveries.length);

    if (!deliveries.length) {
        console.info('No deliveries currently need live GPS tracking.');
        return;
    }

    if (!navigator.geolocation) {
        console.warn('Geolocation is not supported by this browser.');
        return;
    }

    let sending = false;

    function sendLocation() {
        if (sending) return;

        sending = true;

        navigator.geolocation.getCurrentPosition(
            async function (position) {
                try {
                    for (const delivery of deliveries) {
                        const response = await fetch(delivery.url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token())
                            },
                            body: JSON.stringify({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                                heading: position.coords.heading,
                                speed: position.coords.speed
                            })
                        });

                        const result = await response.text();

                        console.log(
                            'GPS response:',
                            response.status,
                            result
                        );
                    }
                } catch (error) {
                    console.error('GPS request failed:', error);
                } finally {
                    sending = false;
                }
            },
            function (error) {
                console.error(
                    'GPS error:',
                    error.code,
                    error.message
                );

                sending = false;
            },
            {
                enableHighAccuracy: true,
                maximumAge: 5000,
                timeout: 15000
            }
        );
    }

    sendLocation();
    setInterval(sendLocation, 10000);
});
</script>
@endsection