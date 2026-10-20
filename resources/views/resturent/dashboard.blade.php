<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restaurant Dashboard — NOOR</title>
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
    $roleValue = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
    $isOwner = $roleValue === 'restaurant_owner';

    // Only the authenticated user's own restaurants (data isolation).
    $restaurant = $user->restaurants->first();
    $hasRestaurant = (bool) $restaurant;

    $initials = Str::of($user->name)->explode(' ')->filter()->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
    $roleLabel = Str::of($roleValue)->replace('_', ' ')->title();
    $firstName = Str::of($user->name)->explode(' ')->first();

    $statusKey = strtolower((string) ($restaurant->status ?? ''));
    $statusClass = match (true) {
        in_array($statusKey, ['active', 'approved', 'open']) => 'bg-[#b8d985]/10 text-[#b8d985]',
        in_array($statusKey, ['pending', 'review']) => 'bg-[#d7a858]/15 text-[#f1cf8b]',
        in_array($statusKey, ['suspended', 'rejected', 'closed', 'inactive']) => 'bg-[#ed8b79]/10 text-[#ed8b79]',
        default => 'bg-white/10 text-stone-300',
    };

    $profileUrl = \Illuminate\Support\Facades\Route::has('profile.edit') ? route('profile.edit') : null;
    $imageUrl = $hasRestaurant && $restaurant->image
    ? asset('storage/' . ltrim($restaurant->image, '/'))
    : null;

    // Only routes that exist are linked. Everything else is a disabled "Soon" item.
    $nav = [
        'Overview' => [
            ['Dashboard', 'ti-layout-dashboard', route('resturent.dashboard'), true],
        ],
        'Restaurant' => [
           ['My Restaurant', 'ti-building-store', $hasRestaurant ? route('resturent.restaurant.edit') : route('resturent.restaurant.create'), false],
            ['Restaurant Settings', 'ti-settings-2', null, false],
        ],
        'Management' => [
            ['Orders', 'ti-shopping-bag', route('resturent.orders.index'), false],
           ['Menu / Food Items', 'ti-tools-kitchen-2', route('resturent.menu.index'), true],
            ['Categories', 'ti-category', null, false],
           ['Staff', 'ti-users-group', route('resturent.staff.index'), false],
        ],
        'Business' => [
            ['Offers', 'ti-discount-2', null, false],
            ['Revenue / Reports', 'ti-chart-bar', null, false],
        ],
        'Account' => [
            ['My Profile', 'ti-user-circle', $profileUrl, false],
        ],
    ];

    $stats = [
        ['Total Orders', 'ti-shopping-bag', '0'],
        ['Pending Orders', 'ti-clock-hour-4', '0'],
        ['Completed Orders', 'ti-circle-check', '0'],
        ['Total Revenue', 'ti-wallet', '৳ 0'],
        ['Menu Items', 'ti-tools-kitchen-2', '0'],
    ];
@endphp

<body class="bg-[#0e0f0d] text-[#f6f2e9] antialiased">
<div class="flex min-h-screen">

    {{-- Mobile overlay --}}
    <div id="overlay" class="fixed inset-0 z-40 hidden bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/10 bg-[#11120f] px-4 py-6 transition-transform lg:static lg:translate-x-0">
        <a href="{{ route('resturent.dashboard') }}" class="mb-8 flex items-center gap-3 px-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </a>

        <nav class="flex-1 space-y-6 overflow-y-auto">
            @foreach ($nav as $group => $items)
                <div>
                    <p class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.17em] text-stone-500">{{ $group }}</p>
                    <div class="space-y-1">
                        @foreach ($items as [$label, $icon, $href, $active])
                            @if ($href)
                                <a href="{{ $href }}" class="flex items-center gap-3 rounded-xl border px-3 py-2.5 text-xs font-bold transition {{ $active ? 'border-[#d7a858]/25 bg-gradient-to-r from-[#d7a858]/10 to-transparent text-[#f1cf8b]' : 'border-transparent text-stone-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="ti {{ $icon }} text-base {{ $active ? 'text-[#d7a858]' : '' }}"></i>{{ $label }}
                                </a>
                            @else
                                <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-bold text-stone-600" title="Coming soon">
                                    <i class="ti {{ $icon }} text-base"></i>{{ $label }}
                                    <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] font-bold text-stone-500">Soon</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="mt-6 flex items-center gap-3 border-t border-white/10 px-2 pt-4">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-[#d7a858] text-[11px] font-black text-[#161714]">{{ $initials }}</span>
            <div class="min-w-0">
                <p class="truncate text-xs font-bold">{{ $user->name }}</p>
                <p class="text-[10px] text-stone-500">{{ $roleLabel }}</p>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="min-w-0 flex-1">
        {{-- Top navbar --}}
        <header class="sticky top-0 z-30 flex h-[72px] items-center justify-between border-b border-white/10 bg-[#0e0f0d]/90 px-4 backdrop-blur sm:px-8">
            <div class="flex items-center gap-3">
                <button id="menuBtn" class="grid h-9 w-9 place-items-center rounded-full border border-white/10 text-stone-300 lg:hidden" aria-label="Open menu">
                    <i class="ti ti-menu-2 text-lg"></i>
                </button>
                <div class="hidden items-center gap-2 text-[11px] text-stone-500 sm:flex">
                    <span>NOOR</span><i class="ti ti-chevron-right text-xs"></i>
                    <b class="font-semibold text-[#f1cf8b]">Restaurant</b><i class="ti ti-chevron-right text-xs"></i>
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
                        <small class="block text-[9px] text-stone-400">{{ $roleLabel }}</small>
                    </span>
                    <i id="chev" class="ti ti-chevron-down hidden text-sm text-[#d7a858] transition sm:block"></i>
                </button>

                <div id="profileMenu" class="absolute right-0 top-14 hidden w-72 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-white/15 bg-[#1a1c17] shadow-2xl shadow-black/80">
                    <div class="flex items-center gap-3 border-b border-white/10 bg-gradient-to-r from-[#d7a858]/10 to-transparent p-4">
                        <span class="grid h-12 w-12 flex-none place-items-center rounded-full bg-gradient-to-br from-[#f1cf8b] to-[#d7a858] text-sm font-black text-[#161714] ring-4 ring-[#d7a858]/15">{{ $initials }}</span>
                        <div class="min-w-0">
                            <strong class="block truncate text-sm">{{ $user->name }}</strong>
                            <span class="block truncate font-mono text-[10px] text-stone-400">{{ $user->email }}</span>
                            <em class="mt-1.5 inline-flex rounded-full bg-[#d7a858]/15 px-2 py-0.5 text-[9px] font-extrabold not-italic text-[#f1cf8b]">{{ $roleValue }}</em>
                        </div>
                    </div>

                    <div class="p-2">
                        @if ($profileUrl)
                            <a href="{{ $profileUrl }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold text-stone-300 transition hover:bg-white/5 hover:text-[#f1cf8b]">
                                <i class="ti ti-user-circle text-base text-[#d7a858]"></i>My Profile
                            </a>
                        @else
                            <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold text-stone-600">
                                <i class="ti ti-user-circle text-base"></i>My Profile
                                <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] text-stone-500">Soon</span>
                            </div>
                        @endif
                        <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold text-stone-300 transition hover:bg-white/5 hover:text-[#f1cf8b]">
                            <i class="ti ti-layout-dashboard text-base text-[#d7a858]"></i>Visit Website
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

            @if (! $hasRestaurant && $isOwner)
                {{-- ===== Onboarding: no restaurant yet ===== --}}
                <section class="relative overflow-hidden rounded-3xl border border-[#d7a858]/25 bg-gradient-to-br from-[#24251e] via-[#181a16] to-[#11120f] p-8 sm:p-14">
                    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full border border-[#d7a858]/15"></div>
                    <div class="pointer-events-none absolute -bottom-32 -right-10 h-80 w-80 rounded-full border border-[#d7a858]/10"></div>
                    <div class="relative max-w-xl">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">
                            <i class="ti ti-building-store text-3xl"></i>
                        </span>
                        <p class="mt-6 text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Welcome, {{ $firstName }}</p>
                        <h1 class="font-display mt-3 text-4xl leading-tight tracking-tight sm:text-5xl">Create Your Restaurant</h1>
                        <p class="mt-4 text-sm leading-7 text-stone-400">
                            Start by creating your restaurant. Once your restaurant is set up, you can manage your menu, orders, staff and business operations from this dashboard.
                        </p>
                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <a href="{{ route('resturent.restaurant.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#d7a858] px-6 py-3.5 text-sm font-extrabold text-[#161714] shadow-lg shadow-[#d7a858]/20 transition hover:-translate-y-0.5 hover:bg-[#f1cf8b]">
                                <i class="ti ti-plus text-lg"></i>Create Restaurant
                            </a>
                            <span class="text-[11px] text-stone-500">Takes about two minutes.</span>
                        </div>
                    </div>
                </section>

                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    @foreach ([['ti-tools-kitchen-2', 'Build your menu', 'Add food items and categories.'], ['ti-shopping-bag', 'Receive orders', 'Track and fulfil customer orders.'], ['ti-users-group', 'Invite staff', 'Give your team access.']] as [$i, $t, $d])
                        <div class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                            <i class="ti {{ $i }} text-xl text-[#d7a858]"></i>
                            <p class="mt-3 text-xs font-extrabold">{{ $t }}</p>
                            <p class="mt-1 text-[11px] text-stone-500">{{ $d }}</p>
                        </div>
                    @endforeach
                </div>

            @elseif (! $hasRestaurant)
                {{-- Staff account without an assigned restaurant --}}
                <section class="rounded-3xl border border-white/10 bg-[#161714] p-10 text-center">
                    <i class="ti ti-building-store text-4xl text-stone-600"></i>
                    <h1 class="font-display mt-4 text-3xl">No restaurant assigned</h1>
                    <p class="mx-auto mt-3 max-w-md text-sm text-stone-500">Your account isn't linked to a restaurant yet. Ask the restaurant owner to add you.</p>
                </section>

            @else
                {{-- ===== Normal owner dashboard ===== --}}
                <section class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Restaurant overview</p>
                        <h1 class="font-display mt-3 text-4xl leading-none tracking-tight sm:text-5xl">Welcome back, <em class="text-[#f1cf8b]">{{ $firstName }}.</em></h1>
                        <p class="mt-3 text-xs text-stone-500">Here's how <span class="text-stone-300">{{ $restaurant->name }}</span> is doing today.</p>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#161714] px-4 py-3">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $restaurant->name }}" class="h-10 w-10 rounded-xl object-cover">
                        @else
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#d7a858]/15 text-[#d7a858]"><i class="ti ti-building-store text-xl"></i></span>
                        @endif
                        <div>
                            <p class="text-xs font-extrabold">{{ $restaurant->name }}</p>
                            <span class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[9px] font-extrabold {{ $statusClass }}">{{ $restaurant->status ? Str::headline($restaurant->status) : '—' }}</span>
                        </div>
                    </div>
                </section>

                {{-- Stats --}}
                <section class="mt-8 grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($stats as [$label, $icon, $value])
                        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-[#1b1d18] to-[#151613] p-4">
                            <div class="flex items-center justify-between text-[10px] uppercase tracking-wider text-stone-500">
                                {{ $label }}<i class="ti {{ $icon }} text-base text-[#d7a858]"></i>
                            </div>
                            <p class="mt-3 text-2xl font-bold tracking-tight">{{ $value }}</p>
                            <p class="mt-1 text-[10px] text-stone-600">No data available</p>
                        </div>
                    @endforeach
                </section>

                <div class="mt-4 grid gap-4 xl:grid-cols-[1.6fr_1fr]">
                    {{-- Recent orders --}}
                    <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#161714]">
                        <div class="flex items-center justify-between p-5">
                            <div>
                                <h2 class="text-xs font-extrabold">Recent orders</h2>
                                <p class="mt-1 text-[10px] text-stone-500">Latest activity at your restaurant</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-y border-white/10 text-[9px] uppercase tracking-[0.13em] text-stone-600">
                                        <th class="px-5 py-3">Order</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="px-5 py-14 text-center">
                                            <i class="ti ti-shopping-bag-x text-3xl text-stone-700"></i>
                                            <p class="mt-3 text-xs font-bold text-stone-400">No orders yet</p>
                                            <p class="mt-1 text-[11px] text-stone-600">New orders will appear here once customers start ordering.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <div class="space-y-4">
                        {{-- Quick actions --}}
                        <section class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                            <h2 class="text-xs font-extrabold">Quick actions</h2>
                            <div class="mt-4 grid grid-cols-2 gap-2.5">
                                @foreach ([['ti-tools-kitchen-2', 'Add menu item'], ['ti-shopping-bag', 'View orders'], ['ti-users-group', 'Add staff'], ['ti-discount-2', 'Create offer']] as [$i, $t])
                                    <div class="cursor-not-allowed rounded-xl border border-white/5 bg-white/[0.02] p-3 text-stone-600" title="Coming soon">
                                        <i class="ti {{ $i }} text-lg"></i>
                                        <p class="mt-2 text-[11px] font-bold">{{ $t }}</p>
                                        <p class="text-[9px] text-stone-700">Coming soon</p>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ url('/') }}" class="mt-3 flex items-center gap-3 rounded-xl border border-[#d7a858]/25 bg-[#d7a858]/10 p-3 transition hover:border-[#d7a858]">
                                <span class="grid h-8 w-8 place-items-center rounded-lg bg-[#d7a858] text-[#161714]"><i class="ti ti-external-link"></i></span>
                                <span class="text-[11px] font-extrabold text-[#f1cf8b]">Visit website</span>
                                <i class="ti ti-arrow-right ml-auto text-[#d7a858]"></i>
                            </a>
                        </section>

                        {{-- Restaurant summary --}}
                        <section class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                            <h2 class="text-xs font-extrabold">Restaurant information</h2>
                            <dl class="mt-4 space-y-3 text-[11px]">
                                @foreach ([['ti-building-store', 'Name', $restaurant->name], ['ti-phone', 'Phone', $restaurant->phone], ['ti-map-pin', 'Address', $restaurant->address], ['ti-link', 'Slug', $restaurant->slug]] as [$i, $k, $v])
                                    <div class="flex gap-3">
                                        <i class="ti {{ $i }} mt-0.5 text-[#d7a858]"></i>
                                        <div class="min-w-0">
                                            <dt class="text-[9px] uppercase tracking-wider text-stone-600">{{ $k }}</dt>
                                            <dd class="break-words text-stone-300">{{ $v ?: '—' }}</dd>
                                        </div>
                                    </div>
                                @endforeach
                            </dl>
                            @if ($restaurant->description)
                                <p class="mt-4 border-t border-white/10 pt-4 text-[11px] leading-6 text-stone-500">{{ $restaurant->description }}</p>
                            @endif
                        </section>
                    </div>
                </div>
            @endif
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