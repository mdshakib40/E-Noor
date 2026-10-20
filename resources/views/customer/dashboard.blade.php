
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Dashboard — NOOR</title>

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
                        },
                        ink: '#11110F',
                        panel: '#191916',
                    }
                }
            }
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>

    <style>
        * { box-sizing: border-box; }

        body {
            background: #11110F;
            color: #F5F1E8;
        }

        .glass {
            background: linear-gradient(
                145deg,
                rgba(255,255,255,.045),
                rgba(255,255,255,.012)
            );
            border: 1px solid rgba(255,255,255,.075);
        }

        .gold-glow {
            box-shadow: 0 0 45px rgba(216,180,92,.08);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            color: #96948C;
            transition: .2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #F1D995;
            background: rgba(216,180,92,.09);
        }

        .nav-link.active {
            border: 1px solid rgba(216,180,92,.13);
        }

        .stat-card {
            transition: transform .25s ease, border-color .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(216,180,92,.28);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
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

        .muted { color: #929087; }

        .hero-pattern {
            background-image:
                radial-gradient(circle at 85% 20%, rgba(216,180,92,.15), transparent 32%),
                radial-gradient(circle at 5% 100%, rgba(216,180,92,.07), transparent 35%);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="min-h-screen font-sans antialiased">

@php
    $statusLabels = [
        'pending' => 'Order placed',
        'accepted' => 'Accepted',
        'preparing' => 'Preparing',
        'ready_for_pickup' => 'Ready for pickup',
        'picked_up' => 'Picked up',
        'out_for_delivery' => 'On the way',
        'delivered' => 'Delivered',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'rejected' => 'Rejected',
    ];

    $statusStyles = [
        'pending' => 'background:rgba(234,179,8,.10);color:#FACC15',
        'accepted' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'preparing' => 'background:rgba(251,146,60,.10);color:#FDBA74',
        'ready_for_pickup' => 'background:rgba(192,132,252,.10);color:#D8B4FE',
        'picked_up' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'out_for_delivery' => 'background:rgba(96,165,250,.10);color:#93C5FD',
        'delivered' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'completed' => 'background:rgba(74,222,128,.10);color:#86EFAC',
        'cancelled' => 'background:rgba(248,113,113,.10);color:#FCA5A5',
        'rejected' => 'background:rgba(248,113,113,.10);color:#FCA5A5',
    ];

    $firstName = explode(' ', trim($user->name))[0] ?? 'there';

    $currentStatus = $activeOrder
        ? strtolower($activeOrder->status)
        : null;

    $progressSteps = [
        'pending' => 1,
        'accepted' => 2,
        'preparing' => 2,
        'ready_for_pickup' => 3,
        'picked_up' => 3,
        'out_for_delivery' => 4,
        'delivered' => 4,
        'completed' => 4,
    ];

    $progress = $activeOrder
        ? ($progressSteps[$currentStatus] ?? 1)
        : 0;
@endphp

<div class="min-h-screen lg:flex">

    {{-- Desktop sidebar --}}
    <aside class="hidden w-[250px] shrink-0 border-r border-white/[.07] bg-[#141411] px-5 py-7 lg:flex lg:flex-col">

        <a href="{{ route('home') }}" class="mb-11 flex items-center gap-3 px-2">
            <div class="grid h-11 w-11 place-items-center rounded-2xl border border-gold-400/30 bg-gold-400/[.09]">
                <i data-lucide="utensils" class="h-5 w-5 text-gold-400"></i>
            </div>
            <div>
                <div class="font-display text-2xl tracking-[.18em] text-gold-300">NOOR</div>
                <div class="mt-0.5 text-[9px] uppercase tracking-[.28em] text-stone-500">Food, considered</div>
            </div>
        </a>

        <div class="mb-3 px-3 text-[10px] font-extrabold uppercase tracking-[.2em] text-stone-600">
            Workspace
        </div>

        <nav class="space-y-1">
            <a href="{{ route('customer.dashboard') }}" class="nav-link active">
                <i data-lucide="layout-dashboard" class="h-[18px] w-[18px]"></i>
                <span class="text-sm font-semibold">Overview</span>
            </a>

            <a href="{{ route('cart.index') }}" class="nav-link">
                <i data-lucide="shopping-bag" class="h-[18px] w-[18px]"></i>
                <span class="text-sm font-semibold">My Bag</span>
            </a>

            <a href="{{ route('home') }}" class="nav-link">
                <i data-lucide="compass" class="h-[18px] w-[18px]"></i>
                <span class="text-sm font-semibold">Explore Food</span>
            </a>
        </nav>

        <div class="mb-3 mt-9 px-3 text-[10px] font-extrabold uppercase tracking-[.2em] text-stone-600">
            Account
        </div>

        <nav class="space-y-1">
            <a href="{{ route('home') }}" class="nav-link">
                <i data-lucide="globe-2" class="h-[18px] w-[18px]"></i>
                <span class="text-sm font-semibold">Visit Website</span>
                <i data-lucide="arrow-up-right" class="ml-auto h-3.5 w-3.5"></i>
            </a>
        </nav>

        <div class="mt-auto">
            <div class="mb-5 overflow-hidden rounded-2xl border border-gold-400/15 bg-gold-400/[.045] p-4">
                <div class="mb-3 grid h-9 w-9 place-items-center rounded-xl bg-gold-400/10">
                    <i data-lucide="sparkles" class="h-4 w-4 text-gold-300"></i>
                </div>
                <p class="font-display text-lg text-stone-100">Good food. Good mood.</p>
                <p class="mt-1 text-xs leading-5 text-stone-500">Your next favourite meal is waiting.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-gold-300">
                    Explore restaurants <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                </a>
            </div>

            <div class="flex items-center gap-3 border-t border-white/[.07] pt-5">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[#29271F] text-sm font-extrabold text-gold-300">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-bold text-stone-200">{{ $user->name }}</div>
                    <div class="truncate text-[11px] text-stone-500">NOOR customer</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Log out" aria-label="Log out" class="rounded-xl p-2 text-stone-500 transition hover:bg-red-400/10 hover:text-red-300">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main area --}}
    <main class="min-w-0 flex-1">

        {{-- Mobile / top header --}}
        <header class="sticky top-0 z-30 border-b border-white/[.07] bg-[#11110F]/95 px-4 py-4 backdrop-blur-xl sm:px-7 lg:px-10">
            <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 lg:hidden">
                    <div class="grid h-9 w-9 place-items-center rounded-xl border border-gold-400/25 bg-gold-400/[.08]">
                        <i data-lucide="utensils" class="h-4 w-4 text-gold-300"></i>
                    </div>
                    <span class="font-display text-xl tracking-[.15em] text-gold-300">NOOR</span>
                </a>

                <div class="hidden sm:block">
                    <div class="text-xs text-stone-500">Your space</div>
                    <div class="mt-0.5 text-sm font-bold text-stone-200">Customer Dashboard</div>
                </div>

                <div class="ml-auto flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2.5 text-xs font-bold text-stone-300 transition hover:border-gold-400/30 hover:text-gold-300 sm:px-4">
                        <i data-lucide="external-link" class="h-4 w-4"></i>
                        <span>Visit Website</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="lg:hidden">
                        @csrf
                        <button type="submit" aria-label="Log out" class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 text-stone-300 transition hover:border-red-400/30 hover:text-red-300">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                        </button>
                    </form>

                    <div class="hidden h-10 w-10 place-items-center rounded-full border border-gold-400/20 bg-gold-400/[.08] text-sm font-extrabold text-gold-300 sm:grid">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <nav class="mx-auto mt-4 flex max-w-[1440px] gap-2 overflow-x-auto lg:hidden">
                <a href="{{ route('customer.dashboard') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gold-400/10 px-3 py-2 text-xs font-bold text-gold-300">
                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Overview
                </a>
                <a href="{{ route('cart.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-stone-400 hover:bg-white/5">
                    <i data-lucide="shopping-bag" class="h-4 w-4"></i> My Bag
                </a>
                <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-stone-400 hover:bg-white/5">
                    <i data-lucide="compass" class="h-4 w-4"></i> Explore
                </a>
            </nav>
        </header>

        <div class="mx-auto max-w-[1440px] px-4 py-7 sm:px-7 sm:py-9 lg:px-10 lg:py-10">

            {{-- Welcome hero --}}
            <section class="hero-pattern gold-glow relative overflow-hidden rounded-[28px] border border-gold-400/[.17] bg-[#191915] p-6 sm:p-9 lg:p-11">
                <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full border border-gold-400/[.08]"></div>
                <div class="pointer-events-none absolute -right-2 -top-10 h-48 w-48 rounded-full border border-gold-400/[.09]"></div>

                <div class="relative z-10 grid items-center gap-8 lg:grid-cols-[1.2fr_.8fr]">
                    <div>
                        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-gold-400/20 bg-gold-400/[.07] px-3 py-2 text-[10px] font-extrabold uppercase tracking-[.18em] text-gold-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-gold-300"></span>
                            Your personal dining space
                        </div>

                        <p class="mb-2 text-sm font-medium text-stone-400">Welcome back, {{ $firstName }}.</p>

                        <h1 class="max-w-2xl font-display text-4xl leading-[1.13] tracking-tight text-stone-100 sm:text-5xl lg:text-[58px]">
                            Something delicious
                            <span class="italic text-gold-300">starts here.</span>
                        </h1>

                        <p class="mt-5 max-w-xl text-sm leading-7 text-stone-400 sm:text-base">
                            Your favourite flavours, thoughtful meals, and the next little moment of happiness are just a few clicks away.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ route('home') }}" class="gold-button inline-flex items-center gap-2 rounded-xl px-5 py-3.5 text-sm font-extrabold">
                                <i data-lucide="utensils" class="h-4 w-4"></i>
                                Explore restaurants
                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                            </a>

                            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[.025] px-5 py-3.5 text-sm font-bold text-stone-200 transition hover:border-gold-400/25 hover:text-gold-300">
                                <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                                View my bag
                            </a>
                        </div>
                    </div>

                    <div class="relative hidden min-h-[235px] lg:block">
                        <div class="absolute right-2 top-2 h-[235px] w-[235px] rounded-full border border-gold-400/15"></div>
                        <div class="absolute right-8 top-8 h-[183px] w-[183px] rounded-full border border-gold-400/15"></div>

                        <div class="absolute right-0 top-0 grid h-[235px] w-[235px] place-items-center overflow-hidden rounded-full border border-gold-400/20 bg-[#242117]">
                            <img
                                src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=700&q=85"
                                alt="A beautifully prepared meal"
                                class="h-full w-full object-cover opacity-90"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-[#11110F]/45 to-transparent"></div>
                        </div>

                        <div class="absolute bottom-2 left-0 rounded-2xl border border-white/10 bg-[#1D1C17]/95 p-4 shadow-2xl backdrop-blur">
                            <div class="flex items-center gap-3">
                                <div class="grid h-10 w-10 place-items-center rounded-xl bg-gold-400/10">
                                    <i data-lucide="heart" class="h-5 w-5 text-gold-300"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-stone-100">Made for your cravings</div>
                                    <div class="mt-1 text-[10px] text-stone-500">Discover something wonderful</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Statistics --}}
            <section class="mt-8">
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-gold-400">At a glance</p>
                        <h2 class="mt-2 font-display text-2xl text-stone-100 sm:text-3xl">Your overview</h2>
                    </div>
                    <span class="hidden text-xs text-stone-500 sm:block">A little snapshot of your NOOR journey</span>
                </div>

                <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
                    <div class="stat-card glass rounded-2xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-stone-400 sm:text-sm">Total orders</span>
                            <div class="grid h-9 w-9 place-items-center rounded-xl bg-gold-400/10">
                                <i data-lucide="receipt-text" class="h-4 w-4 text-gold-300"></i>
                            </div>
                        </div>
                        <div class="mt-5 text-3xl font-extrabold tracking-tight text-stone-100">{{ number_format($totalOrders) }}</div>
                        <p class="mt-1 text-[10px] text-stone-500 sm:text-xs">Orders placed so far</p>
                    </div>

                    <div class="stat-card glass rounded-2xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-stone-400 sm:text-sm">In progress</span>
                            <div class="grid h-9 w-9 place-items-center rounded-xl bg-blue-400/10">
                                <i data-lucide="clock-3" class="h-4 w-4 text-blue-300"></i>
                            </div>
                        </div>
                        <div class="mt-5 text-3xl font-extrabold tracking-tight text-stone-100">{{ number_format($activeOrders->count()) }}</div>
                        <p class="mt-1 text-[10px] text-stone-500 sm:text-xs">Orders still underway</p>
                    </div>

                    <div class="stat-card glass rounded-2xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-stone-400 sm:text-sm">Completed</span>
                            <div class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-400/10">
                                <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-300"></i>
                            </div>
                        </div>
                        <div class="mt-5 text-3xl font-extrabold tracking-tight text-stone-100">{{ number_format($completedOrders) }}</div>
                        <p class="mt-1 text-[10px] text-stone-500 sm:text-xs">Successfully delivered</p>
                    </div>

                    <div class="stat-card glass rounded-2xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-stone-400 sm:text-sm">Delivered value</span>
                            <div class="grid h-9 w-9 place-items-center rounded-xl bg-purple-400/10">
                                <i data-lucide="wallet" class="h-4 w-4 text-purple-300"></i>
                            </div>
                        </div>
                        <div class="mt-5 break-words text-2xl font-extrabold tracking-tight text-stone-100 sm:text-3xl">
                            ৳{{ number_format((float) $totalSpent, 2) }}
                        </div>
                        <p class="mt-1 text-[10px] text-stone-500 sm:text-xs">Value of completed orders</p>
                    </div>
                </div>
            </section>

            {{-- Active order --}}
            <section class="mt-10">
                <div class="mb-4">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-gold-400">On its way to you</p>
                    <h2 class="mt-2 font-display text-2xl text-stone-100 sm:text-3xl">Your active order</h2>
                </div>

                @if($activeOrder)
                    <div class="glass overflow-hidden rounded-[24px]">
                        <div class="grid lg:grid-cols-[1fr_280px]">
                            <div class="p-5 sm:p-7">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <div class="text-[10px] font-extrabold uppercase tracking-[.16em] text-stone-500">Order reference</div>
                                        <div class="mt-2 text-lg font-extrabold text-stone-100">{{ $activeOrder->order_number }}</div>
                                        <div class="mt-1 text-xs text-stone-500">
                                            {{ $activeOrder->created_at?->format('d M Y, h:i A') }}
                                        </div>
                                    </div>

                                    <span class="status-pill" style="{{ $statusStyles[$currentStatus] ?? 'background:rgba(234,179,8,.1);color:#FACC15' }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        {{ $statusLabels[$currentStatus] ?? ucwords(str_replace('_', ' ', $currentStatus)) }}
                                    </span>
                                </div>

                                <div class="mt-7 flex items-center gap-4 rounded-2xl border border-white/[.06] bg-white/[.02] p-4">
                                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gold-400/[.08]">
                                        <i data-lucide="store" class="h-5 w-5 text-gold-300"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-[10px] uppercase tracking-[.15em] text-stone-500">Restaurant</div>
                                        <div class="mt-1 truncate text-sm font-bold text-stone-100">
                                            {{ $activeOrder->restaurant?->name ?? 'NOOR Restaurant' }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] uppercase tracking-[.15em] text-stone-500">Order total</div>
                                        <div class="mt-1 text-base font-extrabold text-gold-300">৳{{ number_format((float) $activeOrder->total, 2) }}</div>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="mb-5 flex items-center justify-between gap-3">
                                        <span class="text-xs font-bold text-stone-300">Delivery progress</span>
                                        <span class="text-[10px] text-stone-500">Status updates appear here</span>
                                    </div>

                                    <div class="relative grid grid-cols-4 gap-2">
                                        <div class="absolute left-[12%] right-[12%] top-[15px] h-px bg-white/10"></div>
                                        <div class="absolute left-[12%] top-[15px] h-px bg-gold-400 transition-all"
                                             style="width: {{ max(0, min(100, (($progress - 1) / 3) * 76)) }}%"></div>

                                        @php
                                            $steps = [
                                                ['label' => 'Placed', 'icon' => 'clipboard-check'],
                                                ['label' => 'Preparing', 'icon' => 'chef-hat'],
                                                ['label' => 'Picked up', 'icon' => 'package-check'],
                                                ['label' => 'Delivered', 'icon' => 'house'],
                                            ];
                                        @endphp

                                        @foreach($steps as $index => $step)
                                            @php $done = ($index + 1) <= $progress; @endphp
                                            <div class="relative z-10 flex flex-col items-center text-center">
                                                <div class="grid h-[31px] w-[31px] place-items-center rounded-full border {{ $done ? 'border-gold-400 bg-gold-400 text-[#17150F]' : 'border-white/10 bg-[#22221D] text-stone-600' }}">
                                                    <i data-lucide="{{ $step['icon'] }}" class="h-3.5 w-3.5"></i>
                                                </div>
                                                <span class="mt-3 text-[9px] font-bold sm:text-[11px] {{ $done ? 'text-gold-300' : 'text-stone-600' }}">
                                                    {{ $step['label'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col justify-between border-t border-white/[.07] bg-white/[.018] p-5 sm:p-7 lg:border-l lg:border-t-0">
                                <div>
                                    <div class="grid h-11 w-11 place-items-center rounded-2xl border border-gold-400/15 bg-gold-400/[.07]">
                                        <i data-lucide="bike" class="h-5 w-5 text-gold-300"></i>
                                    </div>
                                    <h3 class="mt-4 font-display text-xl text-stone-100">Good things take a little time.</h3>
                                    <p class="mt-2 text-xs leading-6 text-stone-500">
                                        Your order status will update as the restaurant prepares your food and the delivery progresses.
                                    </p>
                                </div>

                                <div class="mt-6 rounded-xl border border-white/[.07] p-3">
                                    <div class="text-[10px] uppercase tracking-[.14em] text-stone-500">Payment method</div>
                                    <div class="mt-2 flex items-center gap-2 text-xs font-bold text-stone-200">
                                        <i data-lucide="wallet" class="h-4 w-4 text-gold-300"></i>
                                        {{ ucwords(str_replace('_', ' ', $activeOrder->payment_method ?? 'cash_on_delivery')) }}
                                    </div>
                                    <div class="mt-2 text-[10px] text-stone-500">
                                        Payment: {{ ucfirst($activeOrder->payment_status ?? 'pending') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="glass relative overflow-hidden rounded-[24px] p-7 sm:p-10">
                        <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full border border-gold-400/[.08]"></div>

                        <div class="relative flex flex-col items-start gap-6 sm:flex-row sm:items-center">
                            <div class="grid h-16 w-16 shrink-0 place-items-center rounded-2xl border border-gold-400/15 bg-gold-400/[.07]">
                                <i data-lucide="cooking-pot" class="h-7 w-7 text-gold-300"></i>
                            </div>

                            <div class="flex-1">
                                <h3 class="font-display text-2xl text-stone-100">Nothing cooking just yet.</h3>
                                <p class="mt-2 max-w-xl text-sm leading-6 text-stone-500">
                                    When you place an order, you can follow its progress right here. In the meantime, discover your next favourite meal.
                                </p>
                            </div>

                            <a href="{{ route('home') }}" class="gold-button inline-flex shrink-0 items-center gap-2 rounded-xl px-5 py-3 text-xs font-extrabold">
                                Find your next meal
                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </section>

            {{-- Recent orders --}}
            <section class="mt-10">
                <div class="mb-5 flex items-end justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-gold-400">Your food journey</p>
                        <h2 class="mt-2 font-display text-2xl text-stone-100 sm:text-3xl">Recent orders</h2>
                    </div>
                    <span class="text-xs text-stone-500">{{ $totalOrders }} {{ $totalOrders === 1 ? 'order' : 'orders' }}</span>
                </div>

                @if($recentOrders->isNotEmpty())
                    <div class="overflow-hidden rounded-[22px] border border-white/[.07] bg-[#171714]">
                        <div class="hidden grid-cols-[1.3fr_1fr_.8fr_.8fr] gap-4 border-b border-white/[.07] px-6 py-4 text-[10px] font-extrabold uppercase tracking-[.15em] text-stone-600 md:grid">
                            <div>Order</div>
                            <div>Restaurant</div>
                            <div>Amount</div>
                            <div class="text-right">Status</div>
                        </div>

                        <div class="divide-y divide-white/[.055]">
                            @foreach($recentOrders as $order)
                                @php
                                    $orderStatus = strtolower($order->status);
                                @endphp

                                <div class="grid gap-3 px-4 py-5 transition hover:bg-white/[.018] sm:px-6 md:grid-cols-[1.3fr_1fr_.8fr_.8fr] md:items-center md:gap-4">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-white/[.07] bg-white/[.025]">
                                            <i data-lucide="receipt" class="h-4 w-4 text-gold-300"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a
                                                href="{{ route('customer.orders.show', $order) }}"
                                                class="truncate text-xs font-extrabold text-stone-200 transition hover:text-gold-300"
                                            >
                                                {{ $order->order_number }}
                                            </a>
                                            <div class="mt-1 text-[10px] text-stone-500">{{ $order->created_at?->format('d M Y · h:i A') }}</div>
                                        </div>
                                    </div>

                                    <div class="min-w-0 pl-[52px] md:pl-0">
                                        <div class="truncate text-xs font-semibold text-stone-300">{{ $order->restaurant?->name ?? 'Restaurant' }}</div>
                                        <div class="mt-1 text-[10px] text-stone-500">
                                            {{ ucwords(str_replace('_', ' ', $order->payment_method ?? 'cash_on_delivery')) }}
                                            · {{ ucfirst($order->payment_status ?? 'pending') }}
                                        </div>
                                    </div>

                                    <div class="pl-[52px] text-sm font-extrabold text-stone-100 md:pl-0">
                                        ৳{{ number_format((float) $order->total, 2) }}
                                    </div>

                                    <div class="flex items-center justify-between gap-3 pl-[52px] md:justify-end md:pl-0">
                                        <span class="status-pill" style="{{ $statusStyles[$orderStatus] ?? 'background:rgba(255,255,255,.06);color:#D6D3D1' }}">
                                            {{ $statusLabels[$orderStatus] ?? ucwords(str_replace('_', ' ', $orderStatus)) }}
                                        </span>
                                        <i data-lucide="chevron-right" class="h-4 w-4 text-stone-600 md:hidden"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="glass rounded-[22px] px-6 py-12 text-center">
                        <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl border border-white/[.07] bg-white/[.025]">
                            <i data-lucide="notebook-tabs" class="h-6 w-6 text-gold-300"></i>
                        </div>
                        <h3 class="mt-5 font-display text-2xl text-stone-100">Your story starts with a meal.</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-stone-500">
                            Your orders will appear here, so you can revisit your dining history whenever you like.
                        </p>
                        <a href="{{ route('home') }}" class="gold-button mt-6 inline-flex items-center gap-2 rounded-xl px-5 py-3 text-xs font-extrabold">
                            Explore the menu <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    </div>
                @endif
            </section>

            {{-- Bottom callout --}}
            <section class="mt-8 flex flex-col gap-4 rounded-[22px] border border-gold-400/10 bg-gradient-to-r from-gold-400/[.07] to-transparent p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
                <div class="flex items-center gap-4">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gold-400/10">
                        <i data-lucide="heart-handshake" class="h-5 w-5 text-gold-300"></i>
                    </div>
                    <div>
                        <h3 class="font-display text-xl text-stone-100">A little something for your appetite.</h3>
                        <p class="mt-1 text-xs leading-5 text-stone-500">Explore restaurants and find something worth coming back for.</p>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-gold-400/20 px-4 py-3 text-xs font-extrabold text-gold-300 transition hover:bg-gold-400/10">
                    Browse restaurants <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                </a>
            </section>

            <footer class="mt-10 flex flex-col gap-2 border-t border-white/[.06] pt-6 text-[10px] text-stone-600 sm:flex-row sm:items-center sm:justify-between">
                <div>© {{ date('Y') }} NOOR. Food, considered.</div>
                <div class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Made for good food moments
                </div>
            </footer>

        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        } else {
            console.error('NOOR dashboard: Lucide failed to load.');
        }
    });
</script>

</body>
</html>
