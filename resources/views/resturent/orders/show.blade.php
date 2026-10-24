
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Order Details — NOOR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;1,500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Manrope, Arial, sans-serif;
        }

        .font-display {
            font-family: "Playfair Display", Georgia, serif;
        }

        select option {
            background: #161714;
            color: #f6f2e9;
        }
    </style>
</head>

@php
    use Illuminate\Support\Str;

    $user = auth()->user();

    $initials = Str::of($user->name)
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $money = fn ($value) => $value !== null && $value !== ''
        ? '৳' . number_format((float) $value, 2)
        : '—';

    $customer = $order->customer;
    $items = $order->items ?? collect();

    $number = $order->order_number ?: '#' . $order->getKey();
    $name = $order->customer_name ?: ($customer->name ?? '—');
    $phone = $order->customer_phone ?: '—';
    $address = $order->delivery_address ?: '—';

    $status = strtolower((string) $order->status);
    $payment = $order->payment_status;
    $method = $order->payment_method;
    $date = $order->created_at;

    $subtotal = $order->subtotal;
    $deliveryFee = $order->delivery_fee;
    $discount = $order->discount;
    $grand = $order->total;

    $lines = $items->map(function ($item) {
        $quantity = $item->quantity ?? 0;
        $price = $item->unit_price ?? 0;

        return (object) [
            'name' => $item->product_name ?? 'Item',
            'qty' => $quantity,
            'unit' => $price,
            'total' => $item->total ?? ($quantity * $price),
        ];
    });

    $orderBadge = fn ($value) => match (strtolower((string) $value)) {
        'pending' => 'bg-[#d7a858]/15 text-[#f1cf8b]',
        'accepted', 'preparing', 'ready_for_pickup',
        'picked_up', 'out_for_delivery' => 'bg-[#a7c4e9]/10 text-[#a7c4e9]',
        'delivered', 'completed' => 'bg-[#b8d985]/10 text-[#b8d985]',
        'cancelled', 'canceled', 'rejected', 'failed' => 'bg-[#ed8b79]/10 text-[#ed8b79]',
        default => 'bg-white/10 text-stone-300',
    };

    $payBadge = fn ($value) => match (strtolower((string) $value)) {
        'paid', 'completed', 'success' => 'bg-[#b8d985]/10 text-[#b8d985]',
        'pending', 'unpaid' => 'bg-[#d7a858]/15 text-[#f1cf8b]',
        'failed', 'refunded', 'cancelled' => 'bg-[#ed8b79]/10 text-[#ed8b79]',
        default => 'bg-white/10 text-stone-300',
    };

    $statusOptions = match ($status) {
    'pending', 'confirmed' => [
        'accepted' => 'Accept Order',
        'rejected' => 'Reject Order',
    ],
    'accepted' => [
        'preparing' => 'Start Preparing',
        'rejected' => 'Reject Order',
    ],
    'preparing' => [
        'ready_for_pickup' => 'Ready for Pickup',
    ],
    default => [],
};

    $nav = [
        ['Dashboard', 'ti-layout-dashboard', route('resturent.dashboard'), false],
        ['Orders', 'ti-shopping-bag', route('resturent.orders.index'), true],
        ['Menu / Food Items', 'ti-tools-kitchen-2', null, false],
        ['Categories', 'ti-category', null, false],
        ['Staff', 'ti-users-group', null, false],
        ['Revenue / Reports', 'ti-chart-bar', null, false],
    ];
@endphp

<body class="bg-[#0e0f0d] text-[#f6f2e9] antialiased">

<div class="flex min-h-screen">

    {{-- Mobile overlay --}}
    <div id="overlay"
         class="fixed inset-0 z-40 hidden bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/10 bg-[#11120f] px-4 py-6 transition-transform lg:static lg:translate-x-0">

        <a href="{{ route('resturent.dashboard') }}"
           class="mb-8 flex items-center gap-3 px-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858]"></span>
            </span>
            NOOR
        </a>

        <p class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.17em] text-stone-500">
            Workspace
        </p>

        <nav class="flex-1 space-y-1 overflow-y-auto">
            @foreach ($nav as [$label, $icon, $href, $active])
                @if ($href)
                    <a href="{{ $href }}"
                       @if ($active) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl border px-3 py-2.5 text-xs font-bold transition
                       {{ $active
                            ? 'border-[#d7a858]/25 bg-gradient-to-r from-[#d7a858]/10 to-transparent text-[#f1cf8b]'
                            : 'border-transparent text-stone-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="ti {{ $icon }} text-base"></i>
                        {{ $label }}
                    </a>
                @else
                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-bold text-stone-600"
                         title="Coming soon">
                        <i class="ti {{ $icon }} text-base"></i>
                        {{ $label }}
                        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px]">
                            Soon
                        </span>
                    </div>
                @endif
            @endforeach
        </nav>

        <div class="mt-6 border-t border-white/10 px-2 pt-4">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-[#d7a858] text-[11px] font-black text-[#161714]">
                    {{ $initials }}
                </span>

                <div class="min-w-0">
                    <p class="truncate text-xs font-bold">{{ $user->name }}</p>
                    <p class="text-[10px] text-stone-500">Restaurant owner</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf

                <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-xs font-semibold text-[#ed8b79] transition hover:bg-[#ed8b79]/10">
                    <i class="ti ti-logout text-base"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="min-w-0 flex-1">

        <header class="sticky top-0 z-30 flex h-[72px] items-center justify-between border-b border-white/10 bg-[#0e0f0d]/90 px-4 backdrop-blur sm:px-8">

            <div class="flex items-center gap-3">
                <button id="menuBtn"
                        type="button"
                        class="grid h-9 w-9 place-items-center rounded-full border border-white/10 text-stone-300 lg:hidden"
                        aria-label="Open menu">
                    <i class="ti ti-menu-2 text-lg"></i>
                </button>

                <div class="hidden items-center gap-2 text-[11px] text-stone-500 sm:flex">
                    <span>NOOR</span>
                    <i class="ti ti-chevron-right text-xs"></i>
                    <span>Orders</span>
                    <i class="ti ti-chevron-right text-xs"></i>
                    <span class="font-semibold text-[#f1cf8b]">{{ $number }}</span>
                </div>
            </div>

            <a href="{{ route('resturent.orders.index') }}"
               class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b]">
                <i class="ti ti-arrow-left"></i>
                Back to Orders
            </a>
        </header>

        <main class="px-4 pb-12 pt-8 sm:px-8">

            {{-- Page heading --}}
            <section class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">

                <div class="flex items-start gap-4">
                    <span class="grid h-14 w-14 flex-none place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">
                        <i class="ti ti-receipt-2 text-3xl"></i>
                    </span>

                    <div class="min-w-0">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">
                            Order details
                        </p>

                        <h1 class="font-display mt-2 break-words text-4xl leading-tight sm:text-5xl">
                            Order <em class="text-[#f1cf8b]">{{ $number }}</em>
                        </h1>

                        <p class="mt-3 text-xs text-stone-500">
                            Placed {{ $date ? $date->format('d M Y, h:i A') : '—' }}
                        </p>
                    </div>
                </div>

                @if ($status)
                    <span class="inline-flex w-fit items-center gap-2 rounded-full px-4 py-2 text-[11px] font-extrabold {{ $orderBadge($status) }}">
                        <span class="h-2 w-2 rounded-full bg-current"></span>
                        {{ Str::headline($status) }}
                    </span>
                @endif
            </section>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4"
                     role="status">
                    <i class="ti ti-circle-check mt-0.5 text-lg text-[#b8d985]"></i>
                    <p class="text-xs font-semibold text-[#d3e8b0]">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4"
                     role="alert">
                    <i class="ti ti-alert-circle mt-0.5 text-lg text-[#ed8b79]"></i>
                    <p class="text-xs font-semibold text-[#f3b3a7]">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->has('status'))
                <div class="mt-8 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4 text-xs font-semibold text-[#f3b3a7]"
                     role="alert">
                    {{ $errors->first('status') }}
                </div>
            @endif

            <div class="mt-8 grid gap-4 xl:grid-cols-[1.6fr_1fr]">

                {{-- LEFT: Order items --}}
                <div class="space-y-4">

                    <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#161714]">

                        <div class="flex items-center justify-between p-5">
                            <div>
                                <h2 class="text-xs font-extrabold">Order items</h2>
                                <p class="mt-1 text-[10px] text-stone-500">
                                    {{ $lines->sum('qty') }}
                                    {{ $lines->sum('qty') == 1 ? 'item' : 'items' }} in this order
                                </p>
                            </div>

                            <i class="ti ti-tools-kitchen-2 text-base text-[#d7a858]"></i>
                        </div>

                        @if ($lines->isEmpty())
                            <div class="border-t border-white/10 px-5 py-14 text-center">
                                <i class="ti ti-shopping-bag-x text-3xl text-stone-700"></i>
                                <p class="mt-3 text-xs font-bold text-stone-400">No items found</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[500px] text-left">
                                    <thead>
                                        <tr class="border-y border-white/10 text-[9px] uppercase tracking-[0.13em] text-stone-600">
                                            <th class="px-5 py-3">Product</th>
                                            <th class="px-5 py-3 text-center">Qty</th>
                                            <th class="px-5 py-3 text-right">Unit price</th>
                                            <th class="px-5 py-3 text-right">Item total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($lines as $line)
                                            <tr class="border-b border-white/5 transition hover:bg-white/[0.02]">
                                                <td class="px-5 py-3.5 text-[11px] font-bold text-stone-200">
                                                    {{ $line->name }}
                                                </td>

                                                <td class="px-5 py-3.5 text-center text-[11px] text-stone-300">
                                                    {{ $line->qty }}
                                                </td>

                                                <td class="whitespace-nowrap px-5 py-3.5 text-right text-[11px] text-stone-400">
                                                    {{ $money($line->unit) }}
                                                </td>

                                                <td class="whitespace-nowrap px-5 py-3.5 text-right text-[11px] font-extrabold">
                                                    {{ $money($line->total) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- Order totals --}}
                        <div class="border-t border-white/10 bg-[#11120f] p-5">
                            <dl class="ml-auto max-w-xs space-y-2.5 text-[11px]">

                                <div class="flex justify-between gap-4">
                                    <dt class="text-stone-500">Subtotal</dt>
                                    <dd class="font-semibold text-stone-300">{{ $money($subtotal) }}</dd>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <dt class="text-stone-500">Delivery fee</dt>
                                    <dd class="font-semibold text-stone-300">{{ $money($deliveryFee) }}</dd>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <dt class="text-stone-500">Discount</dt>
                                    <dd class="font-semibold {{ $discount ? 'text-[#b8d985]' : 'text-stone-300' }}">
                                        {{ $discount ? '−' . $money($discount) : $money($discount) }}
                                    </dd>
                                </div>

                                <div class="flex items-center justify-between gap-4 border-t border-white/10 pt-3">
                                    <dt class="text-xs font-extrabold">Grand total</dt>
                                    <dd class="text-xl font-extrabold text-[#f1cf8b]">{{ $money($grand) }}</dd>
                                </div>

                            </dl>
                        </div>
                    </section>
                </div>

                {{-- RIGHT: Customer and status --}}
                <div class="space-y-4">

                    {{-- Customer details --}}
                    <section class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                        <h2 class="text-xs font-extrabold">Customer</h2>

                        <dl class="mt-4 space-y-4 text-[11px]">

                            @foreach ([
                                ['ti-user', 'Name', $name],
                                ['ti-phone', 'Phone', $phone],
                                ['ti-map-pin', 'Delivery address', $address],
                            ] as [$icon, $label, $value])
                                <div class="flex gap-3">
                                    <i class="ti {{ $icon }} mt-0.5 text-[#d7a858]"></i>

                                    <div class="min-w-0">
                                        <dt class="text-[9px] uppercase tracking-wider text-stone-600">
                                            {{ $label }}
                                        </dt>
                                        <dd class="mt-1 break-words text-stone-300">
                                            {{ $value ?: '—' }}
                                        </dd>
                                    </div>
                                </div>
                            @endforeach

                        </dl>
                    </section>

                    {{-- Payment and status --}}
                    <section class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                        <h2 class="text-xs font-extrabold">Payment & status</h2>

                        <dl class="mt-4 space-y-4 text-[11px]">

                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-stone-500">Payment method</dt>
                                <dd class="text-right font-semibold text-stone-300">
                                    {{ $method ? Str::headline($method) : '—' }}
                                </dd>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-stone-500">Payment status</dt>
                                <dd>
                                    @if ($payment)
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $payBadge($payment) }}">
                                            {{ Str::headline($payment) }}
                                        </span>
                                    @else
                                        <span class="text-stone-600">—</span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-stone-500">Order status</dt>
                                <dd>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $orderBadge($status) }}">
                                        {{ $status ? Str::headline($status) : '—' }}
                                    </span>
                                </dd>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-stone-500">Order date</dt>
                                <dd class="text-right font-semibold text-stone-300">
                                    {{ $date ? $date->format('d M Y, h:i A') : '—' }}
                                </dd>
                            </div>

                        </dl>
                    </section>

                    {{-- Functional status update --}}
                    <section class="rounded-2xl border border-[#d7a858]/20 bg-gradient-to-br from-[#24251e] to-[#181a16] p-5">

                        <div class="flex items-center gap-2">
                            <i class="ti ti-refresh text-lg text-[#d7a858]"></i>
                            <h2 class="text-xs font-extrabold">Manage order</h2>
                        </div>

                        <p class="mt-3 text-[11px] leading-5 text-stone-500">
                            Update this order as your kitchen processes it.
                        </p>

                        @if (count($statusOptions) > 0)


<form
    method="POST"
    action="{{ route('resturent.orders.updateStatus', ['order' => $order->id]) }}"
    class="mt-5 space-y-4"
>
    @csrf
    @method('PATCH')

    <div>
        <label
            for="newStatus"
            class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-stone-400"
        >
            Choose next status
        </label>

        <select
            id="newStatus"
            name="status"
            required
            class="w-full rounded-xl border border-white/10 bg-[#11120f] px-4 py-3 text-xs text-[#f6f2e9] outline-none transition focus:border-[#d7a858]/60"
        >
            <option value="" disabled selected>
                Select an action
            </option>

            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}">
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <button
        type="submit"
        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#d7a858] px-4 py-3 text-xs font-extrabold text-[#161714] transition hover:bg-[#f1cf8b]"
    >
        <i class="ti ti-refresh text-base"></i>
        Update Order Status
    </button>
</form>


                        @elseif (in_array($status, ['ready_for_pickup', 'picked_up', 'out_for_delivery'], true))

                            <div class="mt-5 rounded-xl border border-[#a7c4e9]/15 bg-[#a7c4e9]/5 p-4">
                                <i class="ti ti-truck-delivery text-xl text-[#a7c4e9]"></i>
                                <p class="mt-2 text-xs font-bold text-[#a7c4e9]">
                                    Waiting for delivery workflow
                                </p>
                                <p class="mt-1 text-[11px] leading-5 text-stone-500">
                                    This stage will be managed by the rider workflow.
                                </p>
                            </div>

                        @elseif (in_array($status, ['delivered', 'completed'], true))

                            <div class="mt-5 rounded-xl border border-[#b8d985]/20 bg-[#b8d985]/5 p-4">
                                <i class="ti ti-circle-check text-xl text-[#b8d985]"></i>
                                <p class="mt-2 text-xs font-bold text-[#b8d985]">
                                    Order completed
                                </p>
                                <p class="mt-1 text-[11px] text-stone-500">
                                    This order has reached its final stage.
                                </p>
                            </div>

                        @elseif (in_array($status, ['rejected', 'cancelled', 'canceled'], true))

                            <div class="mt-5 rounded-xl border border-[#ed8b79]/20 bg-[#ed8b79]/5 p-4">
                                <i class="ti ti-circle-x text-xl text-[#ed8b79]"></i>
                                <p class="mt-2 text-xs font-bold text-[#ed8b79]">
                                    Order closed
                                </p>
                                <p class="mt-1 text-[11px] text-stone-500">
                                    This order can no longer be updated from this page.
                                </p>
                            </div>

                        @else

                            <div class="mt-5 rounded-xl border border-white/10 bg-[#11120f] p-4">
                                <p class="text-xs font-bold text-stone-300">
                                    No available actions
                                </p>
                                <p class="mt-1 text-[11px] leading-5 text-stone-500">
                                    This status does not have an available owner action yet.
                                </p>
                            </div>

                        @endif

                    </section>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('resturent.orders.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-stone-300 transition hover:border-white/25 hover:text-white">
                    <i class="ti ti-arrow-left text-base"></i>
                    Back to Orders
                </a>
            </div>

        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const menuBtn = document.getElementById('menuBtn');

    function toggleSidebar(open) {
        sidebar.classList.toggle('-translate-x-full', !open);
        overlay.classList.toggle('hidden', !open);
    }

    menuBtn?.addEventListener('click', () => toggleSidebar(true));
    overlay?.addEventListener('click', () => toggleSidebar(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            toggleSidebar(false);
        }
    });
</script>

</body>
</html>
