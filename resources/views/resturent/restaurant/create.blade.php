<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Your Restaurant — NOOR</title>
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
    $inputBase = 'w-full rounded-xl border bg-[#11120f] px-4 py-3 text-sm text-[#f6f2e9] placeholder-stone-600 outline-none transition focus:border-[#d7a858] focus:ring-4 focus:ring-[#d7a858]/10';
    $cls = fn (string $field) => $inputBase . ' ' . ($errors->has($field) ? 'border-[#ed8b79]/60' : 'border-white/10');
@endphp

<body class="min-h-screen bg-[#0e0f0d] text-[#f6f2e9] antialiased">

    {{-- Top bar --}}
    <header class="flex h-[72px] items-center justify-between border-b border-white/10 px-4 sm:px-8">
        <a href="{{ route('resturent.dashboard') }}" class="flex items-center gap-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </a>
        <a href="{{ route('resturent.dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b]">
            <i class="ti ti-arrow-left text-sm"></i>Back to Dashboard
        </a>
    </header>

    <main class="mx-auto w-full max-w-3xl px-4 pb-16 pt-10 sm:px-6">

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <span class="grid h-14 w-14 flex-none place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">
                <i class="ti ti-building-store text-3xl"></i>
            </span>
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Restaurant setup</p>
                <h1 class="font-display mt-2 text-4xl leading-tight tracking-tight sm:text-5xl">Create Your Restaurant</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                    Tell us about your restaurant. You can update these details later from your dashboard.
                </p>
            </div>
        </div>

        {{-- Error summary --}}
        @if ($errors->any())
            <div class="mt-8 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4" role="alert">
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
        <form method="POST" action="{{ route('resturent.restaurant.store') }}" enctype="multipart/form-data"
              class="mt-8 overflow-hidden rounded-3xl border border-white/10 bg-[#161714]">
            @csrf

            <div class="space-y-6 p-5 sm:p-8">

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-building-store text-sm text-[#d7a858]"></i>Restaurant Name
                        <span class="text-[#ed8b79]">*</span>
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" required
                           placeholder="e.g. Rong Kitchen" class="{{ $cls('name') }}">
                    @error('name')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-align-left text-sm text-[#d7a858]"></i>Description
                        <span class="text-[10px] font-medium text-stone-600">Optional</span>
                    </label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="What makes your restaurant special? Cuisine, specialties, atmosphere…"
                              class="{{ $cls('description') }} resize-y">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-phone text-sm text-[#d7a858]"></i>Phone
                        <span class="text-[10px] font-medium text-stone-600">Optional</span>
                    </label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="30"
                           placeholder="e.g. 01XXXXXXXXX" class="{{ $cls('phone') }}">
                    @error('phone')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-map-pin text-sm text-[#d7a858]"></i>Address
                        <span class="text-[10px] font-medium text-stone-600">Optional</span>
                    </label>
                    <textarea id="address" name="address" rows="3"
                              placeholder="Road, area, city"
                              class="{{ $cls('address') }} resize-y">{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>

                {{-- Image --}}
                <div>
                    <label for="image" class="mb-2 flex items-center gap-2 text-xs font-bold">
                        <i class="ti ti-photo text-sm text-[#d7a858]"></i>Restaurant Image
                        <span class="text-[10px] font-medium text-stone-600">Optional</span>
                    </label>

                    <label for="image" id="dropzone"
                           class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed {{ $errors->has('image') ? 'border-[#ed8b79]/60' : 'border-white/15' }} bg-[#11120f] px-4 py-8 text-center transition hover:border-[#d7a858]/60 hover:bg-[#d7a858]/5">
                        <div id="placeholder" class="flex flex-col items-center">
                            <span class="grid h-12 w-12 place-items-center rounded-xl bg-[#d7a858]/10 text-[#d7a858] transition group-hover:bg-[#d7a858]/20">
                                <i class="ti ti-cloud-upload text-2xl"></i>
                            </span>
                            <p class="mt-3 text-xs font-bold">Click to upload an image</p>
                            <p class="mt-1 text-[11px] text-stone-500">JPG, JPEG, PNG or WEBP · up to 2MB</p>
                        </div>
                        <div id="preview" class="hidden w-full">
                            <img id="previewImg" src="" alt="Selected image preview" class="mx-auto max-h-56 rounded-xl object-cover">
                            <p id="fileName" class="mt-3 truncate text-[11px] font-semibold text-[#f1cf8b]"></p>
                            <p class="mt-1 text-[10px] text-stone-500">Click to choose a different image</p>
                        </div>
                    </label>
                    <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only">

                    <p id="imageError" class="mt-1.5 hidden text-[11px] text-[#ed8b79]"></p>
                    @error('image')<p class="mt-1.5 text-[11px] text-[#ed8b79]">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-white/10 bg-[#11120f] p-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <a href="{{ route('resturent.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-stone-300 transition hover:border-white/25 hover:text-white">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#d7a858] px-6 py-3 text-sm font-extrabold text-[#161714] shadow-lg shadow-[#d7a858]/20 transition hover:-translate-y-0.5 hover:bg-[#f1cf8b]">
                    <i class="ti ti-check text-lg"></i>Create Restaurant
                </button>
            </div>
        </form>
    </main>

    <script>
        const input = document.getElementById('image');
        const placeholder = document.getElementById('placeholder');
        const preview = document.getElementById('preview');
        const previewImg = document.getElementById('previewImg');
        const fileName = document.getElementById('fileName');
        const imageError = document.getElementById('imageError');
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        const maxBytes = 2 * 1024 * 1024;

        const reset = () => {
            input.value = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            previewImg.removeAttribute('src');
        };

        input.addEventListener('change', () => {
            const file = input.files[0];
            imageError.classList.add('hidden');
            if (!file) return reset();

            if (!allowed.includes(file.type)) {
                reset();
                imageError.textContent = 'Please choose a JPG, JPEG, PNG or WEBP image.';
                return imageError.classList.remove('hidden');
            }
            if (file.size > maxBytes) {
                reset();
                imageError.textContent = 'The image must be 2MB or smaller.';
                return imageError.classList.remove('hidden');
            }

            previewImg.src = URL.createObjectURL(file);
            fileName.textContent = file.name;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
        });
    </script>
</body>
</html>