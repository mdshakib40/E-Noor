
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orders — NOOR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;1,500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: Manrope, Arial, sans-serif; }
        .font-display { font-family: "Playfair Display", Georgia, serif; }

        .pager nav svg {
            width: 1rem;
            height: 1rem;
            display: inline;
        }

        .pager nav a,
        .pager nav span[aria-current] span,
        .pager nav span[aria-disabled] span {
            background: #11120f !important;
            border-color: #ffffff1a !important;
            color: #d6d3d1 !important;
        }

        .pager nav p {
            color: #78716c !important;
            font-size: 11px;
        }

        .pager nav span[aria-current] span {
            color: #f1cf8b !important;
            border-color: #d7a85860 !important;
        }
    </style>
</head>

@php
    use Illuminate\Support\Str;

    $user = auth()->user();

    $initials = Str::of($user->name)->explode(' ')->filter()->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
        ->implode('');

    $isPaginated = $orders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $rows = $isPaginated ? $orders->getCollection() : collect($orders);

    $pick = function ($model, array $keys, $default = null) {
        if (! $model) return $default;

        foreach ($keys as $k) {
            if (isset($model->{$k})) {
                $v = $model->{$k};

                return $v instanceof \BackedEnum ? $v->value : $v;
            }
        }

        return $default;
    };

    $statusOf = fn ($o) => strtolower((string) $pick($o, ['status'], ''));

    $totalCount = $isPaginated ? $orders->total() : $rows->count();

    $pendingCount = $rows
        ->filter(fn ($o) => $statusOf($o) === 'pending')
        ->count();

    $deliveredCount = $rows
        ->filter(fn ($o) => $statusOf($o) === 'delivered')
        ->count();

    $partial = $isPaginated && $orders->hasPages();

    $statusOptions = $rows
        ->map($statusOf)
        ->filter()
        ->unique()
        ->values();

    $orderBadge = fn ($s) => match (strtolower((string) $s)) {
        'pending' => 'bg-[#d7a858]/15 text-[#f1cf8b]',
        'confirmed',
        'accepted',
        'preparing',
        'ready',
        'on_the_way',
        'out_for_delivery',
        'processing' => 'bg-[#a7c4e9]/10 text-[#a7c4e9]',
        'delivered',
        'completed' => 'bg-[#b8d985]/10 text-[#b8d985]',
        'cancelled',
        'canceled',
        'rejected',
        'failed' => 'bg-[#ed8b79]/10 text-[#ed8b79]',
        default => 'bg-white/10 text-stone-300',
    };

    $payBadge = fn ($s) => match (strtolower((string) $s)) {
        'paid',
        'completed',
        'success' => 'bg-[#b8d985]/10 text-[#b8d985]',
        'pending',
        'unpaid' => 'bg-[#d7a858]/15 text-[#f1cf8b]',
        'failed',
        'refunded',
        'cancelled' => 'bg-[#ed8b79]/10 text-[#ed8b79]',
        default => 'bg-white/10 text-stone-300',
    };

    $nav = [
        ['Dashboard', 'ti-layout-dashboard', route('resturent.dashboard'), false],
        ['Orders', 'ti-shopping-bag', route('resturent.orders.index'), true],
        ['Menu / Food Items', 'ti-tools-kitchen-2', null, false],
        ['Categories', 'ti-category', null, false],
        ['Staff', 'ti-users-group', null, false],
        ['Revenue / Reports', 'ti-chart-bar', null, false],
    ];

    $stats = [
        ['Total Orders', 'ti-shopping-bag', $totalCount],
        ['Pending', 'ti-clock-hour-4', $pendingCount],
        ['Delivered', 'ti-circle-check', $deliveredCount],
    ];

    $list = $rows->map(function ($o) use ($pick, $statusOf) {
        $customer = $o->relationLoaded('customer') ? $o->customer : null;

        $qty = $o->relationLoaded('items')
            ? $o->items->sum('quantity')
            : $pick($o, ['items_count']);

        $number = $pick($o, ['order_number', 'number', 'code'])
            ?? '#' . $o->getKey();

        $name = $pick($customer, ['name'])
            ?? $pick($o, ['customer_name']);

        $phone = $pick($o, ['customer_phone', 'phone'])
            ?? $pick($customer, ['phone', 'phone_number', 'mobile']);

        $total = $pick($o, [
            'total_amount',
            'total_price',
            'total',
            'grand_total'
        ]);

        return (object) [
            'id' => $o->getKey(),
            'number' => $number,
            'name' => $name,
            'phone' => $phone,
            'qty' => $qty,
            'total' => $total,
            'payment' => $pick($o, ['payment_status']),
            'status' => $statusOf($o),
            'date' => $o->created_at,
            'search' => strtolower($number . ' ' . $name . ' ' . $phone),
        ];
    });
@endphp

<body class="bg-[#0e0f0d] text-[#f6f2e9] antialiased">

<div class="flex min-h-screen">

    <div id="overlay" class="fixed inset-0 z-40 hidden bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/10 bg-[#11120f] px-4 py-6 transition-transform lg:static lg:translate-x-0">

        <a href="{{ route('resturent.dashboard') }}"
           class="mb-8 flex items-center gap-3 px-3 text-lg font-extrabold tracking-[0.23em]">

            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
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

                        <i class="ti {{ $icon }} text-base {{ $active ? 'text-[#d7a858]' : '' }}"></i>

                        {{ $label }}
                    </a>

                @else

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-bold text-stone-600"
                         title="Coming soon">

                        <i class="ti {{ $icon }} text-base"></i>

                        {{ $label }}

                        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] font-bold text-stone-500">
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

                    <p class="truncate text-xs font-bold">
                        {{ $user->name }}
                    </p>

                    <p class="text-[10px] text-stone-500">
                        Restaurant owner
                    </p>

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

    {{-- Main --}}
    <div class="min-w-0 flex-1">

        <header class="sticky top-0 z-30 flex h-[72px] items-center justify-between border-b border-white/10 bg-[#0e0f0d]/90 px-4 backdrop-blur sm:px-8">

            <div class="flex items-center gap-3">

                <button id="menuBtn"
                        class="grid h-9 w-9 place-items-center rounded-full border border-white/10 text-stone-300 lg:hidden"
                        aria-label="Open menu">

                    <i class="ti ti-menu-2 text-lg"></i>

                </button>

                <div class="hidden items-center gap-2 text-[11px] text-stone-500 sm:flex">

                    <span>NOOR</span>

                    <i class="ti ti-chevron-right text-xs"></i>

                    <span>Management</span>

                    <i class="ti ti-chevron-right text-xs"></i>

                    <b class="font-semibold text-[#f1cf8b]">
                        Orders
                    </b>

                </div>

            </div>

            <a href="{{ route('resturent.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b]">

                <i class="ti ti-arrow-left text-sm"></i>

                Back to Dashboard
            </a>

        </header>

        <main class="px-4 pb-12 pt-8 sm:px-8">

            {{-- Page header --}}
            <section class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">

                <div class="flex items-start gap-4">

                    <span class="grid h-14 w-14 flex-none place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">

                        <i class="ti ti-shopping-bag text-3xl"></i>

                    </span>

                    <div>

                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">
                            Management
                        </p>

                        <h1 class="font-display mt-2 text-4xl leading-tight tracking-tight sm:text-5xl">
                            Orders
                        </h1>

                        <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                            Orders placed at
                            <span class="font-semibold text-[#f1cf8b]">
                                {{ $restaurant->name }}
                            </span>.
                        </p>

                    </div>

                </div>

            </section>

            {{-- Flash messages --}}
            @if (session('success'))

                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4"
                     role="status">

                    <i class="ti ti-circle-check mt-0.5 text-lg text-[#b8d985]"></i>

                    <p class="text-xs font-semibold text-[#d3e8b0]">
                        {{ session('success') }}
                    </p>

                </div>

            @endif

            @if (session('error'))

                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4"
                     role="alert">

                    <i class="ti ti-alert-circle mt-0.5 text-lg text-[#ed8b79]"></i>

                    <p class="text-xs font-semibold text-[#f3b3a7]">
                        {{ session('error') }}
                    </p>

                </div>

            @endif

            {{-- Stats --}}
            <section class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">

                @foreach ($stats as [$label, $icon, $value])

                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-[#1b1d18] to-[#151613] p-4">

                        <div class="flex items-center justify-between text-[10px] uppercase tracking-wider text-stone-500">

                            {{ $label }}

                            <i class="ti {{ $icon }} text-base text-[#d7a858]"></i>

                        </div>

                        <p class="mt-3 text-3xl font-bold tracking-tight">
                            {{ number_format($value) }}
                        </p>

                        @if ($partial && $label !== 'Total Orders')

                            <p class="mt-1 text-[10px] text-stone-600">
                                On this page
                            </p>

                        @endif

                    </div>

                @endforeach

            </section>

            {{-- Orders --}}
            <section class="mt-4 overflow-hidden rounded-2xl border border-white/10 bg-[#161714]">

                <div class="flex flex-col gap-3 p-5 md:flex-row md:items-center md:justify-between">

                    <div>

                        <h2 class="text-xs font-extrabold">
                            All orders
                        </h2>

                        <p class="mt-1 text-[10px] text-stone-500">
                            Latest activity at your restaurant
                        </p>

                    </div>

                    @if ($list->isNotEmpty())

                        <div class="flex flex-col gap-2 sm:flex-row">

                            <div class="relative">

                                <i class="ti ti-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-stone-500"></i>

                                <input id="orderSearch"
                                       type="search"
                                       placeholder="Search order, name or phone…"
                                       class="w-full rounded-xl border border-white/10 bg-[#11120f] py-2.5 pl-9 pr-3 text-xs text-[#f6f2e9] placeholder-stone-600 outline-none transition focus:border-[#d7a858] focus:ring-4 focus:ring-[#d7a858]/10 sm:w-64">

                            </div>

                            <select id="statusFilter"
                                    class="rounded-xl border border-white/10 bg-[#11120f] px-3 py-2.5 text-xs text-stone-300 outline-none transition focus:border-[#d7a858]">

                                <option value="">
                                    All statuses
                                </option>

                                @foreach ($statusOptions as $s)

                                    <option value="{{ $s }}">
                                        {{ Str::headline($s) }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endif

                </div>

                @if ($list->isEmpty())

                    {{-- Empty state --}}
                    <div class="border-t border-white/10 px-5 py-16 text-center">

                        <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl border border-[#d7a858]/20 bg-[#d7a858]/10 text-[#d7a858]">

                            <i class="ti ti-shopping-bag-x text-3xl"></i>

                        </span>

                        <p class="mt-5 text-sm font-extrabold">
                            No orders yet
                        </p>

                        <p class="mx-auto mt-2 max-w-sm text-[11px] leading-6 text-stone-500">

                            When customers place orders at
                            {{ $restaurant->name }},
                            they will appear here.

                        </p>

                    </div>

                @else

                    {{-- Desktop table --}}
                    <div class="hidden overflow-x-auto md:block">

                        <table class="w-full min-w-[980px] text-left">

                            <thead>

                                <tr class="border-y border-white/10 text-[9px] uppercase tracking-[0.13em] text-stone-600">

                                    <th class="px-5 py-3">Order</th>
                                    <th class="px-5 py-3">Customer</th>
                                    <th class="px-5 py-3">Phone</th>
                                    <th class="px-5 py-3 text-center">Qty</th>
                                    <th class="px-5 py-3 text-right">Total</th>
                                    <th class="px-5 py-3">Payment</th>
                                    <th class="px-5 py-3">Status</th>
                                    <th class="px-5 py-3">Date</th>
                                    <th class="px-5 py-3 text-right">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($list as $r)

                                    <tr class="order-row border-b border-white/5 transition hover:bg-white/[0.02]"
                                        data-search="{{ $r->search }}"
                                        data-status="{{ $r->status }}">

                                        <td class="whitespace-nowrap px-5 py-3.5 text-[11px] font-extrabold text-stone-200">
                                            {{ $r->number }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5 text-[11px] text-stone-300">
                                            {{ $r->name ?: '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5 font-mono text-[10px] text-stone-400">
                                            {{ $r->phone ?: '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5 text-center text-[11px] text-stone-300">
                                            {{ $r->qty ?? '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5 text-right text-[11px] font-extrabold">
                                            {{ $r->total !== null ? '৳' . number_format((float) $r->total, 2) : '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5">

                                            @if ($r->payment)

                                                <span class="inline-flex rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $payBadge($r->payment) }}">
                                                    {{ Str::headline($r->payment) }}
                                                </span>

                                            @else

                                                <span class="text-[11px] text-stone-600">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5">

                                            @if ($r->status)

                                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $orderBadge($r->status) }}">

                                                    <i class="h-1.5 w-1.5 rounded-full bg-current"></i>

                                                    {{ Str::headline($r->status) }}

                                                </span>

                                            @else

                                                <span class="text-[11px] text-stone-600">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                        <td class="whitespace-nowrap px-5 py-3.5 text-[11px] text-stone-400">

                                            @if ($r->date)

                                                {{ $r->date->format('d M Y') }}

                                                <span class="mt-0.5 block font-mono text-[9px] text-stone-600">
                                                    {{ $r->date->format('h:i A') }}
                                                </span>

                                            @else

                                                —

                                            @endif

                                        </td>

                                        {{-- NEW: Details button --}}
                                        <td class="whitespace-nowrap px-5 py-3.5 text-right">

                                            <a href="{{ route('resturent.orders.show', $r->id) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg border border-[#d7a858]/20 bg-[#d7a858]/10 px-3 py-2 text-[9px] font-extrabold text-[#f1cf8b] transition hover:border-[#d7a858]/40 hover:bg-[#d7a858]/20">

                                                <i class="ti ti-eye text-sm"></i>

                                                Details

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    {{-- Mobile cards --}}
                    <div class="divide-y divide-white/5 border-t border-white/10 md:hidden">

                        @foreach ($list as $r)

                            <div class="order-row space-y-3 p-4"
                                 data-search="{{ $r->search }}"
                                 data-status="{{ $r->status }}">

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <p class="text-xs font-extrabold">
                                            {{ $r->number }}
                                        </p>

                                        <p class="mt-1 truncate text-[11px] text-stone-300">
                                            {{ $r->name ?: '—' }}
                                        </p>

                                        <p class="font-mono text-[10px] text-stone-500">
                                            {{ $r->phone ?: '—' }}
                                        </p>

                                    </div>

                                    <p class="flex-none text-sm font-extrabold">

                                        {{ $r->total !== null ? '৳' . number_format((float) $r->total, 2) : '—' }}

                                    </p>

                                </div>

                                <div class="flex flex-wrap items-center gap-2">

                                    @if ($r->status)

                                        <span class="rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $orderBadge($r->status) }}">
                                            {{ Str::headline($r->status) }}
                                        </span>

                                    @endif

                                    @if ($r->payment)

                                        <span class="rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $payBadge($r->payment) }}">
                                            {{ Str::headline($r->payment) }}
                                        </span>

                                    @endif

                                    <span class="text-[10px] text-stone-500">

                                        {{ $r->qty ?? '—' }}

                                        {{ ($r->qty ?? 0) == 1 ? 'item' : 'items' }}

                                    </span>

                                    <span class="ml-auto text-[10px] text-stone-500">

                                        {{ $r->date ? $r->date->format('d M Y, h:i A') : '—' }}

                                    </span>

                                </div>

                                {{-- NEW: Mobile Details button --}}
                                <a href="{{ route('resturent.orders.show', $r->id) }}"
                                   class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#d7a858]/20 bg-[#d7a858]/10 px-4 py-2.5 text-[10px] font-extrabold text-[#f1cf8b] transition hover:border-[#d7a858]/40 hover:bg-[#d7a858]/20">

                                    <i class="ti ti-eye text-sm"></i>

                                    View Order Details

                                </a>

                            </div>

                        @endforeach

                    </div>

                    {{-- No search results --}}
                    <div id="noResults"
                         class="hidden border-t border-white/10 px-5 py-14 text-center">

                        <i class="ti ti-search-off text-3xl text-stone-700"></i>

                        <p class="mt-3 text-xs font-bold text-stone-400">
                            No matching orders
                        </p>

                        <p class="mt-1 text-[11px] text-stone-600">
                            Try a different search or status.
                        </p>

                    </div>

                    @if ($partial)

                        <div class="pager border-t border-white/10 p-4">
                            {{ $orders->links() }}
                        </div>

                    @endif

                @endif

            </section>

        </main>

    </div>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    const toggleSidebar = (open) => {
        sidebar.classList.toggle('-translate-x-full', !open);
        overlay.classList.toggle('hidden', !open);
    };

    document.getElementById('menuBtn').addEventListener('click', () => {
        toggleSidebar(true);
    });

    overlay.addEventListener('click', () => {
        toggleSidebar(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleSidebar(false);
        }
    });

    // Client-side filter
    const search = document.getElementById('orderSearch');
    const filter = document.getElementById('statusFilter');
    const noResults = document.getElementById('noResults');

    const rows = [...document.querySelectorAll('.order-row')];

    const applyFilters = () => {

        const q = (search?.value || '').trim().toLowerCase();
        const s = filter?.value || '';

        let shown = 0;

        rows.forEach((row) => {

            const match =
                row.dataset.search.includes(q) &&
                (!s || row.dataset.status === s);

            row.style.display = match ? '' : 'none';

            if (match && row.offsetParent !== null) {
                shown++;
            }

        });

        noResults?.classList.toggle(
            'hidden',
            shown > 0 || rows.length === 0
        );
    };

    search?.addEventListener('input', applyFilters);
    filter?.addEventListener('change', applyFilters);
</script>

</body>
</html>

