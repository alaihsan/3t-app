@extends('layouts.app')

@section('title', $surah->name_latin . " - Al-Qur'an - 3T App")

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-4">

    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('quran.index') }}" class="hover:text-emerald-700">Al-Qur'an</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">{{ $surah->name_latin }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-emerald-700 text-white font-bold text-sm flex items-center justify-center shadow-sm flex-shrink-0">
                {{ $surah->number }}
            </span>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-extrabold text-slate-900 text-lg">{{ $surah->name_latin }}</h1>
                    <span class="font-arabic text-emerald-800 text-base">{{ $surah->name_arabic }}</span>
                </div>
                <p class="text-[11px] text-slate-400">{{ $surah->total_verses }} Ayat &middot; Juz {{ $surah->juz_start }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('quran.show', $surah->number) }}" class="flex items-center gap-2">
            <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Highlight Hafalan Murid</label>
            <select name="student_id" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                <option value="">— Tanpa Highlight —</option>
                @foreach ($students as $s)
                    <option value="{{ $s->id }}" {{ $student && $student->id === $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->class_name }})</option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($unavailable)
        <div class="p-8 text-center bg-white rounded-2xl border border-slate-200/80 shadow-sm text-slate-400 text-sm">
            Konten Al-Qur'an sedang tidak tersedia, coba lagi nanti.
        </div>
    @else
        <x-quran.reader :surah="$surah" :verses="$verses" :memorized-verses="$memorizedVerses" />
    @endif
</div>
@endsection
