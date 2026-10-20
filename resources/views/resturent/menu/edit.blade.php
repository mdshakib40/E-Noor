<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Food Item — NOOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Manrope, Arial, sans-serif; }
        .font-display { font-family: "Playfair Display", Georgia, serif; }
        .field-invalid { border-color: rgba(237, 139, 121, .6) !important; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Save button: idle -> loading (the page then reloads with the real server result) */
        @keyframes spin  { to { transform: rotate(360deg); } }
        @keyframes sweep { to { transform: translateX(100%); } }
        .sbtn {
            position: relative; display: inline-grid; overflow: hidden; isolation: isolate;
            border-radius: .75rem; padding: .85rem 1.5rem; min-width: 210px;
            background: linear-gradient(135deg, #f1cf8b, #d7a858); color: #161714;
            font-size: .875rem; font-weight: 800; border: 0; cursor: pointer;
            box-shadow: 0 10px 30px -10px rgba(215, 168, 88, .55);
            transition: transform .2s, filter .3s;
        }
        .sbtn:hover:not(:disabled) { transform: translateY(-2px); }
        .sbtn:focus-visible { outline: 2px solid #f1cf8b; outline-offset: 3px; }
        .sbtn:disabled { cursor: progress; filter: saturate(.9); }
        .sbtn::after {
            content: ""; position: absolute; inset: 0; z-index: -1; opacity: 0;
            background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, .4) 50%, transparent 70%);
            transform: translateX(-100%);
        }
        .sbtn[data-state="loading"]::after { opacity: 1; animation: sweep 1.4s ease-in-out infinite; }
        .sbtn .st {
            grid-area: 1 / 1; display: flex; align-items: center; justify-content: center; gap: .55rem;
            opacity: 0; visibility: hidden; transform: translateY(8px);
            transition: opacity .3s, transform .3s, visibility 0s .3s;
        }
        .sbtn[data-state="idle"] .st-idle,
        .sbtn[data-state="loading"] .st-loading {
            opacity: 1; visibility: visible; transform: none;
            transition: opacity .3s .1s, transform .3s .1s, visibility 0s;
        }
        .spin { animation: spin .8s linear infinite; }
        @media (prefers-reduced-motion: reduce) {
            .sbtn, .sbtn .st { transition: none !important; }
            .sbtn::after, .spin { animation: none !important; }
            .sbtn:hover:not(:disabled) { transform: none; }
        }
    </style>
</head>

@php
    use Illuminate\Support\Str;

    $currentStatus = strtolower((string) ($menuItem->status instanceof \BackedEnum ? $menuItem->status->value : $menuItem->status));
    $currentImage = $menuItem->image ? asset('storage/' . ltrim($menuItem->image, '/')) : null;

    $inputBase = 'w-full rounded-xl border bg-[#11120f] py-3 text-sm text-[#f6f2e9] placeholder-stone-600 outline-none transition focus:border-[#d7a858] focus:ring-4 focus:ring-[#d7a858]/10';
    $cls = fn (string $field) => $inputBase . ' ' . ($errors->has($field) ? 'field-invalid border-[#ed8b79]/60' : 'border-white/10');
    $statusOptions = [['active', 'Active', 'ti-circle-check'], ['inactive', 'Inactive', 'ti-circle-off']];
    $availabilityOptions = [['1', 'Available', 'ti-check'], ['0', 'Unavailable', 'ti-x']];
    $segment = 'flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-white/10 bg-[#11120f] px-3 py-3 text-xs font-bold text-stone-400 transition hover:border-white/25 peer-checked:border-[#d7a858] peer-checked:bg-[#d7a858]/10 peer-checked:text-[#f1cf8b] peer-focus-visible:ring-4 peer-focus-visible:ring-[#d7a858]/20';
@endphp

<body class="min-h-screen bg-[#0e0f0d] text-[#f6f2e9] antialiased">

    {{-- Top bar --}}
    <header class="flex h-[72px] items-center justify-between border-b border-white/10 px-4 sm:px-8">
        <div class="flex items-center gap-3 text-lg font-extrabold tracking-[0.23em]">
            <span class="grid h-7 w-7 place-items-center rounded-full border border-[#d7a858]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d7a858] shadow-[0_0_0_5px_rgba(215,168,88,.12)]"></span>
            </span>NOOR
        </div>
        <a href="{{ route('resturent.menu.index') }}" class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-[11px] font-bold text-stone-300 transition hover:border-[#d7a858]/50 hover:text-[#f1cf8b]">
            <i class="ti ti-arrow-left text-sm"></i>Back to Menu
        </a>
    </header>

    <main class="mx-auto w-full max-w-5xl px-4 pb-16 pt-10 sm:px-6">

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <span class="grid h-14 w-14 flex-none place-items-center rounded-2xl bg-[#d7a858] text-[#161714]">
                <i class="ti ti-pencil text-3xl"></i>
            </span>
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#f1cf8b]">Menu management</p>
                <h1 class="font-display mt-2 break-words text-4xl leading-tight tracking-tight sm:text-5xl">Edit <em class="text-[#f1cf8b]">{{ $menuItem->name }}</em></h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
                    Update this dish on the menu of <span class="font-semibold text-[#f1cf8b]">{{ $restaurant->name }}</span>.
                </p>
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mt-8 flex items-start gap-3 rounded-2xl border border-[#b8d985]/30 bg-[#b8d985]/10 p-4" role="status">
                <i class="ti ti-circle-check mt-0.5 text-lg text-[#b8d985]"></i>
                <p class="text-xs font-semibold text-[#d3e8b0]">{{ session('success') }}</p>
            </div>
        @endif

        <div id="formBanner" class="{{ $errors->any() || session('error') ? '' : 'hidden' }} mt-8 flex items-start gap-3 rounded-2xl border border-[#ed8b79]/30 bg-[#ed8b79]/10 p-4" role="alert">
            <i class="ti ti-alert-circle mt-0.5 text-lg text-[#ed8b79]"></i>
            <p id="formBannerText" class="text-xs font-semibold text-[#f3b3a7]">
                {{ session('error') ?: ($errors->any() ? 'Please fix the highlighted fields and try again.' : '') }}
            </p>
        </div>

        <form id="foodForm" method="POST" action="{{ route('resturent.menu.update', $menuItem->id) }}" enctype="multipart/form-data" novalidate class="mt-8">
            @csrf
            @method('PUT')

            <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr]">

                {{-- Left: details --}}
                <section class="space-y-6 rounded-3xl border border-white/10 bg-[#161714] p-5 sm:p-8">
                    <h2 class="text-xs font-extrabold">Food details</h2>

                    <div>
                        <label for="name" class="mb-2 flex items-center gap-2 text-xs font-bold">
                            <i class="ti ti-tools-kitchen-2 text-sm text-[#d7a858]"></i>Food name <span class="text-[#ed8b79]">*</span>
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name', $menuItem->name) }}" maxlength="255" required
                               placeholder="e.g. Chicken Kacchi Biryani" data-field="name" class="{{ $cls('name') }} px-4">
                        <p data-error="name" class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('name') ? '' : 'hidden' }}">{{ $errors->first('name') }}</p>
                    </div>

                    <div>
                        <label for="description" class="mb-2 flex items-center gap-2 text-xs font-bold">
                            <i class="ti ti-align-left text-sm text-[#d7a858]"></i>Description
                            <span class="text-[10px] font-medium text-stone-600">Optional</span>
                        </label>
                        <textarea id="description" name="description" rows="5" maxlength="2000" data-field="description"
                                  placeholder="Ingredients, taste, serving size…"
                                  class="{{ $cls('description') }} resize-y px-4">{{ old('description', $menuItem->description) }}</textarea>
                        <p data-error="description" class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('description') ? '' : 'hidden' }}">{{ $errors->first('description') }}</p>
                    </div>

                    <div>
                        <label for="price" class="mb-2 flex items-center gap-2 text-xs font-bold">
                            <i class="ti ti-coin-taka text-sm text-[#d7a858]"></i>Price (BDT) <span class="text-[#ed8b79]">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-[#d7a858]">৳</span>
                            <input id="price" name="price" type="number" min="1" step="0.01" inputmode="decimal" required
                                   value="{{ old('price', $menuItem->price) }}" placeholder="0.00" data-field="price" class="{{ $cls('price') }} pl-9 pr-4">
                        </div>
                        <p data-error="price" class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('price') ? '' : 'hidden' }}">{{ $errors->first('price') }}</p>
                    </div>
                </section>

                {{-- Right: image, status, availability --}}
                <div class="space-y-4">
                    <section class="rounded-3xl border border-white/10 bg-[#161714] p-5 sm:p-6">
                        <label for="image" class="mb-3 flex items-center gap-2 text-xs font-bold">
                            <i class="ti ti-photo text-sm text-[#d7a858]"></i>Food image
                            <span class="text-[10px] font-medium text-stone-600">Optional</span>
                        </label>

                        @if ($currentImage)
                            <div class="mb-3 flex items-center gap-4 rounded-2xl border border-white/10 bg-[#11120f] p-3">
                                <img src="{{ $currentImage }}" alt="{{ $menuItem->name }}" class="h-16 w-24 flex-none rounded-xl object-cover">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold">Current image</p>
                                    <p class="mt-1 text-[11px] text-stone-500">Upload a new one to replace it, or leave empty to keep it.</p>
                                </div>
                            </div>
                        @endif

                        <label for="image" id="dropzone" data-field="image"
                               class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed {{ $errors->has('image') ? 'field-invalid border-[#ed8b79]/60' : 'border-white/15' }} bg-[#11120f] px-4 py-8 text-center transition hover:border-[#d7a858]/60 hover:bg-[#d7a858]/5">
                            <div id="placeholder" class="flex flex-col items-center">
                                <span class="grid h-12 w-12 place-items-center rounded-xl bg-[#d7a858]/10 text-[#d7a858] transition group-hover:bg-[#d7a858]/20">
                                    <i class="ti ti-cloud-upload text-2xl"></i>
                                </span>
                                <p class="mt-3 text-xs font-bold">{{ $currentImage ? 'Click to upload a new image' : 'Click to upload an image' }}</p>
                                <p class="mt-1 text-[11px] text-stone-500">JPG, PNG or WEBP · up to 2MB</p>
                            </div>
                            <div id="preview" class="hidden w-full">
                                <img id="previewImg" src="" alt="New image preview" class="mx-auto aspect-[16/10] w-full rounded-xl object-cover">
                                <p id="fileName" class="mt-3 truncate text-[11px] font-semibold text-[#f1cf8b]"></p>
                                <p class="mt-1 text-[10px] text-stone-500">New image selected · click to choose a different one</p>
                            </div>
                        </label>
                        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only">
                        <button type="button" id="removeImage" class="mt-3 hidden items-center gap-1.5 text-[11px] font-bold text-[#ed8b79] transition hover:text-[#f3b3a7]">
                            <i class="ti ti-x text-sm"></i>Clear selected image
                        </button>
                        <p data-error="image" class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('image') ? '' : 'hidden' }}">{{ $errors->first('image') }}</p>
                    </section>

                    <section class="space-y-5 rounded-3xl border border-white/10 bg-[#161714] p-5 sm:p-6">
                        <div>
                            <p class="mb-2.5 flex items-center gap-2 text-xs font-bold"><i class="ti ti-toggle-right text-sm text-[#d7a858]"></i>Status</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($statusOptions as [$value, $label, $icon])
                                    <label class="block">
                                        <input type="radio" name="status" value="{{ $value }}" class="peer sr-only" @checked(old('status', $currentStatus) === $value)>
                                        <span class="{{ $segment }}"><i class="ti {{ $icon }} text-sm"></i>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p data-error="status" class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('status') ? '' : 'hidden' }}">{{ $errors->first('status') }}</p>
                        </div>

                        <div>
                            <p class="mb-2.5 flex items-center gap-2 text-xs font-bold"><i class="ti ti-basket-check text-sm text-[#d7a858]"></i>Availability</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($availabilityOptions as [$value, $label, $icon])
                                    <label class="block">
                                        <input type="radio" name="is_available" value="{{ $value }}" class="peer sr-only" @checked((string) old('is_available', $menuItem->is_available ? '1' : '0') === $value)>
                                        <span class="{{ $segment }}"><i class="ti {{ $icon }} text-sm"></i>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p data-error="is_available" class="mt-1.5 text-[11px] text-[#ed8b79] {{ $errors->has('is_available') ? '' : 'hidden' }}">{{ $errors->first('is_available') }}</p>
                        </div>
                    </section>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-4 flex flex-col-reverse gap-3 rounded-3xl border border-white/10 bg-[#11120f] p-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <a href="{{ route('resturent.menu.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-stone-300 transition hover:border-white/25 hover:text-white">
                    Cancel
                </a>

                <button type="submit" id="submitBtn" class="sbtn w-full sm:w-auto" data-state="idle">
                    <span class="st st-idle"><i class="ti ti-device-floppy text-lg"></i>Save Changes</span>
                    <span class="st st-loading">
                        <svg class="spin h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/>
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        Saving changes...
                    </span>
                </button>
                <span id="btnLive" class="sr-only" aria-live="polite"></span>
            </div>
        </form>
    </main>

    <script>
        (() => {
            const form = document.getElementById('foodForm');
            const btn = document.getElementById('submitBtn');
            const live = document.getElementById('btnLive');
            const banner = document.getElementById('formBanner');
            const bannerText = document.getElementById('formBannerText');
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let busy = false;

            const setState = (state) => {
                btn.dataset.state = state;
                btn.setAttribute('aria-busy', state === 'loading' ? 'true' : 'false');
                live.textContent = state === 'loading' ? 'Saving changes…' : '';
            };

            const clearErrors = () => {
                banner.classList.add('hidden');
                form.querySelectorAll('[data-error]').forEach((el) => { el.textContent = ''; el.classList.add('hidden'); });
                form.querySelectorAll('.field-invalid').forEach((el) => el.classList.remove('field-invalid'));
            };

            const showErrors = (errors) => {
                let first = null;
                Object.entries(errors).forEach(([key, [message]]) => {
                    const slot = form.querySelector(`[data-error="${key}"]`);
                    slot.textContent = message;
                    slot.classList.remove('hidden');
                    form.querySelector(`[data-field="${key}"]`)?.classList.add('field-invalid');
                    first = first || key;
                });
                bannerText.textContent = 'Please fix the highlighted fields and try again.';
                banner.classList.remove('hidden');
                banner.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
                if (first) document.getElementById(first)?.focus();
            };

            // Client-side checks only for convenience; the server validates everything again.
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            const validate = () => {
                const errors = {};
                if (!document.getElementById('name').value.trim()) errors.name = ['Food name is required.'];
                const raw = document.getElementById('price').value.trim();
                const price = parseFloat(raw);
                if (!raw || Number.isNaN(price)) errors.price = ['Price is required.'];
                else if (price < 1) errors.price = ['Price must be at least ৳1.'];
                const file = document.getElementById('image').files[0];
                if (file && !allowedTypes.includes(file.type)) errors.image = ['Please choose a JPG, PNG or WEBP image.'];
                else if (file && file.size > 2 * 1024 * 1024) errors.image = ['The image must be 2MB or smaller.'];
                return errors;
            };

            form.addEventListener('submit', (e) => {
                if (busy) { e.preventDefault(); return; }   // blocks duplicate submits (e.g. double Enter)
                clearErrors();
                const errors = validate();
                if (Object.keys(errors).length) {
                    e.preventDefault();
                    showErrors(errors);
                    return;
                }
                // Normal form submission continues; the server answer decides what happens next.
                busy = true;
                setState('loading');
                setTimeout(() => { btn.disabled = true; }, 0);
            });

            // Back button after a save must not leave the button stuck.
            window.addEventListener('pageshow', (e) => {
                if (e.persisted) { busy = false; btn.disabled = false; setState('idle'); }
            });

            /* ----- new image preview ----- */
            const input = document.getElementById('image');
            const placeholder = document.getElementById('placeholder');
            const preview = document.getElementById('preview');
            const previewImg = document.getElementById('previewImg');
            const fileName = document.getElementById('fileName');
            const removeBtn = document.getElementById('removeImage');
            let objectUrl = null;

            const resetImage = () => {
                input.value = '';
                if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
                previewImg.removeAttribute('src');
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                removeBtn.classList.add('hidden');
                removeBtn.classList.remove('inline-flex');
            };

            input.addEventListener('change', () => {
                const file = input.files[0];
                form.querySelector('[data-error="image"]').classList.add('hidden');
                document.getElementById('dropzone').classList.remove('field-invalid');
                if (!file) return resetImage();

                const errors = validate();
                if (errors.image) {
                    resetImage();
                    showErrors({ image: errors.image });
                    return;
                }
                if (objectUrl) URL.revokeObjectURL(objectUrl);
                objectUrl = URL.createObjectURL(file);
                previewImg.src = objectUrl;
                fileName.textContent = file.name;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
                removeBtn.classList.remove('hidden');
                removeBtn.classList.add('inline-flex');
            });
            removeBtn.addEventListener('click', resetImage);
        })();
    </script>
</body>
</html>