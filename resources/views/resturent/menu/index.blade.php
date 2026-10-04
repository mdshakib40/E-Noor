<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu / Food Items — NOOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Manrope, Arial, sans-serif; }
        .font-display { font-family: "Playfair Display", Georgia, serif; }
        .pager nav svg { width: 1rem; height: 1rem; display: inline; }
        .pager nav a, .pager nav span[aria-current] span, .pager nav span[aria-disabled] span { background: #11120f !important; border-color: #ffffff1a !important; color: #d6d3d1 !important; }
        .pager nav p { color: #78716c !important; font-size: 11px; }
        .pager nav span[aria-current] span { color: #f1cf8b !important; border-color: #d7a85860 !important; }
    </style>
</head>

@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $initials = Str::of($user->name)->explode(' ')->filter()->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');

    $isPaginated = $menuItems instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $itemCount = $isPaginated ? $menuItems->total() : count($menuItems);

    // [label, icon, href, active]
    $nav = [
        ['Dashboard', 'ti-layout-dashboard', route('resturent.dashboard'), false],
        ['Orders', 'ti-shopping-bag', route('resturent.orders.index'), false],
        ['Menu / Food Items', 'ti-tools-kitchen-2', null, true],
        ['Categories', 'ti-category', null, false],
        ['Staff', 'ti-users-group', route('resturent.staff.index'), false],
        ['Revenue / Reports', 'ti-chart-bar', null, false],
    ];
@endphp

<body class="bg-[#0e0f0d] text-[#f6f2e9] antialiased">
<div class="flex min-h-screen">

    <div id="overlay" class="fixed inset-0 z-40 hidden bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/10 bg-[#11120f] px-4 py-6 transition-transform lg:static lg:translate-x-0">
        <a href="{{ route('resturent.dashboard') }}" class="mb-8 flex items-center gap-3 px-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </a>

        <p class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.17em] text-stone-500">Workspace</p>
        <nav class="flex-1 space-y-1 overflow-y-auto">
            @foreach ($nav as [$label, $icon, $href, $active])
                @if ($active)
                    <div aria-current="page" class="flex items-center gap-3 rounded-xl border border-[#d7a858]/25 bg-gradient-to-r from-[#d7a858]/10 to-transparent px-3 py-2.5 text-xs font-bold text-[#f1cf8b]">
                        <i class="ti {{ $icon }} text-base text-[#d7a858]"></i>{{ $label }}
                    </div>
                @elseif ($href)
                    <a href="{{ $href }}" class="flex items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-xs font-bold text-stone-400 transition hover:bg-white/5 hover:text-white">
                        <i class="ti {{ $icon }} text-base"></i>{{ $label }}
                    </a>
                @else
                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-bold text-stone-600" title="Coming soon">
                        <i class="ti {{ $icon }} text-base"></i>{{ $label }}
                        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] font-bold text-stone-500">Soon</span>
                    </div>
                @endif
            @endforeach
        </nav>

        <div class="mt-6 border-t border-white/10 px-2 pt-4">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-[#d7a858] text-[11px] font-black text-[#161714]">{{ $initials }}</span>
                <div class="min-w-0">
                    <p class="truncate text-xs font-bold">{{ $user->name }}</p>
                    <p class="text-[10px] text-stone-500">Restaurant owner</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-xs font-semibold text-[#ed8b79] transition hover:bg-[#ed8b79]/10">
                    <i class="ti ti-logout text-base"></i>Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-30 flex h-[72px] items-center justify-between border-b border-white/10 bg-[#0e0f0d]/90 px-4 backdrop-blur sm:px-8">
            <div class="flex items-center gap-3">
                <button id="menuBtn" class="grid h-9 w-9 place-items-center rounded-full border border-white/10 text-stone-300 lg:hidden" aria-label="Open menu">
                    <i class="ti ti-menu-2 text-lg"></i>
                </button>
                <div class="hidden items-center gap-2 text-[11px] text-stone-500 sm:flex">
                    <span>NOOR</span><i class="ti ti-chevron-right text-xs"></i>
                    <span>Management</span><i class="ti ti-chevron-right text-xs"></i>
                    <b class="font-semibold text-[#f1cf8b]">Menu / Food Items</b>
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
                        <i class="ti ti-tools-kitchen-2 text-3xl"></i>
                    </span>
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Management</p>
                        <h1 class="font-display mt-2 text-4xl leading-tight tracking-tight sm:text-5xl">Menu / Food Items</h1>
                        <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                            Food items served at <span class="font-semibold text-[#f1cf8b]">{{ $restaurant->name }}</span>.
                        </p>
                    </div>
                </div>
                <a href="{{ route('resturent.menu.create') }}"
                   class="inline-flex flex-none items-center justify-center gap-2 rounded-xl bg-[#d7a858] px-5 py-3 text-sm font-extrabold text-[#161714] shadow-lg shadow-[#d7a858]/20 transition hover:-translate-y-0.5 hover:bg-[#f1cf8b]">
                    <i class="ti ti-plus text-lg"></i>Add Food Item
                </a>
            </section>

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4" role="status">
                    <i class="ti ti-circle-check mt-0.5 text-lg text-[#b8d985]"></i>
                    <p class="text-xs font-semibold text-[#d3e8b0]">{{ session('success') }}</p>
                </div>
            @endif

            @if ($itemCount > 0)
                <div class="mt-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-extrabold">All food items</h2>
                        <p class="mt-1 text-[10px] text-stone-500">Manage what your customers can order</p>
                    </div>
                    <span class="rounded-full bg-white/5 px-3 py-1 text-[10px] font-bold text-stone-400">{{ $itemCount }} {{ $itemCount == 1 ? 'item' : 'items' }}</span>
                </div>

                {{-- Item cards --}}
                <section class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($menuItems as $menuItem)
                        @php
                            $status = strtolower((string) ($menuItem->status instanceof \BackedEnum ? $menuItem->status->value : $menuItem->status));
                            $isActive = $status === 'active';
                            $available = (bool) $menuItem->is_available;
                            $imageUrl = $menuItem->image ? asset('storage/' . ltrim($menuItem->image, '/')) : null;
                        @endphp

                        <article class="group flex flex-col overflow-hidden rounded-2xl border border-white/10 bg-[#161714] transition hover:border-[#d7a858]/30">
                            {{-- Image --}}
                            <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-[#24251e] to-[#11120f]">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $menuItem->name }}" loading="lazy"
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="grid h-full w-full place-items-center">
                                        <div class="text-center">
                                            <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl border border-[#d7a858]/20 bg-[#d7a858]/10 text-[#d7a858]">
                                                <i class="ti ti-tools-kitchen-2 text-3xl"></i>
                                            </span>
                                            <p class="mt-2 text-[10px] font-semibold text-stone-600">No image</p>
                                        </div>
                                    </div>
                                @endif

                                <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold backdrop-blur {{ $isActive ? 'bg-[#0e0f0d]/80 text-[#b8d985]' : 'bg-[#0e0f0d]/80 text-stone-400' }}">
                                    <i class="h-1.5 w-1.5 rounded-full bg-current"></i>{{ Str::headline($status ?: 'inactive') }}
                                </span>
                            </div>

                            {{-- Body --}}
                            <div class="flex flex-1 flex-col p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="min-w-0 break-words text-sm font-extrabold leading-snug">{{ $menuItem->name }}</h3>
                                    <p class="flex-none text-base font-extrabold text-[#f1cf8b]">৳{{ number_format((float) $menuItem->price, 2) }}</p>
                                </div>

                                <p class="mt-2 line-clamp-2 min-h-[2.5rem] text-[11px] leading-5 text-stone-500">
                                    {{ $menuItem->description ?: 'No description added.' }}
                                </p>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold {{ $available ? 'bg-[#b8d985]/10 text-[#b8d985]' : 'bg-[#ed8b79]/10 text-[#ed8b79]' }}">
                                        <i class="ti {{ $available ? 'ti-circle-check' : 'ti-circle-x' }} text-xs"></i>{{ $available ? 'Available' : 'Unavailable' }}
                                    </span>
                                    <span class="ml-auto font-mono text-[9px] text-stone-600">
                                        Added {{ $menuItem->created_at ? $menuItem->created_at->format('d M Y') : '—' }}
                                    </span>
                                </div>

                                {{-- Actions --}}
                                <div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/10 pt-4">
                                    <a href="{{ route('resturent.menu.edit', $menuItem->id) }}"
                                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#d7a858]/30 bg-[#d7a858]/10 px-3 py-2.5 text-[11px] font-extrabold text-[#f1cf8b] transition hover:border-[#d7a858] hover:bg-[#d7a858]/20">
                                        <i class="ti ti-pencil text-sm"></i>Edit
                                    </a>
                                    <form method="POST" action="{{ route('resturent.menu.destroy', $menuItem->id) }}"
                                          onsubmit="return confirm('Delete &quot;{{ e(addslashes($menuItem->name)) }}&quot;? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#ed8b79]/25 bg-[#ed8b79]/5 px-3 py-2.5 text-[11px] font-extrabold text-[#ed8b79] transition hover:border-[#ed8b79]/60 hover:bg-[#ed8b79]/10">
                                            <i class="ti ti-trash text-sm"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                @if ($isPaginated && $menuItems->hasPages())
                    <div class="pager mt-6 rounded-2xl border border-white/10 bg-[#161714] p-4">{{ $menuItems->links() }}</div>
                @endif
            @else
                {{-- Empty state --}}
                <section class="relative mt-8 overflow-hidden rounded-3xl border border-[#d7a858]/20 bg-gradient-to-br from-[#24251e] via-[#181a16] to-[#11120f] px-6 py-16 text-center">
                    <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full border border-[#d7a858]/10"></div>
                    <span class="relative mx-auto grid h-16 w-16 place-items-center rounded-2xl border border-[#d7a858]/20 bg-[#d7a858]/10 text-[#d7a858]">
                        <i class="ti ti-tools-kitchen-2 text-3xl"></i>
                    </span>
                    <p class="relative mt-5 text-sm font-extrabold">No food items yet</p>
                    <p class="relative mx-auto mt-2 max-w-sm text-[11px] leading-6 text-stone-500">
                        Add your first dish to start building the menu for {{ $restaurant->name }}.
                    </p>
                    <a href="{{ route('resturent.menu.create') }}"
                       class="relative mt-6 inline-flex items-center gap-2 rounded-xl bg-[#d7a858] px-5 py-3 text-sm font-extrabold text-[#161714] shadow-lg shadow-[#d7a858]/20 transition hover:-translate-y-0.5 hover:bg-[#f1cf8b]">
                        <i class="ti ti-plus text-lg"></i>Add Food Item
                    </a>
                </section>
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
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleSidebar(false); });
</script>
</body>
</html>