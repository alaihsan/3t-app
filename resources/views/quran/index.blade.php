@extends('layouts.app')

@section('title', "Al-Qur'an - 3T App")

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

    <div>
        <h1 class="text-xl font-extrabold text-slate-900">Baca Al-Qur'an</h1>
        <p class="text-xs text-slate-500 mt-1">Pilih surah untuk membaca teks Arab, terjemahan, dan progres hafalan murid.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm divide-y divide-slate-100">
        @foreach ($surahs as $surah)
            <a href="{{ route('quran.show', $surah->number) }}"
               class="flex items-center justify-between gap-3 p-4 hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-bold text-xs flex items-center justify-center shadow-sm flex-shrink-0">
                        {{ $surah->number }}
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $surah->name_latin }}</h4>
                            <span class="font-arabic text-emerald-800 text-sm">{{ $surah->name_arabic }}</span>
                        </div>
                        <p class="text-[11px] text-slate-400">{{ $surah->total_verses }} Ayat &middot; Juz {{ $surah->juz_start }}</p>
                    </div>
                </div>
                <span class="text-emerald-600 text-xs">→</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
