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

        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">

            <div class="h-[76px] flex items-center justify-between">

                <!-- Logo -->

                <a
                    href="{{ url('/') }}"
                    class="shrink-0"
                >
                    <div class="font-noor-serif text-2xl tracking-wide text-[#f4f0e8]">
                        NOOR
                    </div>

                    <div class="font-noor-mono text-[8px] uppercase tracking-[0.25em] text-[#9d978e]">
                        Food, considered.
                    </div>
                </a>


                <!-- Desktop Navigation -->

                <nav class="hidden lg:flex items-center gap-8">

                    <a
                        href="{{ url('/') }}"
                        class="text-xs text-[#d7cabb] hover:text-[#f2d092] transition"
                    >
                        Home
                    </a>

                    <a
                        href="{{ url('/restaurants') }}"
                        class="text-xs text-[#d7cabb] hover:text-[#f2d092] transition"
                    >
                        Discover
                    </a>

                    <a
                        href="#"
                        class="text-xs text-[#d7cabb] hover:text-[#f2d092] transition"
                    >
                        Offers
                    </a>

                    <a
                        href="#"
                        class="text-xs text-[#d7cabb] hover:text-[#f2d092] transition"
                    >
                        The NOOR way
                    </a>

                </nav>


                <!-- Right Side -->

                <div class="flex items-center gap-2 sm:gap-3">

                    <!-- Search -->

                    <button
                        type="button"
                        class="hidden sm:flex w-10 h-10 rounded-full border border-white/10 items-center justify-center text-[#d7cabb] hover:text-[#d8a85a] hover:border-[#d8a85a]/40 transition"
                    >
                        <i data-lucide="search" class="w-[17px] h-[17px]"></i>
                    </button>


                    <!-- Bag -->

                    <a
                        href="{{ url('/cart') }}"
                        class="relative w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-[#d7cabb] hover:text-[#d8a85a] hover:border-[#d8a85a]/40 transition"
                    >

                        <i data-lucide="shopping-bag" class="w-[17px] h-[17px]"></i>

                        <span
                            class="absolute -top-1 -right-1 min-w-[17px] h-[17px] px-1 rounded-full bg-[#d8a85a] text-[#11110f] text-[9px] font-bold flex items-center justify-center"
                        >
                            {{ session('cart_count', 0) }}
                        </span>

                    </a>


                    <!-- Sign In -->

                    <a
                        href="{{ url('/login') }}"
                        class="hidden sm:inline-flex items-center justify-center rounded-full bg-[#f4f0e8] px-5 py-2.5 text-xs font-semibold text-[#11110f] hover:bg-[#f2d092] transition"
                    >
                        Sign in
                    </a>


                    <!-- Mobile Menu -->

                    <button
                        type="button"
                        id="mobile-menu-button"
                        class="lg:hidden w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-[#d7cabb]"
                    >
                        <i
                            data-lucide="menu"
                            class="h-[17px] w-[17px]"
                        ></i>
                    </button>

                </div>

            </div>


            <!-- Mobile Menu -->

            <div
                id="mobile-menu"
                class="hidden lg:hidden border-t border-white/10 py-5"
            >

                <nav class="flex flex-col gap-5">

                    <a
                        href="{{ url('/') }}"
                        class="text-sm text-[#d7cabb] hover:text-[#f2d092]"
                    >
                        Home
                    </a>

                    <a
                        href="{{ url('/restaurants') }}"
                        class="text-sm text-[#d7cabb] hover:text-[#f2d092]"
                    >
                        Discover
                    </a>

                    <a
                        href="#"
                        class="text-sm text-[#d7cabb] hover:text-[#f2d092]"
                    >
                        Offers
                    </a>

                    <a
                        href="#"
                        class="text-sm text-[#d7cabb] hover:text-[#f2d092]"
                    >
                        The NOOR way
                    </a>

                    <a
                        href="{{ url('/login') }}"
                        class="inline-flex w-fit rounded-full bg-[#f4f0e8] px-5 py-2.5 text-xs font-semibold text-[#11110f]"
                    >
                        Sign in
                    </a>

                </nav>

            </div>

        </div>

    </header>


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