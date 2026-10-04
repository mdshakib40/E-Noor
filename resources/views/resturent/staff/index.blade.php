<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Management — NOOR</title>
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
    $initials = Str::of($user->name)->explode(' ')->filter()->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');

    // Only the dashboard route exists, so everything else is a non-linked item.
    // [label, icon, href, active]
    $nav = [
        'Overview' => [['Dashboard', 'ti-layout-dashboard', route('resturent.dashboard'), false]],
        'Restaurant' => [
            ['My Restaurant', 'ti-building-store', null, false],
            ['Restaurant Settings', 'ti-settings-2', null, false],
        ],
        'Management' => [
            ['Orders', 'ti-shopping-bag', null, false],
            ['Menu / Food Items', 'ti-tools-kitchen-2', null, false],
            ['Categories', 'ti-category', null, false],
            ['Staff', 'ti-users-group', null, true],
        ],
        'Business' => [
            ['Offers', 'ti-discount-2', null, false],
            ['Revenue / Reports', 'ti-chart-bar', null, false],
        ],
    ];

    $columns = ['Name', 'Email', 'Role', 'Status', 'Actions'];
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
                                <a href="{{ $href }}" class="flex items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-xs font-bold text-stone-400 transition hover:bg-white/5 hover:text-white">
                                    <i class="ti {{ $icon }} text-base"></i>{{ $label }}
                                </a>
                            @elseif ($active)
                                <div class="flex items-center gap-3 rounded-xl border border-[#d7a858]/25 bg-gradient-to-r from-[#d7a858]/10 to-transparent px-3 py-2.5 text-xs font-bold text-[#f1cf8b]" aria-current="page">
                                    <i class="ti {{ $icon }} text-base text-[#d7a858]"></i>{{ $label }}
                                </div>
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
                <p class="text-[10px] text-stone-500">Restaurant owner</p>
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
                    <span>Management</span><i class="ti ti-chevron-right text-xs"></i>
                    <b class="font-semibold text-[#f1cf8b]">Staff</b>
                </div>
            </div>
            <a href="{{ route('resturent.dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b]">
                <i class="ti ti-arrow-left text-sm"></i>Back to Dashboard
            </a>
        </header>

        <main class="px-4 pb-12 pt-8 sm:px-8">

            {{-- Page header --}}
            <section class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div class="flex items-start gap-4">
                    <span class="grid h-14 w-14 flex-none place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">
                        <i class="ti ti-users-group text-3xl"></i>
                    </span>
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Management</p>
                        <h1 class="font-display mt-2 text-4xl leading-tight tracking-tight sm:text-5xl">Staff Management</h1>
                        <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                            Manage your restaurant staff and their access.
                        </p>
                    </div>
                </div>

                {{-- Not linked yet: no staff route exists --}}
                <a href="{{ route('resturent.staff.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#d7a858] px-4 py-2.5 text-[11px] font-extrabold text-[#17120c] transition hover:bg-[#f1cf8b]">
                    <i class="ti ti-plus text-sm"></i>
                    Add Staff
                </a>
            </section>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4" role="status">
                    <i class="ti ti-circle-check mt-0.5 text-lg text-[#b8d985]"></i>
                    <p class="text-xs font-semibold text-[#d3e8b0]">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4" role="alert">
                    <i class="ti ti-alert-circle mt-0.5 text-lg text-[#ed8b79]"></i>
                    <p class="text-xs font-semibold text-[#f3b3a7]">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Staff list --}}
            <section class="mt-8 overflow-hidden rounded-2xl border border-white/10 bg-[#161714]">
                <div class="flex items-center justify-between p-5">
                    <div>
                        <h2 class="text-xs font-extrabold">Team members</h2>
                        <p class="mt-1 text-[10px] text-stone-500">People who can help run your restaurant</p>
                    </div>
                  <span class="rounded-full bg-white/5 px-3 py-1 text-[10px] font-bold text-stone-400">
                        {{ $staff->count() }} {{ $staff->count() === 1 ? 'staff' : 'staff' }}
                  </san>
                </div>

                {{-- Column headers (desktop / tablet only) --}}
                <div class="hidden border-y border-white/10 md:grid md:grid-cols-[1.4fr_1.6fr_1fr_1fr_.8fr]">
                    @foreach ($columns as $i => $col)
                        <div class="px-5 py-3 text-[9px] uppercase tracking-[0.13em] text-stone-600 {{ $i === count($columns) - 1 ? 'text-right' : '' }}">{{ $col }}</div>
                    @endforeach
                </div>

                {{-- Empty state --}}
                @if ($staff->isEmpty())

    {{-- Empty state --}}
    <div class="border-t border-white/10 px-5 py-16 text-center md:border-t-0">
        <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl border border-[#d7a858]/20 bg-[#d7a858]/10 text-[#d7a858]">
            <i class="ti ti-users-group text-3xl"></i>
        </span>

        <p class="mt-5 text-sm font-extrabold">No staff members yet</p>

        <p class="mx-auto mt-2 max-w-sm text-[11px] leading-6 text-stone-500">
            Restaurant staff can be added here. Once added, you'll see their name, email, role and status in this list.
        </p>
    </div>

@else

    {{-- Staff list --}}
    <div class="divide-y divide-white/10">
        @foreach ($staff as $member)
            <div class="grid gap-4 px-5 py-4 md:grid-cols-[1.4fr_1.6fr_1fr_1fr_.8fr] md:items-center">

                {{-- Name --}}
                <div>
                    <p class="text-xs font-bold">{{ $member->name }}</p>
                </div>

                {{-- Email --}}
                <div>
                    <p class="break-all text-xs text-stone-400">{{ $member->email }}</p>
                </div>

                {{-- Role --}}
                <div>
                    <span class="inline-flex rounded-full bg-[#d7a858]/10 px-2.5 py-1 text-[10px] font-bold text-[#f1cf8b]">
                        Restaurant Staff
                    </span>
                </div>

                {{-- Status --}}
                <div>
                    <span class="inline-flex rounded-full bg-[#b8d985]/10 px-2.5 py-1 text-[10px] font-bold text-[#b8d985]">
                        Active
                    </span>
                </div>

                {{-- Actions --}}
                <div class="flex md:justify-end">
                    <button
                        type="button"
                        disabled
                        class="cursor-not-allowed rounded-lg border border-white/10 px-3 py-2 text-[10px] font-bold text-stone-600"
                        title="Coming soon"
                    >
                        Manage
                    </button>
                </div>

            </div>
        @endforeach
    </div>

@endif
            </section>

            {{-- What staff can do --}}
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                @foreach ([
                    ['ti-shopping-bag', 'Handle orders', 'Let your team accept and prepare incoming orders.'],
                    ['ti-tools-kitchen-2', 'Update the menu', 'Give staff access to food items and categories.'],
                    ['ti-shield-check', 'Control access', 'Choose what each team member can see and do.'],
                ] as [$i, $t, $d])
                    <div class="rounded-2xl border border-white/10 bg-[#161714] p-5">
                        <i class="ti {{ $i }} text-xl text-[#d7a858]"></i>
                        <p class="mt-3 text-xs font-extrabold">{{ $t }}</p>
                        <p class="mt-1 text-[11px] text-stone-500">{{ $d }}</p>
                    </div>
                @endforeach
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
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleSidebar(false); });
</script>
</body>
</html>