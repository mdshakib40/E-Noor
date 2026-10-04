<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Dashboard — NOOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Manrope, Arial, sans-serif; }
        .font-display { font-family: "Playfair Display", Georgia, serif; }
    </style>
</head>

@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $firstName = Str::of($user->name)->explode(' ')->first();
    $initials = Str::of($user->name)->explode(' ')->filter()->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');

    // [label, icon, href, active] — only the dashboard route exists.
    $nav = [
        ['Dashboard', 'ti-layout-dashboard', route('resturent.staff.dashboard'), true],
        ['Orders', 'ti-shopping-bag', null, false],
        ['Menu / Stock', 'ti-tools-kitchen-2', null, false],
        ['My Profile', 'ti-user-circle', null, false],
    ];

    // Demo values only — not database data.
    $summary = [
        ['Pending Orders', 'ti-clock-hour-4', '5', 'text-[#f1cf8b]', 'bg-[#d7a858]/15'],
        ['Preparing', 'ti-flame', '3', 'text-[#a7c4e9]', 'bg-[#a7c4e9]/10'],
        ['Ready', 'ti-package', '2', 'text-[#b8d985]', 'bg-[#b8d985]/10'],
        ['Completed Today', 'ti-circle-check', '18', 'text-stone-300', 'bg-white/10'],
    ];

    $orders = [
        ['DEMO-001', 'Sample Customer A', '2 items', 'Pending', 'bg-[#d7a858]/15 text-[#f1cf8b]', 'Just now'],
        ['DEMO-002', 'Sample Customer B', '4 items', 'Preparing', 'bg-[#a7c4e9]/10 text-[#a7c4e9]', '6 min ago'],
        ['DEMO-003', 'Sample Customer C', '1 item', 'Ready', 'bg-[#b8d985]/10 text-[#b8d985]', '12 min ago'],
        ['DEMO-004', 'Sample Customer D', '3 items', 'Completed', 'bg-white/10 text-stone-300', '25 min ago'],
    ];

    $allowed = ['View and update order status', 'Check incoming and ready orders', 'Update limited menu and stock availability'];
    $blocked = ['Staff management', 'Restaurant settings', 'Revenue and sensitive reports'];
@endphp

<body class="bg-[#0e0f0d] text-[#f6f2e9] antialiased">
<div class="flex min-h-screen">

    {{-- Mobile overlay --}}
    <div id="overlay" class="fixed inset-0 z-40 hidden bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/10 bg-[#11120f] px-4 py-6 transition-transform lg:static lg:translate-x-0">
        <a href="{{ route('resturent.staff.dashboard') }}" class="mb-8 flex items-center gap-3 px-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </a>

        <p class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.17em] text-stone-500">Workspace</p>
        <nav class="flex-1 space-y-1">
            @foreach ($nav as [$label, $icon, $href, $active])
                @if ($href)
                    <a href="{{ $href }}" aria-current="page" class="flex items-center gap-3 rounded-xl border border-[#d7a858]/25 bg-gradient-to-r from-[#d7a858]/10 to-transparent px-3 py-2.5 text-xs font-bold text-[#f1cf8b]">
                        <i class="ti {{ $icon }} text-base text-[#d7a858]"></i>{{ $label }}
                    </a>
                @else
                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-bold text-stone-600" title="Coming soon">
                        <i class="ti {{ $icon }} text-base"></i>{{ $label }}
                        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] font-bold text-stone-500">Soon</span>
                    </div>
                @endif
            @endforeach
        </nav>

        <div class="mt-6 flex items-center gap-3 border-t border-white/10 px-2 pt-4">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-[#d7a858] text-[11px] font-black text-[#161714]">{{ $initials }}</span>
            <div class="min-w-0">
                <p class="truncate text-xs font-bold">{{ $user->name }}</p>
                <p class="text-[10px] text-stone-500">Restaurant staff</p>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="min-w-0 flex-1">
        {{-- Top header --}}
        <header class="sticky top-0 z-30 flex h-[72px] items-center justify-between border-b border-white/10 bg-[#0e0f0d]/90 px-4 backdrop-blur sm:px-8">
            <div class="flex items-center gap-3">
                <button id="menuBtn" class="grid h-9 w-9 place-items-center rounded-full border border-white/10 text-stone-300 lg:hidden" aria-label="Open menu">
                    <i class="ti ti-menu-2 text-lg"></i>
                </button>
                <div class="hidden items-center gap-2 text-[11px] text-stone-500 sm:flex">
                    <span>NOOR</span><i class="ti ti-chevron-right text-xs"></i>
                    <b class="font-semibold text-[#f1cf8b]">Staff</b><i class="ti ti-chevron-right text-xs"></i>
                    <span>Dashboard</span>
                </div>
            </div>

            {{-- Profile dropdown --}}
            <div class="relative">
                <button id="profileBtn" aria-expanded="false" aria-label="Open account menu"
                        class="flex items-center gap-2.5 rounded-full border border-[#d7a858]/25 bg-gradient-to-br from-[#d7a858]/10 to-white/[0.02] p-1 transition hover:border-[#d7a858] sm:pr-3">
                    <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-[#f1cf8b] to-[#d7a858] text-[11px] font-black text-[#161714]">{{ $initials }}</span>
                    <span class="hidden text-left leading-tight sm:block">
                        <strong class="block text-[11px]">{{ $user->name }}</strong>
                        <small class="block text-[9px] text-stone-400">Restaurant staff</small>
                    </span>
                    <i id="chev" class="ti ti-chevron-down hidden text-sm text-[#d7a858] transition sm:block"></i>
                </button>

                <div id="profileMenu" class="absolute right-0 top-14 hidden w-72 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-white/15 bg-[#1a1c17] shadow-2xl shadow-black/80">
                    <div class="flex items-center gap-3 border-b border-white/10 bg-gradient-to-r from-[#d7a858]/10 to-transparent p-4">
                        <span class="grid h-12 w-12 flex-none place-items-center rounded-full bg-gradient-to-br from-[#f1cf8b] to-[#d7a858] text-sm font-black text-[#161714] ring-4 ring-[#d7a858]/15">{{ $initials }}</span>
                        <div class="min-w-0">
                            <strong class="block truncate text-sm">{{ $user->name }}</strong>
                            <span class="block truncate font-mono text-[10px] text-stone-400">{{ $user->email }}</span>
                            <em class="mt-1.5 inline-flex rounded-full bg-[#d7a858]/15 px-2 py-0.5 text-[9px] font-extrabold not-italic text-[#f1cf8b]">restaurant_staff</em>
                        </div>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('resturent.staff.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold text-stone-300 transition hover:bg-white/5 hover:text-[#f1cf8b]">
                            <i class="ti ti-layout-dashboard text-base text-[#d7a858]"></i>Dashboard
                        </a>
                    </div>
                    <div class="border-t border-white/10 p-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-xs font-semibold text-[#ed8b79] transition hover:bg-[#ed8b79]/10">
                                <i class="ti ti-logout text-base"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 pb-12 pt-8 sm:px-8">

            {{-- Welcome --}}
            <section class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Staff overview</p>
                    <h1 class="font-display mt-3 text-4xl leading-none tracking-tight sm:text-5xl">Welcome, <em class="text-[#f1cf8b]">{{ $firstName }}.</em></h1>
                    <p class="mt-3 text-xs text-stone-500">Here's your workspace for today's service.</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-[#d7a858]/30 bg-[#d7a858]/10 px-4 py-2 text-[11px] font-extrabold text-[#f1cf8b]">
                    <i class="ti ti-id-badge-2 text-base text-[#d7a858]"></i>Restaurant Staff
                </span>
            </section>

            {{-- Demo notice --}}
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-[#a7c4e9]/25 bg-[#a7c4e9]/10 p-4" role="note">
                <i class="ti ti-info-circle mt-0.5 text-lg text-[#a7c4e9]"></i>
                <p class="text-[11px] leading-5 text-[#c5d8f0]">
                    <b class="font-extrabold">Demo content.</b> The numbers and orders below are placeholders for the UI preview. They are not live data and will be replaced once orders are connected.
                </p>
            </div>

            {{-- Summary cards --}}
            <section class="mt-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($summary as [$label, $icon, $value, $text, $bg])
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-[#1b1d18] to-[#151613] p-4">
                        <div class="flex items-center justify-between text-[10px] uppercase tracking-wider text-stone-500">
                            {{ $label }}
                            <span class="grid h-7 w-7 place-items-center rounded-lg {{ $bg }} {{ $text }}"><i class="ti {{ $icon }} text-base"></i></span>
                        </div>
                        <p class="mt-3 text-3xl font-bold tracking-tight">{{ $value }}</p>
                        <p class="mt-1 text-[10px] text-stone-600">Demo value</p>
                    </div>
                @endforeach
            </section>

            <div class="mt-4 grid gap-4 xl:grid-cols-[1.6fr_1fr]">
                {{-- Recent orders --}}
                <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#161714]">
                    <div class="flex items-center justify-between p-5">
                        <div>
                            <h2 class="text-xs font-extrabold">Recent orders</h2>
                            <p class="mt-1 text-[10px] text-stone-500">Sample orders for preview</p>
                        </div>
                        <span class="rounded-full bg-[#a7c4e9]/10 px-3 py-1 text-[9px] font-extrabold uppercase tracking-wider text-[#a7c4e9]">Demo data</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[520px] text-left">
                            <thead>
                                <tr class="border-y border-white/10 text-[9px] uppercase tracking-[0.13em] text-stone-600">
                                    <th class="px-5 py-3">Order</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Items</th><th class="px-5 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as [$id, $customer, $items, $status, $badge, $time])
                                    <tr class="border-b border-white/5 transition hover:bg-white/[0.02]">
                                        <td class="whitespace-nowrap px-5 py-3.5 text-[11px]">
                                            <b class="text-stone-200">#{{ $id }}</b>
                                            <span class="mt-1 block font-mono text-[9px] text-stone-600">{{ $time }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-[11px] text-stone-400">{{ $customer }}</td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-[11px] text-stone-400">{{ $items }}</td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $badge }}">
                                                <i class="h-1.5 w-1.5 rounded-full bg-current"></i>{{ $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <div class="space-y-4">
                    {{-- Quick actions --}}
                    <section class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                        <h2 class="text-xs font-extrabold">Quick actions</h2>
                        <div class="mt-4 grid grid-cols-2 gap-2.5">
                            @foreach ([['ti-shopping-bag', 'View Orders'], ['ti-tools-kitchen-2', 'Manage Menu']] as [$i, $t])
                                <button type="button" disabled aria-disabled="true" title="Coming soon"
                                        class="cursor-not-allowed rounded-xl border border-[#d7a858]/15 bg-[#d7a858]/5 p-3 text-left text-stone-500">
                                    <i class="ti {{ $i }} text-lg text-[#d7a858]/60"></i>
                                    <p class="mt-2 text-[11px] font-bold text-stone-300">{{ $t }}</p>
                                    <p class="text-[9px] text-stone-600">Coming soon</p>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    {{-- Your access --}}
                    <section class="rounded-2xl border border-[#d7a858]/20 bg-gradient-to-br from-[#24251e] to-[#181a16] p-5">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-shield-check text-lg text-[#d7a858]"></i>
                            <h2 class="text-xs font-extrabold">Your access</h2>
                        </div>
                        <p class="mt-3 text-[11px] leading-5 text-stone-500">
                            As restaurant staff, you can manage orders and limited menu and stock. Some areas are reserved for the restaurant owner.
                        </p>
                        <ul class="mt-4 space-y-2">
                            @foreach ($allowed as $item)
                                <li class="flex items-start gap-2 text-[11px] text-stone-300">
                                    <i class="ti ti-circle-check mt-0.5 text-sm text-[#b8d985]"></i>{{ $item }}
                                </li>
                            @endforeach
                        </ul>
                        <div class="my-4 border-t border-white/10"></div>
                        <p class="mb-2 text-[9px] font-extrabold uppercase tracking-wider text-stone-600">Not available to staff</p>
                        <ul class="space-y-2">
                            @foreach ($blocked as $item)
                                <li class="flex items-start gap-2 text-[11px] text-stone-500">
                                    <i class="ti ti-lock mt-0.5 text-sm text-[#ed8b79]"></i>{{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </div>
            </div>
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
    document.getElementById('menuBtn').addEventListener('click', () => toggleSidebar(true));
    overlay.addEventListener('click', () => toggleSidebar(false));

    const btn = document.getElementById('profileBtn');
    const menu = document.getElementById('profileMenu');
    const chev = document.getElementById('chev');
    const setMenu = (open) => {
        menu.classList.toggle('hidden', !open);
        chev.classList.toggle('rotate-180', open);
        btn.setAttribute('aria-expanded', open);
    };
    btn.addEventListener('click', (e) => { e.stopPropagation(); setMenu(menu.classList.contains('hidden')); });
    document.addEventListener('click', (e) => { if (!menu.contains(e.target)) setMenu(false); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { setMenu(false); toggleSidebar(false); } });
</script>
</body>
</html>