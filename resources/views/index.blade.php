<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8"/>

    <meta name="viewport"
          content="width=device-width,initial-scale=1.0"/>

    <meta
        name="description"
        content="NOOR — a more considered way to order food."
    />

    <title>NOOR — Food, considered.</title>


    <!-- FONTS -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com"/>

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin/>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap"
        rel="stylesheet"
    />


    <!-- TAILWIND -->

    <script src="https://cdn.tailwindcss.com"></script>

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        night: '#11110f',
                        night2: '#1a1916',
                        cream: '#f4f0e8',
                        sand: '#d7cabb',
                        gold: '#d8a85a',
                        gold2: '#f2d092',
                        muted: '#9d978e',
                        whiteNoor: '#fbfaf6',
                        greenNoor: '#9bbf8d'

                    },

                    fontFamily: {

                        sansNoor: [
                            'Manrope',
                            'Arial',
                            'sans-serif'
                        ],

                        serifNoor: [
                            'Playfair Display',
                            'Georgia',
                            'serif'
                        ],

                        monoNoor: [
                            'DM Mono',
                            'monospace'
                        ]

                    }

                }

            }

        };

    </script>


    <!-- LUCIDE -->

    <script src="https://unpkg.com/lucide@latest"></script>

</head>


<body
    class="m-0
           overflow-x-hidden
           bg-night
           text-whiteNoor
           font-sansNoor
           selection:bg-gold
           selection:text-night">


<!-- ========================================================= -->
<!-- NAVBAR -->
<!-- ========================================================= -->

<header
    class="fixed
           left-0
           right-0
           top-0
           z-30
           h-[86px]
           border-b
           border-white/[.055]
           bg-[#11110fbb]
           backdrop-blur-[18px]
           max-[680px]:h-[72px]">


    <div
        class="mx-auto
               flex
               h-full
               w-[min(1240px,calc(100%-48px))]
               items-center
               justify-between
               max-[680px]:w-[calc(100%-30px)]">


        <!-- LOGO -->

        <a
            href="#top"
            class="flex
                   items-center
                   gap-[11px]
                   text-[17px]
                   font-extrabold
                   tracking-[.24em]">

            <span
                class="relative
                       grid
                       h-[27px]
                       w-[27px]
                       place-items-center
                       rounded-full
                       border
                       border-gold

                       after:absolute
                       after:h-[7px]
                       after:w-[7px]
                       after:rounded-full
                       after:bg-gold
                       after:shadow-[0_0_0_5px_#d8a85a20]">
            </span>

            NOOR

        </a>


        <!-- DESKTOP NAV -->

        <nav
            class="flex
                   gap-8
                   text-[11px]
                   tracking-[.06em]
                   text-[#c8c1b7]
                   max-[680px]:hidden">

            <a
                href="#top"
                class="relative
                       py-[9px]
                       text-white

                       after:absolute
                       after:bottom-0
                       after:left-0
                       after:right-0
                       after:h-px
                       after:bg-gold">

                Home

            </a>


            <a
                href="#discover"
                class="py-[9px]
                       hover:text-white">

                Discover

            </a>


            <a
                href="#offers"
                class="py-[9px]
                       hover:text-white">

                Offers

            </a>


            <a
                href="#how"
                class="py-[9px]
                       hover:text-white">

                The NOOR way

            </a>

        </nav>


        <!-- RIGHT ACTIONS -->

        <div
            class="flex
                   items-center
                   gap-[10px]">


            <!-- SEARCH -->

            <button
                id="navSearch"
                type="button"
                class="grid
                       h-[38px]
                       w-[38px]
                       place-items-center
                       rounded-full
                       border
                       border-white/[.094]
                       bg-transparent
                       transition
                       hover:-translate-y-0.5
                       hover:border-white/[.227]
                       hover:bg-white/[.075]">

                <i
                    data-lucide="search"
                    class="w-4">
                </i>

            </button>


            <!-- BAG -->

            <button
                type="button"
                class="relative
                       grid
                       h-[38px]
                       w-[38px]
                       place-items-center
                       rounded-full
                       border
                       border-white/[.094]
                       bg-transparent">

                <i
                    data-lucide="shopping-bag"
                    class="w-4">
                </i>


                <span
                    id="bagCount"
                    class="absolute
                           -right-[3px]
                           -top-[3px]
                           grid
                           h-4
                           w-4
                           place-items-center
                           rounded-full
                           bg-gold
                           text-[8px]
                           font-extrabold
                           text-night">

                    2

                </span>

            </button>


            <!-- ================================================= -->
            <!-- AUTH -->
            <!-- ================================================= -->

            <div
                class="hidden
                       items-center
                       gap-2
                       sm:flex">


                @auth

                    <!-- LOGGED IN -->

                    <div class="relative group">


                        <!-- PROFILE BUTTON -->

                        <button
                            type="button"
                            class="flex
                                   items-center
                                   gap-2
                                   rounded-xl
                                   px-2
                                   py-1.5
                                   transition
                                   hover:bg-white/[.05]">


                            <!-- AVATAR -->

                            <div
                                class="grid
                                       h-[38px]
                                       w-[38px]
                                       place-items-center
                                       rounded-full
                                       bg-gold
                                       text-[12px]
                                       font-extrabold
                                       text-night">

                                {{ strtoupper(
                                    substr(auth()->user()->name, 0, 1)
                                ) }}

                            </div>


                            <!-- USER NAME -->

                            <div
                                class="hidden
                                       text-left
                                       lg:block">

                                <p
                                    class="text-[11px]
                                           font-extrabold
                                           leading-4
                                           text-white">

                                    {{ auth()->user()->name }}

                                </p>


                                <p
                                    class="text-[9px]
                                           text-[#858078]">

                                    My Account

                                </p>

                            </div>


                            <!-- CHEVRON -->

                            <i
                                data-lucide="chevron-down"
                                class="w-[13px]
                                       text-[#858078]">
                            </i>

                        </button>


                        <!-- ================================================= -->
                        <!-- PROFILE DROPDOWN -->
                        <!-- ================================================= -->

                        <div
                            class="absolute
                                   right-0
                                   top-full
                                   mt-2
                                   w-[220px]
                                   overflow-hidden
                                   rounded-[18px]
                                   border
                                   border-white/[.08]
                                   bg-[#1b1916]
                                   shadow-[0_20px_60px_#000b]

                                   opacity-0
                                   invisible
                                   translate-y-1

                                   group-hover:visible
                                   group-hover:opacity-100
                                   group-hover:translate-y-0

                                   transition-all
                                   duration-200">


                            <!-- MY PROFILE -->

                            <a
                                href="{{ route('dashboard') }}"
                                class="flex
                                       items-center
                                       gap-3
                                       px-4
                                       py-3
                                       text-[11px]
                                       text-[#c8c1b7]
                                       transition
                                       hover:bg-white/[.05]
                                       hover:text-white">

                                <i
                                    data-lucide="user"
                                    class="w-[16px]">
                                </i>


                                <span>
                                    My Profile
                                </span>

                            </a>


                            <!-- DASHBOARD -->

                            <a 
                                href="{{ route('dashboard') }}" 
                                class="flex 
                                    items-center 
                                    gap-3 
                                    px-4 
                                    py-3 
                                    text-[11px] 
                                    text-[#c8c1b7] 
                                    transition 
                                    hover:bg-white/[.05] 
                                    hover:text-white">

                                <i 
                                    data-lucide="layout-dashboard" 
                                    class="w-[16px]">
                                </i>

                                <span>
                                    Dashboard
                                </span>

                            </a>


                            <!-- DIVIDER -->

                            <div
                                class="border-t
                                       border-white/[.07]">
                            </div>


                            <!-- LOGOUT -->

                            <form
                                action="{{ route('logout') }}"
                                method="POST">

                                @csrf


                                <button
                                    type="submit"
                                    class="flex
                                           w-full
                                           items-center
                                           gap-3
                                           px-4
                                           py-3
                                           text-[11px]
                                           text-red-400
                                           transition
                                           hover:bg-red-500/[.08]">

                                    <i
                                        data-lucide="log-out"
                                        class="w-[16px]">
                                    </i>


                                    <span>
                                        Logout
                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>


                @else

                    <!-- ================================================= -->
                    <!-- GUEST -->
                    <!-- ================================================= -->


                    <!-- SIGN IN -->

                    <button
                        data-open-modal
                        type="button"
                        class="rounded-full
                               border
                               border-white/[.21]
                               bg-transparent
                               px-[17px]
                               py-[11px]
                               text-[10px]
                               font-extrabold
                               tracking-[.04em]
                               transition
                               hover:border-gold
                               hover:text-gold2">

                        Login

                    </button>


                @endauth


            </div>


            <!-- MOBILE BUTTON -->

            <button
                id="mobileBtn"
                type="button"
                class="hidden
                       h-[38px]
                       w-[38px]
                       place-items-center
                       rounded-full
                       border
                       border-white/[.094]
                       bg-transparent
                       max-[680px]:grid">

                <i
                    data-lucide="menu"
                    class="w-[17px]">
                </i>

            </button>


        </div>

    </div>


    <!-- ========================================================= -->
    <!-- MOBILE MENU -->
    <!-- ========================================================= -->

    <div
        id="mobileMenu"
        class="hidden
               border-t
               border-white/[.07]
               bg-night
               px-[17px]
               py-[17px]">


        <div
            class="mx-auto
                   grid
                   w-[calc(100%-30px)]
                   gap-[15px]
                   text-[11px]
                   text-[#c8c1b7]">


            <a href="#top">
                Home
            </a>


            <a href="#discover">
                Discover
            </a>


            <a href="#offers">
                Offers
            </a>


            <a href="#how">
                The NOOR way
            </a>


            @auth

                <!-- MOBILE DASHBOARD -->

                <a
                    href="{{ url('/customer/dashboard') }}"
                    class="flex
                           items-center
                           gap-2
                           text-gold2">

                    <i
                        data-lucide="layout-dashboard"
                        class="w-[15px]">
                    </i>

                    Dashboard

                </a>


                <!-- MOBILE PROFILE -->

                <a
                    href="{{ url('/customer/dashboard') }}"
                    class="flex
                           items-center
                           gap-2">

                    <i
                        data-lucide="user"
                        class="w-[15px]">
                    </i>

                    My Profile

                </a>


                <!-- MOBILE LOGOUT -->

                <form
                    action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="flex
                               w-full
                               items-center
                               gap-2
                               text-left
                               text-red-400">

                        <i
                            data-lucide="log-out"
                            class="w-[15px]">
                        </i>

                        Logout

                    </button>

                </form>


            @else

                <!-- MOBILE SIGN IN -->

                <button
                    data-open-modal
                    type="button"
                    class="rounded-full
                           border
                           border-white/[.21]
                           px-[17px]
                           py-[11px]
                           text-[10px]
                           font-extrabold">

                    Sign in

                </button>


                <!-- MOBILE JOIN -->

                <button
                    data-open-modal
                    data-open-join
                    type="button"
                    class="rounded-full
                           bg-gold
                           px-[17px]
                           py-[11px]
                           text-[10px]
                           font-extrabold
                           text-night">

                    Join

                </button>

            @endauth


        </div>

    </div>

</header>


<!-- ========================================================= -->
<!-- SEARCH OVERLAY -->
<!-- ========================================================= -->

<div
    id="searchOverlay"
    class="fixed
           inset-0
           z-[80]
           hidden
           items-start
           justify-center
           bg-black/[.67]
           px-5
           pb-[30px]
           pt-[105px]
           backdrop-blur-[14px]
           max-[680px]:px-3
           max-[680px]:pt-[86px]">


    <div
        class="w-[min(720px,100%)]
               overflow-hidden
               rounded-[22px]
               border
               border-white/[.14]
               bg-[#1b1916]
               shadow-[0_25px_80px_#000b]">


        <div
            class="flex
                   items-center
                   gap-3
                   border-b
                   border-white/[.07]
                   px-5
                   py-[18px]">


            <i
                data-lucide="search"
                class="w-[18px]
                       text-gold2">
            </i>


            <input
                id="searchInput"
                type="search"
                placeholder="Search biryani, grill, noodles, restaurant..."
                class="min-w-0
                       flex-1
                       bg-transparent
                       text-[14px]
                       text-white
                       outline-none
                       placeholder:text-[#817b73]"
            />


            <button
                id="searchClose"
                type="button"
                class="grid
                       h-[34px]
                       w-[34px]
                       place-items-center
                       rounded-full
                       border
                       border-white/[.125]">

                <i data-lucide="x"></i>

            </button>

        </div>


        <div
            class="px-5
                   py-[13px]
                   text-[10px]
                   text-[#777169]">

            Press

            <kbd
                class="rounded-[5px]
                       border
                       border-white/[.125]
                       px-[6px]
                       py-[3px]">

                Enter

            </kbd>

            to search ·

            <kbd
                class="rounded-[5px]
                       border
                       border-white/[.125]
                       px-[6px]
                       py-[3px]">

                Esc

            </kbd>

            to close

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- MAIN -->
<!-- ========================================================= -->

<main id="top">


<!-- ========================================================= -->
<!-- HERO -->
<!-- ========================================================= -->

<section
    class="relative
           flex
           min-h-[760px]
           items-center
           overflow-hidden
           pt-[86px]
           max-[680px]:min-h-[930px]
           max-[680px]:pt-[72px]">


    <div
        class="mx-auto
               grid
               w-[min(1240px,calc(100%-48px))]
               grid-cols-2
               items-center
               gap-[50px]
               max-[950px]:gap-5
               max-[680px]:w-[calc(100%-30px)]
               max-[680px]:grid-cols-1">


        <div
            class="relative
                   z-[2]
                   py-[72px]
                   max-[680px]:pt-[55px]">


            <div
                class="flex
                       items-center
                       gap-[10px]
                       text-[10px]
                       font-extrabold
                       uppercase
                       tracking-[.2em]
                       text-gold2">

                <span
                    class="h-px
                           w-[27px]
                           bg-gold">
                </span>

                Food, considered

            </div>


            <h1
                class="my-[25px]
                       max-w-[690px]
                       font-serifNoor
                       text-[clamp(58px,8vw,115px)]
                       font-medium
                       leading-[.86]
                       tracking-[-.075em]
                       max-[950px]:text-[75px]
                       max-[680px]:text-[67px]">

                The city’s best<br/>

                <i class="text-gold2">
                    kept warm.
                </i>

            </h1>


            <p
                class="max-w-[430px]
                       text-[14px]
                       leading-[1.8]
                       text-[#b6afa6]">

                NOOR brings the right dish from the right kitchen to your door.
                Curated for taste, timed for your evening, and delivered without the noise.

            </p>


            <div
                class="mt-[33px]
                       flex
                       items-center
                       gap-[18px]">


                <a
                    href="#discover"
                    class="flex
                           items-center
                           gap-2
                           rounded-full
                           bg-gold
                           px-5
                           py-[14px]
                           text-[11px]
                           font-extrabold
                           text-night
                           transition
                           hover:-translate-y-[3px]
                           hover:bg-gold2">

                    Explore the menu

                    <i data-lucide="arrow-up-right"></i>

                </a>


                <button
                    id="heroSearch"
                    type="button"
                    class="border-0
                           border-b
                           border-white/[.32]
                           bg-transparent
                           py-2
                           text-[11px]">

                    Search the menu

                </button>

            </div>


            <div
                class="mt-[26px]
                       flex
                       items-center
                       gap-[9px]
                       text-[10px]
                       text-[#858078]">

                <i
                    data-lucide="sparkles"
                    class="w-[14px]
                           text-greenNoor">
                </i>

                Handpicked kitchens in Dhanmondi & beyond

            </div>

        </div>


        <div
            class="relative
                   grid
                   h-[610px]
                   place-items-center
                   max-[950px]:h-[520px]
                   max-[680px]:h-[370px]">


            <div
                class="absolute
                       h-[580px]
                       w-[580px]
                       rotate-[-20deg]
                       rounded-full
                       border
                       border-gold/[.25]
                       max-[680px]:h-[340px]
                       max-[680px]:w-[340px]">
            </div>


            <div
                class="relative
                       h-[540px]
                       w-[min(470px,90%)]
                       overflow-hidden
                       rounded-[235px_235px_24px_24px]
                       bg-[#2a251e]
                       shadow-[0_30px_100px_#0009]
                       max-[950px]:h-[470px]
                       max-[680px]:h-[360px]
                       max-[680px]:w-[270px]">


                <img
                    class="h-full
                           w-full
                           object-cover
                           saturate-[.86]"
                    src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1100&q=88"
                    alt="A beautifully plated bowl of food"
                />


                <div
                    class="absolute
                           bottom-7
                           left-[27px]
                           right-[27px]
                           flex
                           items-end
                           justify-between">


                    <div>

                        <b
                            class="font-serifNoor
                                   text-[27px]
                                   font-medium">

                            Tonight’s table

                        </b>

                        <span
                            class="text-[10px]
                                   text-white/[.7]">

                            · seasonal, generous, yours

                        </span>

                    </div>


                    <span
                        class="font-monoNoor
                               text-[10px]">

                        01 / 06

                    </span>

                </div>

            </div>


            <div
                class="absolute
                       right-[10px]
                       top-[72px]
                       rounded-2xl
                       border
                       border-white/[.11]
                       bg-[#221f1be8]
                       p-[14px]
                       backdrop-blur-[16px]
                       max-[680px]:right-0
                       max-[680px]:top-[10px]">


                <small
                    class="block
                           text-[9px]
                           uppercase
                           tracking-[.12em]
                           text-[#9c9489]">

                    Curated today

                </small>


                <strong
                    class="mt-2
                           block
                           font-serifNoor
                           text-[17px]">

                    18 kitchens

                </strong>


                <div
                    class="mt-[5px]
                           text-[11px]
                           text-gold2">

                    ★★★★★

                </div>

            </div>


            <div
                class="absolute
                       bottom-[70px]
                       left-0
                       rounded-2xl
                       border
                       border-white/[.11]
                       bg-[#221f1be8]
                       p-[14px]
                       backdrop-blur-[16px]
                       max-[680px]:bottom-[15px]">


                <small
                    class="block
                           text-[9px]
                           uppercase
                           tracking-[.12em]
                           text-[#9c9489]">

                    Arrives in

                </small>


                <strong
                    class="mt-2
                           block
                           font-serifNoor
                           text-[17px]">

                    28–35 min

                </strong>

            </div>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- CATEGORIES -->
<!-- ========================================================= -->

<section
    class="border-t
           border-white/[.055]
           py-[105px]
           max-[680px]:py-[75px]">


    <div
        class="mx-auto
               w-[min(1240px,calc(100%-48px))]
               max-[680px]:w-[calc(100%-30px)]">


        <div
            class="mb-8
                   flex
                   items-end
                   justify-between
                   max-[680px]:block">


            <div>

                <div
                    class="flex
                           items-center
                           gap-[10px]
                           text-[10px]
                           font-extrabold
                           uppercase
                           tracking-[.2em]
                           text-gold2">

                    <span
                        class="h-px
                               w-[27px]
                               bg-gold">
                    </span>

                    A little direction

                </div>


                <h2
                    class="mt-3
                           font-serifNoor
                           text-[clamp(35px,5vw,60px)]
                           leading-[.95]
                           tracking-[-.06em]">

                    What are you<br/>
                    feeling tonight?

                </h2>

            </div>


            <p
                class="max-w-[275px]
                       text-[12px]
                       leading-[1.7]
                       text-[#908a82]
                       max-[680px]:mt-[15px]">

                Not every craving needs a category.
                Start wherever the mood takes you.

            </p>

        </div>


        <div
            class="flex
                   gap-3
                   overflow-auto
                   pb-2
                   [scrollbar-width:none]">


            <!-- CATEGORY 1 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1563379091339-03246963d51a?auto=format&fit=crop&w=700&q=85"
                        alt="Biryani">

                </div>

                <span class="text-[11px]">
                    Biryani
                </span>

            </a>


            <!-- CATEGORY 2 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=250&q=80"
                        alt="Small plates">

                </div>

                <span class="text-[11px]">
                    Small plates
                </span>

            </a>


            <!-- CATEGORY 3 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=250&q=80"
                        alt="Charcoal">

                </div>

                <span class="text-[11px]">
                    Charcoal
                </span>

            </a>


            <!-- CATEGORY 4 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=250&q=80"
                        alt="Noodles">

                </div>

                <span class="text-[11px]">
                    Noodles
                </span>

            </a>


            <!-- CATEGORY 5 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1601303516534-6d6d7c4f2b55?auto=format&fit=crop&w=250&q=80"
                        alt="Sweet things">

                </div>

                <span class="text-[11px]">
                    Sweet things
                </span>

            </a>


            <!-- CATEGORY 6 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=250&q=80"
                        alt="Morning">

                </div>

                <span class="text-[11px]">
                    Morning
                </span>

            </a>


            <!-- CATEGORY 7 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=250&q=80"
                        alt="Green">

                </div>

                <span class="text-[11px]">
                    Green
                </span>

            </a>


            <!-- CATEGORY 8 -->

            <a
                href="#discover"
                class="flex
                       h-[155px]
                       min-w-[137px]
                       shrink-0
                       flex-col
                       items-center
                       justify-center
                       gap-[13px]
                       rounded-[20px]
                       border
                       border-white/[.075]
                       bg-white/[.02]
                       transition
                       hover:-translate-y-[6px]
                       hover:border-gold/[.57]
                       max-[680px]:h-[140px]
                       max-[680px]:min-w-[122px]">

                <div
                    class="h-[72px]
                           w-[72px]
                           overflow-hidden
                           rounded-full">

                    <img
                        class="h-full
                               w-full
                               object-cover"
                        src="https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=250&q=80"
                        alt="Late night">

                </div>

                <span class="text-[11px]">
                    Late night
                </span>

            </a>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- RESTAURANTS -->
<!-- ========================================================= -->

<section
    id="discover"
    class="border-t
           border-white/[.055]
           py-[105px]
           max-[680px]:py-[75px]">


    <div
        class="mx-auto
               w-[min(1240px,calc(100%-48px))]
               max-[680px]:w-[calc(100%-30px)]">


        <div
            class="mb-8
                   flex
                   items-end
                   justify-between
                   max-[680px]:block">


            <div>

                <div
                    class="flex
                           items-center
                           gap-[10px]
                           text-[10px]
                           font-extrabold
                           uppercase
                           tracking-[.2em]
                           text-gold2">

                    <span
                        class="h-px
                               w-[27px]
                               bg-gold">
                    </span>

                    Worth knowing

                </div>


                <h2
                    class="mt-3
                           font-serifNoor
                           text-[clamp(35px,5vw,60px)]
                           leading-[.95]
                           tracking-[-.06em]">

                    Good places<br/>
                    to begin.

                </h2>

            </div>


            <a
                href="#discover"
                class="border-b
                       border-white/[.28]
                       pb-2
                       text-[10px]
                       uppercase
                       tracking-[.1em]
                       hover:border-gold
                       hover:text-gold2">

                View all places

            </a>

        </div>


        <div
            class="grid
                   grid-cols-4
                   gap-[15px]
                   max-[950px]:grid-cols-2
                   max-[680px]:grid-cols-2
                   max-[680px]:gap-[10px]">


            <!-- RESTAURANT 1 -->

            <article
                data-search="kacchi bhai kacchi bangla"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]
                       hover:border-gold/[.4]">


                <div
                    class="relative
                           h-[210px]
                           overflow-hidden
                           max-[680px]:h-[150px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1563379091339-03246963d51a?auto=format&fit=crop&w=1000&q=88"
                        alt="Kacchi Bhai"
                    />


                    <button
                        data-fav
                        type="button"
                        class="absolute
                               right-[13px]
                               top-[13px]
                               grid
                               h-[33px]
                               w-[33px]
                               place-items-center
                               rounded-full
                               border
                               border-white/[.19]
                               bg-[#11110fb8]">

                        <i
                            data-lucide="heart"
                            class="w-[15px]">
                        </i>

                    </button>

                </div>


                <div
                    class="p-4
                           max-[680px]:p-3">

                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[19px]">

                        Kacchi Bhai

                    </h3>


                    <div
                        class="flex
                               gap-2
                               text-[10px]
                               text-[#918a80]">

                        <b class="text-gold2">
                            ★ 4.9
                        </b>

                        <span>•</span>

                        <span>
                            25–35 min
                        </span>

                    </div>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between
                               border-t
                               border-white/[.06]
                               pt-3
                               text-[9px]">

                        <span>
                            Kacchi · Bangla
                        </span>

                        <em
                            class="font-extrabold
                                   not-italic
                                   text-greenNoor">

                            ৳30 delivery

                        </em>

                    </div>

                </div>

            </article>


            <!-- RESTAURANT 2 -->

            <article
                data-search="the grill room charcoal grill"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]
                       hover:border-gold/[.4]">


                <div
                    class="relative
                           h-[210px]
                           overflow-hidden
                           max-[680px]:h-[150px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=900&q=85"
                        alt="The Grill Room"
                    />


                    <button
                        data-fav
                        type="button"
                        class="absolute
                               right-[13px]
                               top-[13px]
                               grid
                               h-[33px]
                               w-[33px]
                               place-items-center
                               rounded-full
                               border
                               border-white/[.19]
                               bg-[#11110fb8]">

                        <i
                            data-lucide="heart"
                            class="w-[15px]">
                        </i>

                    </button>

                </div>


                <div
                    class="p-4
                           max-[680px]:p-3">

                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[19px]">

                        The Grill Room

                    </h3>


                    <div
                        class="flex
                               gap-2
                               text-[10px]
                               text-[#918a80]">

                        <b class="text-gold2">
                            ★ 4.8
                        </b>

                        <span>•</span>

                        <span>
                            30–40 min
                        </span>

                    </div>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between
                               border-t
                               border-white/[.06]
                               pt-3
                               text-[9px]">

                        <span>
                            Charcoal · Grill
                        </span>

                        <em
                            class="font-extrabold
                                   not-italic
                                   text-greenNoor">

                            ৳30 delivery

                        </em>

                    </div>

                </div>

            </article>


            <!-- RESTAURANT 3 -->

            <article
                data-search="rong kitchen chinese asian noodles"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]
                       hover:border-gold/[.4]">


                <div
                    class="relative
                           h-[210px]
                           overflow-hidden
                           max-[680px]:h-[150px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=900&q=85"
                        alt="Rong Kitchen"
                    />


                    <button
                        data-fav
                        type="button"
                        class="absolute
                               right-[13px]
                               top-[13px]
                               grid
                               h-[33px]
                               w-[33px]
                               place-items-center
                               rounded-full
                               border
                               border-white/[.19]
                               bg-[#11110fb8]">

                        <i
                            data-lucide="heart"
                            class="w-[15px]">
                        </i>

                    </button>

                </div>


                <div
                    class="p-4
                           max-[680px]:p-3">

                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[19px]">

                        Rong Kitchen

                    </h3>


                    <div
                        class="flex
                               gap-2
                               text-[10px]
                               text-[#918a80]">

                        <b class="text-gold2">
                            ★ 4.7
                        </b>

                        <span>•</span>

                        <span>
                            25–35 min
                        </span>

                    </div>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between
                               border-t
                               border-white/[.06]
                               pt-3
                               text-[9px]">

                        <span>
                            Chinese · Asian
                        </span>

                        <em
                            class="font-extrabold
                                   not-italic
                                   text-greenNoor">

                            ৳25 delivery

                        </em>

                    </div>

                </div>

            </article>


            <!-- RESTAURANT 4 -->

            <article
                data-search="bhorta ghor bangla homemade"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]
                       hover:border-gold/[.4]">


                <div
                    class="relative
                           h-[210px]
                           overflow-hidden
                           max-[680px]:h-[150px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=900&q=85"
                        alt="Bhorta Ghor"
                    />


                    <button
                        data-fav
                        type="button"
                        class="absolute
                               right-[13px]
                               top-[13px]
                               grid
                               h-[33px]
                               w-[33px]
                               place-items-center
                               rounded-full
                               border
                               border-white/[.19]
                               bg-[#11110fb8]">

                        <i
                            data-lucide="heart"
                            class="w-[15px]">
                        </i>

                    </button>

                </div>


                <div
                    class="p-4
                           max-[680px]:p-3">

                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[19px]">

                        Bhorta Ghor

                    </h3>


                    <div
                        class="flex
                               gap-2
                               text-[10px]
                               text-[#918a80]">

                        <b class="text-gold2">
                            ★ 4.8
                        </b>

                        <span>•</span>

                        <span>
                            20–30 min
                        </span>

                    </div>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between
                               border-t
                               border-white/[.06]
                               pt-3
                               text-[9px]">

                        <span>
                            Bangla · Homemade
                        </span>

                        <em
                            class="font-extrabold
                                   not-italic
                                   text-greenNoor">

                            ৳20 delivery

                        </em>

                    </div>

                </div>

            </article>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- FOOD -->
<!-- ========================================================= -->

<section
    class="border-t
           border-white/[.055]
           py-[105px]
           max-[680px]:py-[75px]">


    <div
        class="mx-auto
               w-[min(1240px,calc(100%-48px))]
               max-[680px]:w-[calc(100%-30px)]">


        <div
            class="mb-8
                   flex
                   items-end
                   justify-between
                   max-[680px]:block">


            <div>

                <div
                    class="flex
                           items-center
                           gap-[10px]
                           text-[10px]
                           font-extrabold
                           uppercase
                           tracking-[.2em]
                           text-gold2">

                    <span
                        class="h-px
                               w-[27px]
                               bg-gold">
                    </span>

                    Most loved this week

                </div>


                <h2
                    class="mt-3
                           font-serifNoor
                           text-[clamp(35px,5vw,60px)]
                           leading-[.95]
                           tracking-[-.06em]">

                    The dishes<br/>
                    people return for.

                </h2>

            </div>


            <a
                href="#discover"
                class="border-b
                       border-white/[.28]
                       pb-2
                       text-[10px]
                       uppercase
                       tracking-[.1em]">

                See the full menu

            </a>

        </div>


        <div
            class="grid
                   grid-cols-4
                   gap-[15px]
                   max-[950px]:grid-cols-2
                   max-[680px]:grid-cols-2
                   max-[680px]:gap-[10px]">


            <!-- FOOD 1 -->

            <article
                data-search="kacchi biryani notun bazar biryani house"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]">


                <div
                    class="relative
                           h-[240px]
                           overflow-hidden
                           max-[680px]:h-[170px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1563379091339-03246963d51a?auto=format&fit=crop&w=1000&q=88"
                        alt="Kacchi Biryani"
                    />


                    <span
                        class="absolute
                               left-[13px]
                               top-[13px]
                               rounded-[6px]
                               bg-gold
                               px-2
                               py-[6px]
                               text-[9px]
                               font-extrabold
                               text-night">

                        EDITOR’S PICK

                    </span>


                    <button
                        data-add
                        type="button"
                        class="absolute
                               bottom-3
                               right-3
                               grid
                               h-9
                               w-9
                               place-items-center
                               rounded-[11px]
                               bg-white
                               text-night
                               shadow-[0_7px_18px_#0004]">

                        <i data-lucide="plus"></i>

                    </button>

                </div>


                <div class="p-4">

                    <div
                        class="text-[10px]
                               text-[#918a80]">

                        Notun Bazar Biryani House

                    </div>


                    <h3
                        class="mt-2
                               font-serifNoor
                               text-[19px]">

                        Kacchi Biryani

                    </h3>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between">

                        <span class="font-extrabold">
                            ৳320
                        </span>

                        <span class="text-[10px]">
                            ★ 4.9
                        </span>

                    </div>

                </div>

            </article>


            <!-- FOOD 2 -->

            <article
                data-search="fuchka plate street food"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]">


                <div
                    class="relative
                           h-[240px]
                           overflow-hidden
                           max-[680px]:h-[170px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?auto=format&fit=crop&w=900&q=85"
                        alt="Fuchka Plate"
                    />


                    <button
                        data-add
                        type="button"
                        class="absolute
                               bottom-3
                               right-3
                               grid
                               h-9
                               w-9
                               place-items-center
                               rounded-[11px]
                               bg-white
                               text-night">

                        <i data-lucide="plus"></i>

                    </button>

                </div>


                <div class="p-4">

                    <div
                        class="text-[10px]
                               text-[#918a80]">

                        Street Food

                    </div>


                    <h3
                        class="mt-2
                               font-serifNoor
                               text-[19px]">

                        Fuchka Plate

                    </h3>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between">

                        <span class="font-extrabold">
                            ৳80
                        </span>

                        <span class="text-[10px]">
                            ★ 4.8
                        </span>

                    </div>

                </div>

            </article>


            <!-- FOOD 3 -->

            <article
                data-search="chui jhal chicken bhorta ghor"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]">


                <div
                    class="relative
                           h-[240px]
                           overflow-hidden
                           max-[680px]:h-[170px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1598103442097-8b74394b95c6?auto=format&fit=crop&w=900&q=85"
                        alt="Chui Jhal Chicken"
                    />


                    <button
                        data-add
                        type="button"
                        class="absolute
                               bottom-3
                               right-3
                               grid
                               h-9
                               w-9
                               place-items-center
                               rounded-[11px]
                               bg-white
                               text-night">

                        <i data-lucide="plus"></i>

                    </button>

                </div>


                <div class="p-4">

                    <div
                        class="text-[10px]
                               text-[#918a80]">

                        Bhorta Ghor

                    </div>


                    <h3
                        class="mt-2
                               font-serifNoor
                               text-[19px]">

                        Chui Jhal Chicken

                    </h3>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between">

                        <span class="font-extrabold">
                            ৳240
                        </span>

                        <span class="text-[10px]">
                            ★ 4.7
                        </span>

                    </div>

                </div>

            </article>


            <!-- FOOD 4 -->

            <article
                data-search="roshogolla sweet corner sweet"
                class="group
                       overflow-hidden
                       rounded-[22px]
                       border
                       border-white/[.075]
                       bg-[#191815]
                       transition
                       duration-300
                       hover:-translate-y-[7px]">


                <div
                    class="relative
                           h-[240px]
                           overflow-hidden
                           max-[680px]:h-[170px]">

                    <img
                        class="h-full
                               w-full
                               object-cover
                               transition
                               duration-500
                               group-hover:scale-[1.07]"
                        src="https://images.unsplash.com/photo-1601303516534-6d6d7c4f2b55?auto=format&fit=crop&w=900&q=85"
                        alt="Roshogolla"
                    />


                    <span
                        class="absolute
                               left-[13px]
                               top-[13px]
                               rounded-[6px]
                               bg-gold
                               px-2
                               py-[6px]
                               text-[9px]
                               font-extrabold
                               text-night">

                        NEW

                    </span>


                    <button
                        data-add
                        type="button"
                        class="absolute
                               bottom-3
                               right-3
                               grid
                               h-9
                               w-9
                               place-items-center
                               rounded-[11px]
                               bg-white
                               text-night">

                        <i data-lucide="plus"></i>

                    </button>

                </div>


                <div class="p-4">

                    <div
                        class="text-[10px]
                               text-[#918a80]">

                        Sweet Corner

                    </div>


                    <h3
                        class="mt-2
                               font-serifNoor
                               text-[19px]">

                        Roshogolla

                    </h3>


                    <div
                        class="mt-[14px]
                               flex
                               justify-between">

                        <span class="font-extrabold">
                            ৳120
                        </span>

                        <span class="text-[10px]">
                            ★ 4.9
                        </span>

                    </div>

                </div>

            </article>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- FEATURE -->
<!-- ========================================================= -->

<section
    class="border-t
           border-white/[.055]
           py-[105px]
           max-[680px]:py-[75px]">


    <div
        class="mx-auto
               w-[min(1240px,calc(100%-48px))]
               max-[680px]:w-[calc(100%-30px)]">


        <div
            class="grid
                   grid-cols-[.8fr_1.2fr]
                   items-center
                   gap-[60px]
                   rounded-[30px]
                   bg-cream
                   p-[58px]
                   text-night
                   max-[950px]:p-[35px]
                   max-[680px]:grid-cols-1
                   max-[680px]:p-[26px]">


            <div>

                <div
                    class="flex
                           items-center
                           gap-[10px]
                           text-[10px]
                           font-extrabold
                           uppercase
                           tracking-[.2em]
                           text-[#9d6c25]">

                    <span
                        class="h-px
                               w-[27px]
                               bg-[#9d6c25]">
                    </span>

                    The NOOR edit

                </div>


                <h2
                    class="my-[15px]
                           font-serifNoor
                           text-[clamp(40px,5vw,67px)]
                           leading-[.92]
                           tracking-[-.07em]">

                    A softer<br/>
                    kind of fast food.

                </h2>


                <p
                    class="max-w-[330px]
                           text-[12px]
                           leading-[1.7]
                           text-[#6f675d]">

                    We choose kitchens for how they make you feel,
                    not just how quickly they can fill a bag.
                    Every week, our editors share the meals worth making an evening around.

                </p>


                <a
                    href="#discover"
                    class="mt-4
                           inline-flex
                           items-center
                           gap-2
                           rounded-full
                           bg-gold
                           px-5
                           py-[14px]
                           text-[11px]
                           font-extrabold
                           text-night">

                    Read the edit

                    <i data-lucide="arrow-up-right"></i>

                </a>

            </div>


            <div
                class="relative
                       h-[330px]
                       overflow-hidden
                       rounded-[22px]
                       max-[680px]:h-[250px]">


                <img
                    class="h-full
                           w-full
                           object-cover"
                    src="https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=1000&q=88"
                    alt="A warm dining table"
                />


                <div
                    class="absolute
                           bottom-5
                           left-5
                           rounded-[15px]
                           bg-[#f4f0e8e8]
                           px-4
                           py-[14px]">


                    <b
                        class="font-serifNoor
                               text-[21px]">

                        Issue no. 04

                    </b>


                    <span
                        class="mt-[3px]
                               block
                               text-[9px]
                               text-[#756e65]">

                        Dhaka after dark · 12 tables

                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- OFFERS -->
<!-- ========================================================= -->

<section
    id="offers"
    class="border-t
           border-white/[.055]
           py-[105px]
           max-[680px]:py-[75px]">


    <div
        class="mx-auto
               w-[min(1240px,calc(100%-48px))]
               max-[680px]:w-[calc(100%-30px)]">


        <div class="mb-8">

            <div
                class="flex
                       items-center
                       gap-[10px]
                       text-[10px]
                       font-extrabold
                       uppercase
                       tracking-[.2em]
                       text-gold2">

                <span
                    class="h-px
                           w-[27px]
                           bg-gold">
                </span>

                A good reason

            </div>


            <h2
                class="mt-3
                       font-serifNoor
                       text-[clamp(35px,5vw,60px)]
                       leading-[.95]
                       tracking-[-.06em]">

                Offers, without<br/>
                the shouting.

            </h2>

        </div>


        <div
            class="grid
                   grid-cols-[1.2fr_.8fr_.8fr]
                   gap-[15px]
                   max-[950px]:grid-cols-2
                   max-[680px]:grid-cols-1">


            <article
                class="min-h-[240px]
                       rounded-[22px]
                       bg-gold
                       p-[26px]
                       text-night">

                <small
                    class="text-[9px]
                           font-extrabold
                           uppercase
                           tracking-[.16em]">

                    For your first evening

                </small>


                <h3
                    class="my-[25px]
                           font-serifNoor
                           text-[29px]
                           leading-[.98]">

                    A little welcome<br/>
                    from us.

                </h3>


                <p
                    class="max-w-[190px]
                           text-[10px]
                           leading-[1.6]">

                    Get your first NOOR order started with a quiet little saving.

                </p>


                <strong
                    class="mt-[18px]
                           block
                           font-serifNoor
                           text-[35px]">

                    ৳150 off

                </strong>


                <button
                    data-code="NOOR150"
                    type="button"
                    class="mt-4
                           rounded-full
                           border
                           border-current
                           px-[11px]
                           py-2
                           text-[9px]
                           font-extrabold">

                    Copy NOOR150

                </button>

            </article>


            <article
                class="min-h-[240px]
                       rounded-[22px]
                       bg-[#25231f]
                       p-[26px]">

                <small
                    class="text-[9px]
                           font-extrabold
                           uppercase
                           tracking-[.16em]">

                    Midweek ritual

                </small>


                <h3
                    class="my-[25px]
                           font-serifNoor
                           text-[29px]
                           leading-[.98]">

                    Wednesday<br/>
                    deserves better.

                </h3>


                <p
                    class="max-w-[190px]
                           text-[10px]
                           leading-[1.6]">

                    Free delivery on curated lunch tables.

                </p>


                <strong
                    class="mt-[18px]
                           block
                           font-serifNoor
                           text-[35px]">

                    Free

                </strong>


                <button
                    data-code="WARMNOON"
                    type="button"
                    class="mt-4
                           rounded-full
                           border
                           border-current
                           px-[11px]
                           py-2
                           text-[9px]
                           font-extrabold">

                    Copy WARMNOON

                </button>

            </article>


            <article
                class="min-h-[240px]
                       rounded-[22px]
                       bg-[#dfe6d6]
                       p-[26px]
                       text-night">

                <small
                    class="text-[9px]
                           font-extrabold
                           uppercase
                           tracking-[.16em]">

                    Bring the table

                </small>


                <h3
                    class="my-[25px]
                           font-serifNoor
                           text-[29px]
                           leading-[.98]">

                    More plates,<br/>
                    less fuss.

                </h3>


                <p
                    class="max-w-[190px]
                           text-[10px]
                           leading-[1.6]">

                    Group orders get a little more generous.

                </p>


                <strong
                    class="mt-[18px]
                           block
                           font-serifNoor
                           text-[35px]">

                    25%

                </strong>


                <button
                    data-code="TABLE25"
                    type="button"
                    class="mt-4
                           rounded-full
                           border
                           border-current
                           px-[11px]
                           py-2
                           text-[9px]
                           font-extrabold">

                    Copy TABLE25

                </button>

            </article>

        </div>

    </div>

</section>


<!-- ========================================================= -->
<!-- HOW -->
<!-- ========================================================= -->

<section
    id="how"
    class="border-t
           border-white/[.055]
           py-[105px]
           max-[680px]:py-[75px]">


    <div
        class="mx-auto
               w-[min(1240px,calc(100%-48px))]
               max-[680px]:w-[calc(100%-30px)]">


        <div
            class="grid
                   grid-cols-[.85fr_1.15fr]
                   gap-[70px]
                   max-[680px]:grid-cols-1">


            <div>

                <div
                    class="flex
                           items-center
                           gap-[10px]
                           text-[10px]
                           font-extrabold
                           uppercase
                           tracking-[.2em]
                           text-gold2">

                    <span
                        class="h-px
                               w-[27px]
                               bg-gold">
                    </span>

                    The NOOR way

                </div>


                <h2
                    class="my-[15px]
                           font-serifNoor
                           text-[clamp(42px,5vw,70px)]
                           leading-[.9]
                           tracking-[-.07em]">

                    Less app.<br/>
                    More appetite.

                </h2>


                <p
                    class="max-w-[310px]
                           text-[12px]
                           leading-[1.7]
                           text-[#999187]">

                    We designed NOOR around the good part:
                    deciding what sounds right,
                    and getting it while the thought is still warm.

                </p>

            </div>


            <div
                class="grid
                       grid-cols-3
                       gap-[15px]
                       max-[680px]:grid-cols-1">


                <div
                    class="border-t
                           border-white/[.19]
                           pt-[18px]">

                    <div
                        class="font-monoNoor
                               text-[12px]
                               text-gold">

                        01

                    </div>


                    <i
                        data-lucide="compass"
                        class="my-[45px]
                               w-[21px]
                               text-gold2">
                    </i>


                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[21px]">

                        Find a feeling

                    </h3>


                    <p
                        class="text-[10px]
                               leading-[1.7]
                               text-[#918a81]">

                        Browse by mood,
                        neighbourhood,
                        or the one dish you cannot stop thinking about.

                    </p>

                </div>


                <div
                    class="border-t
                           border-white/[.19]
                           pt-[18px]">

                    <div
                        class="font-monoNoor
                               text-[12px]
                               text-gold">

                        02

                    </div>


                    <i
                        data-lucide="shopping-bag"
                        class="my-[45px]
                               w-[21px]
                               text-gold2">
                    </i>


                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[21px]">

                        Set the table

                    </h3>


                    <p
                        class="text-[10px]
                               leading-[1.7]
                               text-[#918a81]">

                        Save favourites,
                        build your order,
                        and see the honest total before you commit.

                    </p>

                </div>


                <div
                    class="border-t
                           border-white/[.19]
                           pt-[18px]">

                    <div
                        class="font-monoNoor
                               text-[12px]
                               text-gold">

                        03

                    </div>


                    <i
                        data-lucide="door-open"
                        class="my-[45px]
                               w-[21px]
                               text-gold2">
                    </i>


                    <h3
                        class="mb-2
                               font-serifNoor
                               text-[21px]">

                        Let it arrive

                    </h3>


                    <p
                        class="text-[10px]
                               leading-[1.7]
                               text-[#918a81]">

                        Follow your order,
                        then open the door to something that feels like a plan.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


</main>


<!-- ========================================================= -->
<!-- FOOTER -->
<!-- ========================================================= -->

<footer
    class="mx-auto
           flex
           w-[min(1240px,calc(100%-48px))]
           justify-between
           border-t
           border-white/[.07]
           py-7
           pb-[38px]
           text-[10px]
           text-[#777168]
           max-[680px]:block
           max-[680px]:w-[calc(100%-30px)]">


    <div>

        <span
            class="font-extrabold
                   tracking-[.2em]
                   text-white">

            NOOR

        </span>

        <br/>

        Food, considered.

    </div>


    <div
        class="flex
               gap-5
               max-[680px]:my-[9px]">

        <a
            href="#"
            class="hover:text-gold2">

            Journal

        </a>


        <a
            href="#"
            class="hover:text-gold2">

            About

        </a>


        <a
            href="#"
            class="hover:text-gold2">

            Careers

        </a>


        <a
            href="#"
            class="hover:text-gold2">

            Help

        </a>

    </div>


    <div class="font-monoNoor">
        © 2026 NOOR
    </div>

</footer>


<!-- ========================================================= -->
<!-- AUTH MODAL -->
<!-- ========================================================= -->

<div
    id="modal"
    class="fixed
           inset-0
           z-[85]
           hidden
           place-items-center
           bg-black/[.67]
           p-5
           backdrop-blur-[13px]">


    <div
        class="grid
               w-[min(930px,100%)]
               grid-cols-[.9fr_1.1fr]
               overflow-hidden
               rounded-[28px]
               bg-cream
               text-night
               shadow-[0_30px_100px_#000c]
               max-[760px]:grid-cols-1">


        <!-- LEFT -->

        <aside
            class="relative
                   flex
                   min-h-[610px]
                   flex-col
                   justify-between
                   bg-[linear-gradient(180deg,#171613e8,#171613f2),url('https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=1000&q=85')]
                   bg-cover
                   bg-center
                   p-[42px]
                   text-white
                   max-[760px]:hidden">


            <div
                class="flex
                       items-center
                       gap-[11px]
                       text-[16px]
                       font-extrabold
                       tracking-[.24em]">

                <span
                    class="relative
                           grid
                           h-[27px]
                           w-[27px]
                           place-items-center
                           rounded-full
                           border
                           border-gold

                           after:absolute
                           after:h-[7px]
                           after:w-[7px]
                           after:rounded-full
                           after:bg-gold">
                </span>

                NOOR

            </div>


            <div
                class="max-w-[310px]">


                <div
                    class="mb-[18px]
                           flex
                           items-center
                           gap-[10px]
                           text-[10px]
                           font-extrabold
                           uppercase
                           tracking-[.2em]
                           text-gold2">

                    <span
                        class="h-px
                               w-[27px]
                               bg-gold">
                    </span>

                    A seat at the table

                </div>


                <h2
                    class="mb-[17px]
                           font-serifNoor
                           text-[clamp(38px,4vw,61px)]
                           leading-[.9]">

                    Good food<br/>

                    <i>
                        finds you.
                    </i>

                </h2>


                <p
                    class="text-[11px]
                           leading-[1.8]
                           text-[#c1bbb2]">

                    Save your favourite places,
                    keep your usuals close,
                    and make the next order feel effortless.

                </p>

            </div>


            <div
                class="max-w-[290px]
                       font-serifNoor
                       text-[15px]
                       italic
                       leading-[1.5]
                       text-[#e8dfd3]">

                “The best evenings usually start with something simple.”

            </div>

        </aside>


        <!-- RIGHT -->

        <div
            class="relative
                   px-[44px]
                   pb-[34px]
                   pt-10
                   max-[760px]:px-[23px]">


            <!-- CLOSE -->

            <button
                id="closeModal"
                type="button"
                class="absolute
                       right-[18px]
                       top-[18px]
                       grid
                       h-[35px]
                       w-[35px]
                       place-items-center
                       rounded-full
                       border
                       border-black/[.125]
                       bg-white">

                <i data-lucide="x"></i>

            </button>


            <!-- MOBILE LOGO -->

            <div
                class="mb-[22px]
                       hidden
                       items-center
                       gap-[11px]
                       text-[16px]
                       font-extrabold
                       tracking-[.24em]
                       max-[760px]:flex">

                <span
                    class="relative
                           grid
                           h-[27px]
                           w-[27px]
                           place-items-center
                           rounded-full
                           border
                           border-gold

                           after:absolute
                           after:h-[7px]
                           after:w-[7px]
                           after:rounded-full
                           after:bg-gold">
                </span>

                NOOR

            </div>


            <!-- TABS -->

            <div
                class="mb-[30px]
                       flex
                       gap-[5px]
                       rounded-xl
                       bg-[#e5ded2]
                       p-1">


                <button
                    id="loginTab"
                    type="button"
                    class="flex-1
                           rounded-lg
                           bg-white
                           px-2
                           py-[11px]
                           text-[11px]
                           font-extrabold
                           shadow-[0_4px_13px_#00000010]">

                    Sign in

                </button>


                <button
                    id="joinTab"
                    type="button"
                    class="flex-1
                           rounded-lg
                           px-2
                           py-[11px]
                           text-[11px]
                           font-extrabold">

                    Create account

                </button>

            </div>


@include('SignUp')

 