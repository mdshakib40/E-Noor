{{-- Expects: $icon, $title, $text --}}
<div class="r-card px-6 py-12 text-center">
    <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl border border-[#d7a858]/20 bg-[#d7a858]/10 text-[#d7a858]">
        <i data-lucide="{{ $icon }}" class="h-6 w-6"></i>
    </span>
    <p class="serif mt-5 text-2xl">{{ $title }}</p>
    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-stone-500">{{ $text }}</p>
</div>