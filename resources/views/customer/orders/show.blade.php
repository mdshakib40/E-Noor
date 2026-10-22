
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Order Details — NOOR</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        gold: {
                            300: '#F1D995',
                            400: '#E8C875',
                            500: '#D8B45C',
                        }
                    }
                }
            }
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #11110F;
            color: #F5F1E8;
        }

        .panel {
            background: linear-gradient(145deg, #1B1B17, #161613);
            border: 1px solid rgba(255,255,255,.075);
        }

        .muted {
            color: #929087;
        }

        .gold-button {
            background: #E8C875;
            color: #17150F;
            transition: .2s ease;
        }

        .gold-button:hover {
            background: #F1D995;
            transform: translateY(-1px);
        }

        .step-line {
            height: 2px;
            background: rgba(255,255,255,.09);
        }

        .step-line.done {
            background: #D8B45C;
        }
    </style>
</head>

@php
    $status = strtolower((string) ($order->status ?? 'pending'));

    $statusLabels = [
        'pending' => 'Order placed',
        'confirmed' => 'Confirmed by restaurant',
        'accepted' => 'Accepted by restaurant',
        'preparing' => 'Preparing your food',
        'ready' => 'Ready for pickup',
        'ready_for_pickup' => 'Ready for pickup',
        'picked_up' => 'Picked up by rider',
        'out_for_delivery' => 'Out for delivery',
        'delivered' => 'Delivered',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'canceled' => 'Cancelled',
        'rejected' => 'Rejected',
    ];

    $statusStyles = [
        'pending' => 'background:rgba(234,179,8,.10);color:#FACC15',
        'confirmed' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'accepted' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'preparing' => 'background:rgba(251,146,60,.10);color:#FDBA74',
        'ready' => 'background:rgba(192,132,252,.10);color:#D8B4FE',
        'ready_for_pickup' => 'background:rgba(192,132,252,.10);color:#D8B4FE',
        'picked_up' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'out_for_delivery' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'delivered' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'completed' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'cancelled' => 'background:rgba(248,113,113,.10);color:#FCA5A5',
        'canceled' => 'background:rgba(248,113,113,.10);color:#FCA5A5',
        'rejected' => 'background:rgba(248,113,113,.10);color:#FCA5A5',
    ];

    $steps = [
        ['label' => 'Placed', 'icon' => 'clipboard-check'],
        ['label' => 'Accepted', 'icon' => 'check-circle-2'],
        ['label' => 'Preparing', 'icon' => 'chef-hat'],
        ['label' => 'On the way', 'icon' => 'bike'],
        ['label' => 'Delivered', 'icon' => 'house'],
    ];

    $progressSteps = [
        'pending' => 1,
        'confirmed' => 2,
        'accepted' => 2,
        'preparing' => 3,
        'ready' => 3,
        'ready_for_pickup' => 3,
        'picked_up' => 4,
        'out_for_delivery' => 4,
        'delivered' => 5,
        'completed' => 5,
    ];

    $progress = $progressSteps[$status] ?? 0;

    $isCancelled = in_array(
        $status,
        ['cancelled', 'canceled', 'rejected'],
        true
    );

    $isComplete = in_array(
        $status,
        ['delivered', 'completed'],
        true
    );

    $paymentStatus = strtolower(
        (string) ($order->payment_status ?? 'pending')
    );

    $paymentStyles = [
        'paid' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'completed' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'success' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'pending' => 'background:rgba(234,179,8,.10);color:#FACC15',
        'unpaid' => 'background:rgba(234,179,8,.10);color:#FACC15',
        'failed' => 'background:rgba(248,113,113,.10);color:#FCA5A5',
        'refunded' => 'background:rgba(96,165,250,.10);color:#93C5FD',
    ];

    $items = $order->items ?? collect();

    $orderNumber = $order->order_number ?: ('#' . $order->id);

    $customerName = $order->customer_name
        ?: ($order->customer?->name ?? '—');

    $customerPhone = $order->customer_phone ?: '—';

    $deliveryAddress = $order->delivery_address ?: 'Address not provided';

    $deliveryZone = $order->delivery_zone;

    $notes = $order->notes;

    $subtotal = (float) ($order->subtotal ?? 0);
    $deliveryFee = (float) ($order->delivery_fee ?? 0);
    $discount = (float) ($order->discount ?? 0);
    $total = (float) ($order->total ?? 0);

    $paymentMethod = $order->payment_method ?: 'cash_on_delivery';

    $restaurantName = $order->restaurant?->name ?? 'NOOR Restaurant';
@endphp

<body class="min-h-screen font-sans antialiased">

{{-- Header --}}
<header class="sticky top-0 z-30 border-b border-white/[.07] bg-[#11110F]/95 backdrop-blur-xl">
    <div class="mx-auto flex max-w-[1250px] items-center justify-between gap-4 px-4 py-4 sm:px-7">

        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-xl border border-gold-400/25 bg-gold-400/[.08]">
                <i data-lucide="utensils" class="h-5 w-5 text-gold-300"></i>
            </div>

            <div>
                <div class="font-display text-xl tracking-[.16em] text-gold-300">NOOR</div>
                <div class="text-[9px] uppercase tracking-[.2em] text-stone-500">Food, considered</div>
            </div>
        </a>

        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('customer.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2.5 text-xs font-bold text-stone-300 transition hover:border-gold-400/30 hover:text-gold-300 sm:px-4">
                <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('home') }}"
               aria-label="Visit website"
               class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 text-stone-300 transition hover:border-gold-400/30 hover:text-gold-300">
                <i data-lucide="external-link" class="h-4 w-4"></i>
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-[1250px] px-4 py-7 sm:px-7 sm:py-10">

    {{-- Breadcrumb --}}
    <div class="mb-7 flex flex-wrap items-center gap-2 text-xs text-stone-500">
        <a href="{{ route('customer.dashboard') }}" class="transition hover:text-gold-300">
            Dashboard
        </a>
        <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
        <span class="text-stone-300">Order details</span>
    </div>

    {{-- Page heading --}}
    <section class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-gold-400/20 bg-gold-400/[.06] px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[.17em] text-gold-300">
                <i data-lucide="receipt-text" class="h-3.5 w-3.5"></i>
                Order information
            </div>

            <h1 class="font-display text-3xl leading-tight text-stone-100 sm:text-4xl">
                Your order,
                <span class="italic text-gold-300">beautifully tracked.</span>
            </h1>

            <p class="mt-3 text-sm text-stone-500">
                Everything about this order, in one place.
            </p>
        </div>

        <div class="rounded-2xl border border-white/[.07] bg-white/[.025] px-5 py-4">
            <div class="text-[10px] font-extrabold uppercase tracking-[.15em] text-stone-500">
                Order number
            </div>

            <div class="mt-2 flex items-center gap-2 text-base font-extrabold text-gold-300">
                <i data-lucide="hash" class="h-4 w-4"></i>
                {{ $orderNumber }}
            </div>

            <div class="mt-1 text-[10px] text-stone-500">
                Placed {{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}
            </div>
        </div>
    </section>

    {{-- Status banner --}}
    <section class="panel relative mb-7 overflow-hidden rounded-[24px] p-5 sm:p-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full border border-gold-400/[.08]"></div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-4">
                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl border border-gold-400/15 bg-gold-400/[.08]">
                    <i data-lucide="{{ $isCancelled ? 'circle-x' : ($isComplete ? 'check-circle-2' : 'bike') }}"
                       class="h-6 w-6 text-gold-300"></i>
                </div>

                <div>
                    <div class="text-[10px] font-extrabold uppercase tracking-[.17em] text-stone-500">
                        Current order status
                    </div>

                    <h2 class="mt-2 font-display text-2xl text-stone-100">
                        {{ $statusLabels[$status] ?? ucwords(str_replace('_', ' ', $status)) }}
                    </h2>

                    <p class="mt-2 max-w-xl text-xs leading-6 text-stone-500">
                        @if ($isCancelled)
                            This order is {{ $statusLabels[$status] ?? $status }}.
                            Please contact the restaurant if you need more information.
                        @elseif ($isComplete)
                            Your order has been completed. We hope you enjoyed every bite.
                        @elseif ($status === 'ready_for_pickup' || $status === 'ready')
                            Your food is ready. It will move to the delivery stage when the rider picks it up.
                        @elseif (in_array($status, ['picked_up', 'out_for_delivery'], true))
                            Your order is on its way. The delivery stage is now in progress.
                        @elseif ($status === 'pending')
                            Your order has been placed and is waiting for the restaurant to respond.
                        @else
                            Your order progress is shown below. Updates depend on the restaurant and delivery team.
                        @endif
                    </p>
                </div>
            </div>

            <div class="shrink-0">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs font-extrabold"
                      style="{{ $statusStyles[$status] ?? 'background:rgba(255,255,255,.07);color:#D6D3D1' }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ $statusLabels[$status] ?? ucwords(str_replace('_', ' ', $status)) }}
                </span>
            </div>
        </div>

        {{-- Progress tracker --}}
        @if (!$isCancelled)
            <div class="relative mt-9 grid grid-cols-5 gap-1 sm:gap-3">
                @foreach ($steps as $index => $step)
                    @php
                        $stepNumber = $index + 1;
                        $stepDone = $stepNumber <= $progress;
                        $lineDone = $stepNumber < $progress;
                    @endphp

                    <div class="relative flex flex-col items-center text-center">
                        @if (!$loop->last)
                            <div class="step-line absolute left-[60%] right-[-40%] top-[18px] {{ $lineDone ? 'done' : '' }}"></div>
                        @endif

                        <div class="relative z-10 grid h-9 w-9 place-items-center rounded-full border {{ $stepDone ? 'border-gold-400 bg-gold-400 text-[#17150F]' : 'border-white/10 bg-[#22221D] text-stone-600' }}">
                            <i data-lucide="{{ $step['icon'] }}" class="h-4 w-4"></i>
                        </div>

                        <span class="mt-3 text-[9px] font-bold sm:text-[11px] {{ $stepDone ? 'text-gold-300' : 'text-stone-600' }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        
{{-- Live Rider Tracking --}}
@if (true)
    <section class="mt-8 overflow-hidden rounded-[24px] border border-gold-400/15 bg-[#171610] p-5 sm:p-7">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-gold-300">
                    Live delivery
                </p>
                <h3 class="mt-2 font-display text-2xl text-stone-100">
                    Track your rider
                </h3>
                <p id="rider-tracking-message" class="mt-2 text-xs text-stone-400">
                    Connecting to rider location…
                </p>
            </div>

            <span class="rounded-full border border-green-400/20 bg-green-400/[.07] px-3 py-2 text-[10px] font-bold text-green-300">
                <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-green-300"></span>
                Live tracking
            </span>
        </div>

        <div id="rider-map" class="mt-5 h-[320px] overflow-hidden rounded-2xl border border-white/10 bg-[#22221d] sm:h-[400px]"></div>

        <p id="rider-last-updated" class="mt-3 text-[10px] text-stone-500">
            Waiting for the first location update…
        </p>
    </section>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @endpush

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mapElement = document.getElementById('rider-map');
                const message = document.getElementById('rider-tracking-message');
                const updated = document.getElementById('rider-last-updated');

                if (!mapElement || typeof L === 'undefined') {
                    message.textContent = 'Map could not load. Check your internet connection.';
                    return;
                }

                const map = L.map(mapElement).setView([24.06, 89.33], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);

                let marker = null;

                async function refreshLocation() {
                    try {
                        const response = await fetch(
                            @json(route('customer.orders.tracking', $order->getKey())),
                            {
                                headers: { 'Accept': 'application/json' },
                                cache: 'no-store'
                            }
                        );

                        if (!response.ok) {
                            message.textContent = 'Unable to retrieve rider location.';
                            return;
                        }

                        const data = await response.json();

                        if (!data.available || !data.location) {
                            message.textContent = data.message || 'Waiting for rider location…';
                            return;
                        }

                        const lat = Number(data.location.latitude);
                        const lng = Number(data.location.longitude);

                        if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                            message.textContent = 'Received an invalid rider location.';
                            return;
                        }

                        const position = [lat, lng];

                        const riderIcon = L.divIcon({
    className: '',
    html: `
        <div style="
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E8B45A;
            border: 3px solid #fff;
            border-radius: 50%;
            box-shadow: 0 3px 12px rgba(0,0,0,0.25);
            color: #1C1410;
            font-size: 22px;
        ">
            <i class="fa-solid fa-motorcycle"></i>
        </div>
    `,
    iconSize: [44, 44],
    iconAnchor: [22, 22],
    popupAnchor: [0, -24]
});

if (!marker) {
    marker = L.marker(position, {
        icon: riderIcon
    }).addTo(map).bindPopup('Your delivery rider');

    map.setView(position, 16);
} else {
    marker.setLatLng(position);
}

                        message.textContent = 'Rider location received successfully.';
                        updated.textContent = data.location.recorded_at
                            ? 'Last location update: ' + new Date(data.location.recorded_at).toLocaleTimeString()
                            : 'Location updated just now';

                    } catch (error) {
                        console.error('Tracking error:', error);
                        message.textContent = 'Could not refresh location. Retrying…';
                    }
                }

                refreshLocation();
                setInterval(refreshLocation, 10000);
            });
        </script>

@endif


        <div class="relative mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-white/[.06] pt-4">
            <p class="text-[10px] text-stone-500">
                Last updated: {{ $order->updated_at?->format('d M Y, h:i A') ?? '—' }}
            </p>

            <button type="button"
                    onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2 text-[10px] font-bold text-stone-300 transition hover:border-gold-400/30 hover:text-gold-300">
                <i data-lucide="refresh-cw" class="h-3.5 w-3.5"></i>
                Refresh status
            </button>
        </div>
    </section>

    <div class="grid items-start gap-7 lg:grid-cols-[1fr_350px]">

        {{-- Left column --}}
        <div class="space-y-7">

            {{-- Food items --}}
            <section class="panel overflow-hidden rounded-[24px]">
                <div class="flex items-center justify-between gap-4 border-b border-white/[.07] px-5 py-5 sm:px-7">
                    <div>
                        <h2 class="font-display text-2xl text-stone-100">Your selection</h2>
                        <p class="mt-1 text-xs text-stone-500">
                            {{ $items->count() }}
                            {{ $items->count() === 1 ? 'item' : 'items' }} in this order
                        </p>
                    </div>

                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gold-400/[.08]">
                        <i data-lucide="utensils" class="h-5 w-5 text-gold-300"></i>
                    </div>
                </div>

                @if ($items->isNotEmpty())
                    <div class="divide-y divide-white/[.06] px-5 sm:px-7">
                        @foreach ($items as $item)
                            <div class="flex items-center gap-4 py-5">
                                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl border border-white/[.07] bg-white/[.025]">
                                    <i data-lucide="utensils-crossed" class="h-5 w-5 text-gold-300"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="break-words text-sm font-extrabold text-stone-100">
                                        {{ $item->product_name ?: 'Food item' }}
                                    </h3>

                                    <p class="mt-1 text-xs text-stone-500">
                                        ৳{{ number_format((float) $item->unit_price, 2) }} each
                                        <span class="mx-1 text-stone-700">·</span>
                                        Qty {{ $item->quantity }}
                                    </p>
                                </div>

                                <div class="shrink-0 text-right">
                                    <div class="text-sm font-extrabold text-stone-100">
                                        ৳{{ number_format((float) ($item->total ?? ((float) $item->unit_price * (int) $item->quantity)), 2) }}
                                    </div>
                                    <div class="mt-1 text-[10px] text-stone-600">Item total</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-10 text-center">
                        <i data-lucide="shopping-bag" class="mx-auto h-8 w-8 text-stone-600"></i>
                        <p class="mt-3 text-sm text-stone-400">No item details were found for this order.</p>
                    </div>
                @endif
            </section>

            {{-- Delivery details --}}
            <section class="panel rounded-[24px] p-5 sm:p-7">
                <div class="mb-6 flex items-center gap-3">
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gold-400/[.08]">
                        <i data-lucide="map-pin" class="h-5 w-5 text-gold-300"></i>
                    </div>

                    <div>
                        <h2 class="font-display text-2xl text-stone-100">Delivery details</h2>
                        <p class="mt-1 text-xs text-stone-500">Where your order is going</p>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
                        <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[.14em] text-stone-500">
                            <i data-lucide="user-round" class="h-3.5 w-3.5 text-gold-300"></i>
                            Recipient
                        </div>

                        <div class="mt-3 break-words text-sm font-bold text-stone-100">
                            {{ $customerName }}
                        </div>

                        <div class="mt-1 break-words text-xs text-stone-500">
                            {{ $customerPhone }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
                        <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[.14em] text-stone-500">
                            <i data-lucide="map-pinned" class="h-3.5 w-3.5 text-gold-300"></i>
                            Delivery address
                        </div>

                        <div class="mt-3 break-words text-sm leading-6 text-stone-200">
                            {{ $deliveryAddress }}
                        </div>

                        @if ($deliveryZone)
                            <div class="mt-2 text-xs text-stone-500">
                                Zone: {{ $deliveryZone }}
                            </div>
                        @endif
                    </div>
                </div>

                @if ($notes)
                    <div class="mt-4 rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
                        <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[.14em] text-stone-500">
                            <i data-lucide="message-square-text" class="h-3.5 w-3.5 text-gold-300"></i>
                            Delivery notes
                        </div>

                        <p class="mt-2 break-words text-sm leading-6 text-stone-300">
                            {{ $notes }}
                        </p>
                    </div>
                @endif
            </section>

            {{-- Restaurant --}}
            <section class="panel rounded-[24px] p-5 sm:p-7">
                <div class="flex items-center gap-4">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl border border-gold-400/15 bg-gold-400/[.07]">
                        <i data-lucide="store" class="h-5 w-5 text-gold-300"></i>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="text-[10px] font-extrabold uppercase tracking-[.14em] text-stone-500">
                            Prepared by
                        </div>

                        <h2 class="mt-1 break-words font-display text-xl text-stone-100">
                            {{ $restaurantName }}
                        </h2>
                    </div>

                    <span class="shrink-0 rounded-full border border-white/[.08] px-3 py-1.5 text-[10px] font-bold text-stone-400">
                        Restaurant
                    </span>
                </div>
            </section>
        </div>

        {{-- Right column --}}
        <aside class="space-y-7">

            {{-- Bill summary --}}
            <section class="panel rounded-[24px] p-5 sm:p-6">
                <div class="mb-6 flex items-center gap-3">
                    <div class="grid h-10 w-10 place-items-center rounded-xl bg-gold-400/[.08]">
                        <i data-lucide="receipt-text" class="h-5 w-5 text-gold-300"></i>
                    </div>

                    <div>
                        <h2 class="font-display text-2xl text-stone-100">Bill summary</h2>
                        <p class="mt-1 text-xs text-stone-500">Your order breakdown</p>
                    </div>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-stone-500">Subtotal</span>
                        <span class="font-bold text-stone-200">৳{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-stone-500">Delivery fee</span>
                        <span class="font-bold text-stone-200">৳{{ number_format($deliveryFee, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-stone-500">Discount</span>
                        <span class="font-bold {{ $discount > 0 ? 'text-emerald-300' : 'text-stone-200' }}">
                            − ৳{{ number_format($discount, 2) }}
                        </span>
                    </div>

                    <div class="border-t border-dashed border-white/[.12] pt-4">
                        <div class="flex items-center justify-between gap-4">
                            <span class="font-bold text-stone-100">Total amount</span>
                            <span class="text-xl font-extrabold text-gold-300">৳{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
                    <div class="flex items-center gap-3">
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-white/[.04]">
                            <i data-lucide="{{ in_array($paymentMethod, ['online', 'card', 'bkash', 'nagad'], true) ? 'credit-card' : 'banknote' }}"
                               class="h-5 w-5 text-gold-300"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] uppercase tracking-[.14em] text-stone-500">
                                Payment method
                            </div>

                            <div class="mt-1 break-words text-xs font-extrabold text-stone-200">
                                {{ ucwords(str_replace('_', ' ', $paymentMethod)) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 border-t border-white/[.06] pt-4">
                        <span class="text-xs text-stone-500">Payment status</span>

                        <span class="rounded-full px-3 py-1.5 text-[10px] font-extrabold uppercase"
                              style="{{ $paymentStyles[$paymentStatus] ?? 'background:rgba(255,255,255,.06);color:#D6D3D1' }}">
                            {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- Quick help --}}
            <section class="relative overflow-hidden rounded-[24px] border border-gold-400/15 bg-gradient-to-br from-[#282419] to-[#191915] p-5 sm:p-6">
                <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full border border-gold-400/[.10]"></div>

                <div class="relative">
                    <div class="grid h-11 w-11 place-items-center rounded-2xl border border-gold-400/15 bg-gold-400/[.08]">
                        <i data-lucide="heart-handshake" class="h-5 w-5 text-gold-300"></i>
                    </div>

                    <h3 class="mt-5 font-display text-2xl text-stone-100">
                        We're here to help.
                    </h3>

                    <p class="mt-2 text-xs leading-6 text-stone-400">
                        Keep your order number handy if you need help with this order.
                    </p>

                    <div class="mt-5 rounded-xl border border-white/[.08] bg-black/10 p-3">
                        <div class="text-[10px] uppercase tracking-[.14em] text-stone-500">
                            Your reference
                        </div>

                        <div class="mt-1 break-words text-sm font-extrabold text-gold-300">
                            {{ $orderNumber }}
                        </div>
                    </div>

                    <a href="{{ route('customer.dashboard') }}"
                       class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl border border-gold-400/20 px-4 py-3 text-xs font-extrabold text-gold-300 transition hover:bg-gold-400/10">
                        Back to dashboard
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>
            </section>
        </aside>
    </div>

    {{-- Footer --}}
    <footer class="mt-10 flex flex-col gap-2 border-t border-white/[.06] pt-6 text-[10px] text-stone-600 sm:flex-row sm:items-center sm:justify-between">
        <span>© {{ date('Y') }} NOOR. Food, considered.</span>

        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 transition hover:text-gold-300">
            Back to NOOR website
            <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
        </a>
    </footer>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        } else {
            console.error('NOOR: Lucide icons could not be loaded.');
        }
    });
</script>

</body>
</html>
