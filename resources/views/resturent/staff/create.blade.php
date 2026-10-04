<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Staff — NOOR</title>
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
    $inputBase = 'w-full rounded-xl border bg-[#11120f] py-3 text-sm text-[#f6f2e9] placeholder-stone-600 outline-none transition focus:border-[#d7a858] focus:ring-4 focus:ring-[#d7a858]/10';
    $cls = fn (string $field) => $inputBase . ' ' . ($errors->has($field) ? 'border-[#ed8b79]/60' : 'border-white/10');
@endphp

<body class="min-h-screen bg-[#0e0f0d] text-[#f6f2e9] antialiased">

    {{-- Top bar --}}
    <header class="flex h-[72px] items-center justify-between border-b border-white/10 px-4 sm:px-8">
        <div class="flex items-center gap-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </div>
        <a href="{{ route('resturent.staff.index') }}" class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b]">
            <i class="ti ti-arrow-left text-sm"></i>Back to Staff
        </a>
    </header>

    <main class="mx-auto w-full max-w-3xl px-4 pb-16 pt-10 sm:px-6">

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <span class="grid h-14 w-14 flex-none place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">
                <i class="ti ti-user-plus text-3xl"></i>
            </span>
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Staff management</p>
                <h1 class="font-display mt-2 text-4xl leading-tight tracking-tight sm:text-5xl">Add Staff Member</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                    Create a login for a new team member of
                    <span class="font-semibold text-[#f1cf8b]">{{ $restaurant->name }}</span>.
                </p>
            </div>
        </div>

        {{-- Restaurant chip --}}
        <div class="mt-6 inline-flex items-center gap-3 rounded-2xl border border-white/10 bg-[#161714] px-4 py-3">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#d7a858]/15 text-[#d7a858]">
                <i class="ti ti-building-store text-lg"></i>
            </span>
            <div>
                <p class="text-[9px] uppercase tracking-wider text-stone-600">Adding staff to</p>
                <p class="text-xs font-extrabold">{{ $restaurant->name }}</p>
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4" role="status">
                <i class="ti ti-circle-check mt-0.5 text-lg text-[#b8d985]"></i>
                <p class="text-xs font-semibold text-[#d3e8b0]">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4" role="alert">
                <i class="ti ti-alert-circle mt-0.5 text-lg text-[#ed8b79]"></i>
                <p class="text-xs font-semibold text-[#f3b3a7]">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Validation summary --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4" role="alert">
                <div class="flex items-center gap-2 text-xs font-extrabold text-[#ed8b79]">
                    <i class="ti ti-alert-circle text-base"></i>Please fix the following before continuing
                </div>
                <ul class="mt-2 list-inside list-disc space-y-1 text-[11px] text-[#f3b3a7]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('resturent.staff.store') }}"
              class="mt-8 overflow-hidden rounded-3xl border border-white/10 bg-[#161714]">
            @csrf

            <div class="space-y-6 p-5 sm:p-8">

                {{-- Full name --}}
                <div>
                    <label for="name" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-user text-sm text-[#d7a858]"></i>Full Name
                        <span class="text-[#ed8b79]">*</span>
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                           placeholder="e.g. Mahbub Alam" class="{{ $cls('name') }} px-4">
                    @error('name')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-mail text-sm text-[#d7a858]"></i>Email Address
                        <span class="text-[#ed8b79]">*</span>
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="off"
                           placeholder="staff@example.com" class="{{ $cls('email') }} px-4">
                    @error('email')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-2 flex items-center gap-2 text-xs font-bold">
                            <i class="ti ti-lock text-sm text-[#d7a858]"></i>Password
                            <span class="text-[#ed8b79]">*</span>
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                   placeholder="Enter a password" class="{{ $cls('password') }} pl-4 pr-11">
                            <button type="button" data-toggle="password" aria-label="Show password"
                                    class="absolute inset-y-0 right-0 grid w-11 place-items-center text-stone-500 transition hover:text-[#f1cf8b]">
                                <i class="ti ti-eye text-base"></i>
                            </button>
                        </div>
                        @error('password')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirm password --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 flex items-center gap-2 text-xs font-bold">
                            <i class="ti ti-lock-check text-sm text-[#d7a858]"></i>Confirm Password
                            <span class="text-[#ed8b79]">*</span>
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                   placeholder="Re-enter the password" class="{{ $cls('password_confirmation') }} pl-4 pr-11">
                            <button type="button" data-toggle="password_confirmation" aria-label="Show password"
                                    class="absolute inset-y-0 right-0 grid w-11 place-items-center text-stone-500 transition hover:text-[#f1cf8b]">
                                <i class="ti ti-eye text-base"></i>
                            </button>
                        </div>
                        @error('password_confirmation')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                    </div>
                </div>

                <p class="flex items-start gap-2 rounded-xl border border-white/5 bg-white/[0.02] p-3 text-[11px] leading-5 text-stone-500">
                    <i class="ti ti-info-circle mt-0.5 text-sm text-[#d7a858]"></i>
                    Share these login details with your team member securely. They will use this email and password to sign in.
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-white/10 bg-[#11120f] p-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <a href="{{ route('resturent.staff.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-stone-300 transition hover:border-white/25 hover:text-white">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#d7a858] px-6 py-3 text-sm font-extrabold text-[#161714] shadow-lg shadow-[#d7a858]/20 transition hover:-translate-y-0.5 hover:bg-[#f1cf8b]">
                    <i class="ti ti-user-plus text-lg"></i>Add Staff
                </button>
            </div>
        </form>
    </main>

    <script>
        document.querySelectorAll('[data-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const field = document.getElementById(btn.dataset.toggle);
                const show = field.type === 'password';
                field.type = show ? 'text' : 'password';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                btn.querySelector('i').className = 'ti ' + (show ? 'ti-eye-off' : 'ti-eye') + ' text-base';
            });
        });
    </script>
</body>
</html>