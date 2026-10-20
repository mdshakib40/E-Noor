<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'NOOR — Food, considered.')
    </title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-[#11110f] text-[#f4f0e8] antialiased">

<!-- =========================
     NAVBAR
========================== -->

<header class="sticky top-0 z-50 border-b border-white/10 bg-[#11110f]/90 backdrop-blur-xl">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="flex h-[76px] items-center justify-between">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="shrink-0">
                <div class="font-noor-serif text-2xl tracking-wide text-[#f4f0e8]">
                    NOOR
                </div>

                <div class="font-noor-mono text-[8px] uppercase tracking-[0.25em] text-[#9d978e]">
                    Food, considered.
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden items-center gap-8 lg:flex">
                <a href="{{ url('/') }}"
                   class="text-xs text-[#d7cabb] transition hover:text-[#f2d092]">
                    Home
                </a>

                <a href="{{ url('/#discover') }}"
                   class="text-xs text-[#d7cabb] transition hover:text-[#f2d092]">
                    Discover
                </a>

                <a href="{{ url('/#offers') }}"
                   class="text-xs text-[#d7cabb] transition hover:text-[#f2d092]">
                    Offers
                </a>

                <a href="{{ url('/#noor-way') }}"
                   class="text-xs text-[#d7cabb] transition hover:text-[#f2d092]">
                    The NOOR way
                </a>
            </nav>

            <!-- Right Side -->
            <div class="flex items-center gap-2 sm:gap-3">

                <!-- Search -->
                <button
                    type="button"
                    aria-label="Search"
                    class="hidden h-10 w-10 items-center justify-center rounded-full border border-white/10 text-[#d7cabb] transition hover:border-[#d8a85a]/40 hover:text-[#d8a85a] sm:flex"
                >
                    <i data-lucide="search" class="h-[17px] w-[17px]"></i>
                </button>

                <!-- Shopping Bag -->
                
                <a
                    href="{{ route('cart.index') }}"
                    aria-label="Open shopping bag"
                    class="relative
                        grid
                        h-[38px]
                        w-[38px]
                        place-items-center
                        rounded-full
                        border
                        border-white/[.094]
                        bg-transparent
                        transition
                        hover:border-gold/40
                        hover:bg-white/[.04]">

                    <i
                        data-lucide="shopping-bag"
                        class="w-4">
                    </i>

                    <span
                        id="bagCount"
                        data-cart-count
                        class="absolute
                            -right-[3px]
                            -top-[3px]
                            grid
                            h-4
                            min-w-4
                            place-items-center
                            rounded-full
                            bg-gold
                            px-1
                            text-[8px]
                            font-extrabold
                            text-night">

                        {{ auth()->check() ? auth()->user()->cartItems()->sum('quantity') : 0 }}

                    </span>

                </a>


                @auth
                    @php
                        $navbarUser = auth()->user();

                        $navbarRole = $navbarUser->role instanceof \App\Enums\UserRole
                            ? $navbarUser->role->value
                            : $navbarUser->role;

                        $navbarDashboard = match ($navbarRole) {
                            'super_admin' => route('admin.dashboard'),
                            'customer' => route('customer.dashboard'),
                            'restaurant_owner' => route('resturent.dashboard'),
                            'restaurant_staff' => route('resturent.staff.dashboard'),
                            'rider' => route('rider.dashboard'),
                            default => url('/'),
                        };
                    @endphp

                    <!-- Desktop Account Menu -->
                    <div class="relative hidden sm:block group">

                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-full border border-white/10 px-4 py-2.5 text-xs text-[#f4f0e8] transition hover:border-[#d8a85a]/40"
                        >
                            <i data-lucide="user-round" class="h-4 w-4 text-[#d8a85a]"></i>

                            <span class="max-w-[110px] truncate">
                                {{ $navbarUser->name }}
                            </span>

                            <i data-lucide="chevron-down" class="h-3 w-3"></i>
                        </button>

                        <div class="absolute right-0 top-full z-50 hidden w-56 pt-2 group-hover:block group-focus-within:block">
                            <div class="rounded-xl border border-white/10 bg-[#191815] p-2 shadow-xl">

                                <div class="mb-1 border-b border-white/10 px-3 py-2">
                                    <p class="truncate text-sm text-[#f4f0e8]">
                                        {{ $navbarUser->name }}
                                    </p>

                                    <p class="truncate text-[10px] text-[#9d978e]">
                                        {{ $navbarUser->email }}
                                    </p>
                                </div>

                                <a
                                    href="{{ $navbarDashboard }}"
                                    class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-xs text-[#d7cabb] transition hover:bg-white/5 hover:text-[#f2d092]"
                                >
                                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                                    Dashboard
                                </a>

                                <a
                                    href="{{ url('/') }}"
                                    class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-xs text-[#d7cabb] transition hover:bg-white/5 hover:text-[#f2d092]"
                                >
                                    <i data-lucide="globe" class="h-4 w-4"></i>
                                    Visit website
                                </a>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-xs text-red-300 transition hover:bg-red-500/10"
                                    >
                                        <i data-lucide="log-out" class="h-4 w-4"></i>
                                        Logout
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Mobile Dashboard Shortcut -->
                    <a
                        href="{{ $navbarDashboard }}"
                        aria-label="Dashboard"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-[#d8a85a] sm:hidden"
                    >
                        <i data-lucide="layout-dashboard" class="h-[17px] w-[17px]"></i>
                    </a>

                @else

                    <!-- Guest Sign In -->
                    <a
                        href="{{ url('/login') }}"
                        class="hidden items-center justify-center rounded-full bg-[#f4f0e8] px-5 py-2.5 text-xs font-semibold text-[#11110f] transition hover:bg-[#f2d092] sm:inline-flex"
                    >
                        Sign in
                    </a>

                @endauth

                <!-- Mobile Menu Button -->
                <button
                    type="button"
                    id="mobile-menu-button"
                    aria-label="Toggle navigation"
                    aria-expanded="false"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-[#d7cabb] lg:hidden"
                >
                    <i data-lucide="menu" class="h-[17px] w-[17px]"></i>
                </button>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden border-t border-white/10 py-5 lg:hidden">

            <nav class="flex flex-col gap-5">

                <a href="{{ url('/') }}"
                   class="text-sm text-[#d7cabb] hover:text-[#f2d092]">
                    Home
                </a>

                <a href="{{ url('/#discover') }}"
                   class="text-sm text-[#d7cabb] hover:text-[#f2d092]">
                    Discover
                </a>

                <a href="{{ url('/#offers') }}"
                   class="text-sm text-[#d7cabb] hover:text-[#f2d092]">
                    Offers
                </a>

                <a href="{{ url('/#noor-way') }}"
                   class="text-sm text-[#d7cabb] hover:text-[#f2d092]">
                    The NOOR way
                </a>

                @auth
                    <div class="border-t border-white/10 pt-4">
                        <p class="mb-3 text-xs text-[#9d978e]">
                            Signed in as {{ auth()->user()->name }}
                        </p>

                        <a
                            href="{{ $navbarDashboard }}"
                            class="block text-sm text-[#d7cabb] hover:text-[#f2d092]"
                        >
                            Dashboard
                        </a>

                        <a
                            href="{{ url('/') }}"
                            class="mt-4 block text-sm text-[#d7cabb] hover:text-[#f2d092]"
                        >
                            Visit website
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf

                            <button type="submit" class="text-sm text-red-300 hover:text-red-200">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a
                        href="{{ url('/login') }}"
                        class="inline-flex w-fit rounded-full bg-[#f4f0e8] px-5 py-2.5 text-xs font-semibold text-[#11110f]"
                    >
                        Sign in
                    </a>
                @endauth

            </nav>
        </div>

    </div>
</header>

<!-- Mobile Menu Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function () {
                const isExpanded =
                    menuButton.getAttribute('aria-expanded') === 'true';

                mobileMenu.classList.toggle('hidden');
                menuButton.setAttribute('aria-expanded', String(!isExpanded));
            });
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    });
</script>


    <!-- =========================
         PAGE CONTENT
    ========================== -->

    <main>
        @yield('content')
    </main>


    <!-- =========================
         FOOTER
    ========================== -->

    <footer class="border-t border-white/10 bg-[#11110f]">

        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-14">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">

                <!-- Brand -->

                <div>

                    <div class="font-noor-serif text-3xl text-[#f4f0e8]">
                        NOOR
                    </div>

                    <p class="mt-3 text-sm leading-6 text-[#9d978e] max-w-xs">
                        Food, considered.
                        Thoughtfully chosen kitchens,
                        delivered to your door.
                    </p>

                </div>


                <!-- Explore -->

                <div>

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.2em] text-[#d8a85a] mb-5">
                        Explore
                    </p>

                    <div class="space-y-3">

                        <a
                            href="{{ url('/restaurants') }}"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            Discover
                        </a>

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            Offers
                        </a>

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            The NOOR way
                        </a>

                    </div>

                </div>


                <!-- Company -->

                <div>

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.2em] text-[#d8a85a] mb-5">
                        Company
                    </p>

                    <div class="space-y-3">

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            About NOOR
                        </a>

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            Contact
                        </a>

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            Become a partner
                        </a>

                    </div>

                </div>


                <!-- Legal -->

                <div>

                    <p class="font-noor-mono text-[10px] uppercase tracking-[0.2em] text-[#d8a85a] mb-5">
                        Legal
                    </p>

                    <div class="space-y-3">

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            Privacy
                        </a>

                        <a
                            href="#"
                            class="block text-sm text-[#9d978e] hover:text-[#f4f0e8] transition"
                        >
                            Terms
                        </a>

                    </div>

                </div>

            </div>


            <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">

                <p class="font-noor-mono text-[9px] uppercase tracking-[0.15em] text-[#6f6a63]">
                    Made with care
                </p>

                <p class="text-xs text-[#6f6a63]">
                    © {{ date('Y') }} NOOR
                </p>

            </div>

        </div>

    </footer>


    <!-- =========================
         MOBILE MENU SCRIPT
    ========================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const button = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');

            if (button && menu) {

                button.addEventListener('click', function () {

                    menu.classList.toggle('hidden');

                    const icon = button.querySelector('i');

                    if (icon) {

                        const isOpen = !menu.classList.contains('hidden');

                        icon.setAttribute(
                            'data-lucide',
                            isOpen ? 'x' : 'menu'
                        );

                        lucide.createIcons();

                    }

                });

            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

        });

    </script>


    @stack('scripts')

</body>
</html>