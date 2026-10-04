<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NOOR Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        night: '#11110f',
                        night2: '#1a1916',
                        cream: '#f4f0e8',
                        gold: '#d8a85a',
                        muted: '#9d978e'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-night text-cream">

    <div class="min-h-screen flex">

        <aside class="w-64 bg-night2 p-6 hidden lg:block">
            <h1 class="text-3xl font-serif">
                NOOR
            </h1>

            <p class="text-xs text-muted mt-1">
                Food, considered.
            </p>

            <nav class="mt-10 space-y-2">

                <a href="/admin/dashboard"
                   class="block px-4 py-3 rounded-xl bg-gold text-night">
                    Dashboard
                </a>

                <a href="#" class="block px-4 py-3 rounded-xl text-muted">
                    Orders
                </a>

                <a href="#" class="block px-4 py-3 rounded-xl text-muted">
                    Restaurants
                </a>

                <a href="#" class="block px-4 py-3 rounded-xl text-muted">
                    Customers
                </a>

            </nav>
        </aside>


        <main class="flex-1">

            <header class="h-20 border-b border-white/10 px-6 flex items-center">
                <h2 class="text-2xl">
                    Dashboard
                </h2>
            </header>

            <div class="p-6">

                @yield('content')

            </div>

        </main>

    </div>

</body>
</html>