<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rider') — NOOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@0.469.0"></script>
    <style>
        body { font-family: Manrope, Arial, sans-serif; }
        .serif { font-family: "Playfair Display", Georgia, serif; }
        [data-lucide] { display: inline-block; }

        .r-card { border: 1px solid rgba(255,255,255,.07); background: #161714; border-radius: 18px; }
        .r-lift { transition: transform .25s ease, border-color .25s ease; }
        .r-lift:hover { transform: translateY(-3px); border-color: rgba(215,168,88,.3); }
        .r-btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; border: 0; border-radius: .75rem; padding: .75rem 1.15rem;
                 font-size: .8rem; font-weight: 800; color: #161714; cursor: pointer; background: linear-gradient(135deg, #f1cf8b, #d7a858); transition: transform .2s, filter .2s; }
        .r-btn:hover:not(:disabled) { transform: translateY(-2px); }
        .r-btn:disabled { opacity: .55; cursor: progress; }
        .r-btn:focus-visible, .r-focus:focus-visible { outline: 2px solid #f1cf8b; outline-offset: 3px; }
        .r-badge { display: inline-flex; align-items: center; gap: .4rem; border-radius: 999px; border: 1px solid; padding: .3rem .7rem; font-size: 10px; font-weight: 800; }
        .b-gold  { border-color: rgba(215,168,88,.3); background: rgba(215,168,88,.1); color: #f1cf8b; }
        .b-green { border-color: rgba(184,217,133,.3); background: rgba(184,217,133,.1); color: #b8d985; }
        .b-blue  { border-color: rgba(167,196,233,.3); background: rgba(167,196,233,.1); color: #a7c4e9; }
        .b-mute  { border-color: rgba(255,255,255,.12); background: rgba(255,255,255,.04); color: #a8a29e; }

        @keyframes rIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
        @keyframes rGrow { from { transform: scaleX(0); } to { transform: scaleX(1); } }
        @keyframes rSpin { to { transform: rotate(360deg); } }
        .r-in { animation: rIn .55s ease both; animation-delay: calc(var(--i, 0) * 70ms); }
        .r-bar { transform-origin: left; animation: rGrow .7s ease both; animation-delay: calc(var(--i, 0) * 130ms + .25s); }
        .r-spin { animation: rSpin .8s linear infinite; }

        .pager nav svg { width: 1rem; height: 1rem; display: inline; }
        .pager nav a, .pager nav span span { background: #11120f !important; border-color: rgba(255,255,255,.1) !important; color: #d6d3d1 !important; }
        .pager nav p { display: none; }

        @media (prefers-reduced-motion: reduce) {
            .r-in, .r-bar { animation: none !important; }
            .r-lift, .r-btn { transition: none !important; }
            .r-lift:hover, .r-btn:hover { transform: none !important; }
            .r-spin { animation-duration: 2.4s; }
        }
    </style>
</head>

@php
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();
    $initials = $user ? \Illuminate\Support\Str::of($user->name)->explode(' ')->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('') : '';
    $siteUrl = Route::has('home') ? route('home') : url('/');

    // [label, icon, route name (null = not built yet)]
    $nav = [
        ['Overview', 'layout-dashboard', 'rider.dashboard'],
        ['Available Orders', 'clipboard-list', 'rider.available'],
        ['My Deliveries', 'package', 'rider.deliveries'],
        ['Active Delivery', 'navigation', 'rider.active'],
        ['Delivery History', 'history', 'rider.history'],
        ['Earnings', 'wallet', null],
        ['Profile', 'user-cog', null],
    ];
@endphp

<body class="bg-[#0e0f0d] text-[#f6f2e9] antialiased">
<div class="flex min-h-screen">

    <div id="overlay" class="fixed inset-0 z-40 hidden bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/[.07] bg-[#11120f] px-4 py-6 transition-transform lg:static lg:translate-x-0">
        <a href="{{ route('rider.dashboard') }}" class="mb-8 flex items-center gap-3 px-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </a>

        <p class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.17em] text-stone-500">Rider</p>
        <nav class="flex-1 space-y-1 overflow-y-auto">
            @foreach ($nav as [$label, $icon, $name])
                @if ($name && Route::has($name))
                    @php $active = request()->routeIs($name); @endphp
                    <a href="{{ route($name) }}" @if ($active) aria-current="page" @endif
                       class="r-focus flex items-center gap-3 rounded-xl border px-3 py-2.5 text-xs font-bold transition {{ $active ? 'border-[#d7a858]/25 bg-gradient-to-r from-[#d7a858]/10 to-transparent text-[#f1cf8b]' : 'border-transparent text-stone-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4 {{ $active ? 'text-[#d7a858]' : '' }}"></i>{{ $label }}
                    </a>
                @else
                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-bold text-stone-600" title="Coming soon">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>{{ $label }}
                        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[9px] text-stone-500">Soon</span>
                    </div>
                @endif
            @endforeach

            <a href="{{ $siteUrl }}" class="r-focus mt-3 flex items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-xs font-bold text-stone-400 transition hover:bg-white/5 hover:text-white">
                <i data-lucide="external-link" class="h-4 w-4"></i>Visit Website
            </a>
        </nav>
    </aside>

    {{-- Main --}}
    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-30 flex h-[68px] items-center justify-between border-b border-white/[.07] bg-[#0e0f0d]/90 px-4 backdrop-blur sm:px-8">
            <div class="flex items-center gap-3">
                <button id="menuBtn" class="grid h-9 w-9 place-items-center rounded-full border border-white/10 text-stone-300 lg:hidden" aria-label="Open menu">
                    <i data-lucide="menu" class="h-4 w-4"></i>
                </button>
                <div class="hidden items-center gap-2 text-[11px] text-stone-500 sm:flex">
                    <span>NOOR</span><i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <span>Rider</span><i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <b class="font-semibold text-[#f1cf8b]">@yield('page', 'Overview')</b>
                </div>
                <b class="text-sm font-extrabold sm:hidden">@yield('page', 'Overview')</b>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $siteUrl }}" class="r-focus hidden items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b] sm:inline-flex">
                    <i data-lucide="external-link" class="h-3.5 w-3.5"></i>Visit Website
                </a>

                @auth
                    <div class="relative">
                        <button id="profileBtn" aria-expanded="false" aria-label="Open account menu"
                                class="r-focus flex items-center gap-2.5 rounded-full border border-[#d7a858]/25 bg-[#d7a858]/[.06] p-1 transition hover:border-[#d7a858] sm:pr-3">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-[#f1cf8b] to-[#d7a858] text-[11px] font-black text-[#161714]">{{ $initials }}</span>
                            <span class="hidden text-left leading-tight sm:block">
                                <strong class="block text-[11px]">{{ $user->name }}</strong>
                                <small class="block text-[9px] text-stone-400">Rider</small>
                            </span>
                            <i data-lucide="chevron-down" class="hidden h-3.5 w-3.5 text-[#d7a858] sm:block"></i>
                        </button>

                        <div id="profileMenu" class="absolute right-0 top-14 hidden w-64 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-white/15 bg-[#1a1c17] shadow-2xl shadow-black/80">
                            <div class="border-b border-white/10 p-4">
                                <strong class="block truncate text-sm">{{ $user->name }}</strong>
                                <span class="block truncate font-mono text-[10px] text-stone-400">{{ $user->email }}</span>
                                <span class="r-badge b-gold mt-2">Rider</span>
                            </div>
                            <div class="p-2">
                                <a href="{{ $siteUrl }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs font-semibold text-stone-300 transition hover:bg-white/5 hover:text-[#f1cf8b]">
                                    <i data-lucide="external-link" class="h-4 w-4 text-[#d7a858]"></i>Visit Website
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-xs font-semibold text-[#ed8b79] transition hover:bg-[#ed8b79]/10">
                                        <i data-lucide="log-out" class="h-4 w-4"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="r-btn">Login</a>
                    @endif
                @endauth
            </div>
        </header>

        <main class="px-4 pb-14 pt-7 sm:px-8">
            @if (session('success'))
                <div class="r-in mb-6 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4" role="status">
                    <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-[#b8d985]"></i>
                    <p class="text-xs font-semibold text-[#d3e8b0]">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="r-in mb-6 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4" role="alert">
                    <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0 text-[#ed8b79]"></i>
                    <p class="text-xs font-semibold text-[#f3b3a7]">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@yield('scripts')

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const drawer = (open) => { sidebar.classList.toggle('-translate-x-full', !open); overlay.classList.toggle('hidden', !open); };
    document.getElementById('menuBtn').addEventListener('click', () => drawer(true));
    overlay.addEventListener('click', () => drawer(false));

    const pBtn = document.getElementById('profileBtn');
    const pMenu = document.getElementById('profileMenu');
    const setMenu = (open) => { if (!pMenu) return; pMenu.classList.toggle('hidden', !open); pBtn.setAttribute('aria-expanded', open); };
    pBtn?.addEventListener('click', (e) => { e.stopPropagation(); setMenu(pMenu.classList.contains('hidden')); });
    document.addEventListener('click', (e) => { if (pMenu && !pMenu.contains(e.target)) setMenu(false); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { setMenu(false); drawer(false); } });

    // Every order action is a real server request: block double submits and show progress.
    // Success is never shown here; the page reloads with the server's message.
    const spinner = '<svg class="r-spin h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>';
    document.querySelectorAll('form[data-once]').forEach((form) => form.addEventListener('submit', (e) => {
        if (form.dataset.sent) { e.preventDefault(); return; }
        form.dataset.sent = '1';
        const btn = form.querySelector('button[type="submit"]');
        btn.dataset.html = btn.innerHTML;
        btn.innerHTML = spinner + '<span>Working...</span>';
        setTimeout(() => { btn.disabled = true; }, 0);
    }));
    window.addEventListener('pageshow', (e) => {
        if (!e.persisted) return;
        document.querySelectorAll('form[data-once]').forEach((form) => {
            delete form.dataset.sent;
            const btn = form.querySelector('button[type="submit"]');
            if (btn.dataset.html) { btn.innerHTML = btn.dataset.html; btn.disabled = false; }
        });
        window.lucide?.createIcons();
    });

    window.lucide?.createIcons();
</script>
</body>
</html>