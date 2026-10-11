@props(['trigger' => 'Tampilkan Lainnya', 'open' => false])

<div x-data="{ open: @js($open) }">
    <button type="button" @click="open = !open"
            class="flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
        <span>{{ $trigger }}</span>
    </button>
    <div x-show="open" x-collapse class="mt-3">
        {{ $slot }}
    </div>
</div>
