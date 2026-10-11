@props(['surah', 'verses', 'memorizedVerses' => []])

<div class="space-y-3">
    @forelse ($verses as $verse)
        @php
            $grade = $memorizedVerses[$verse['verse_number']] ?? null;
            $translationText = $verse['translations'][0]['text'] ?? '';
        @endphp
        <div class="p-4 rounded-2xl border shadow-sm {{ $grade ? 'bg-emerald-50 border-emerald-300' : 'bg-white border-slate-200/80' }}">
            <div class="flex items-center justify-between mb-2.5">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold {{ $grade ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ $verse['verse_number'] }}
                </span>
                @if ($grade)
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-600 text-white">
                        Sudah Hafal &middot; {{ $grade }}
                    </span>
                @endif
            </div>
            <p class="font-arabic text-right text-2xl sm:text-3xl leading-loose text-emerald-950" dir="rtl" lang="ar">
                {!! $verse['text_uthmani'] ?? '' !!}
            </p>
            @if ($translationText)
                <p class="text-xs text-slate-600 mt-3 leading-relaxed">{{ $translationText }}</p>
            @endif
        </div>
    @empty
        <div class="p-8 text-center text-slate-400 text-xs">
            Tidak ada data ayat untuk ditampilkan.
        </div>
    @endforelse
</div>
