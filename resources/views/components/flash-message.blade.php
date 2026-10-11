@props(['type', 'message'])

@php
    $config = match ($type) {
        'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'iconBg' => 'bg-emerald-600', 'close' => 'text-emerald-500 hover:text-emerald-700', 'path' => 'M5 13l4 4L19 7'],
        'warning' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'iconBg' => 'bg-amber-500', 'close' => 'text-amber-500 hover:text-amber-700', 'path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        default => ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-800', 'iconBg' => 'bg-rose-600', 'close' => 'text-rose-500 hover:text-rose-700', 'path' => 'M6 18L18 6M6 6l12 12'],
    };
    $items = array_values(array_filter(is_array($message) ? $message : [$message]));
@endphp

@if (count($items))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 no-print">
        <div class="p-4 rounded-2xl {{ $config['bg'] }} border {{ $config['border'] }} {{ $config['text'] }} flex items-start justify-between gap-3 shadow-xs">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl {{ $config['iconBg'] }} text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['path'] }}"></path>
                    </svg>
                </div>
                @if ($type === 'error')
                    <div class="text-xs">
                        <p class="font-semibold">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside mt-1 space-y-0.5">
                            @foreach ($items as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="font-medium text-xs sm:text-sm">{{ $items[0] }}</p>
                @endif
            </div>
            <button type="button" onclick="this.closest('.no-print').remove()" class="{{ $config['close'] }} flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif
