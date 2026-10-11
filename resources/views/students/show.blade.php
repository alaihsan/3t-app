@extends('layouts.app')

@section('title', 'Rapor & Rekap Murid - ' . $student->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('students.index') }}" class="hover:text-emerald-700">Manajemen Murid</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Rapor Murid</span>
    </div>

    <!-- Student Profile Header Card -->
    <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-islamic-pattern pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-extrabold text-2xl shadow-lg flex-shrink-0 {{ $student->gender === 'L' ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-rose-400 to-amber-500' }}">
                    {{ substr($student->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white">{{ $student->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-lg bg-emerald-950/80 text-amber-300 font-extrabold text-xs border border-amber-400/30">
                            Kelas {{ $student->class_name }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-200 mt-1 flex-wrap">
                        <span class="font-mono">NIS: {{ $student->nis }}</span>
                        <span>•</span>
                        <span>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @foreach ($student->categories ?? [] as $cat)
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold
                                {{ $cat === 'Tahfizh' ? 'bg-amber-400 text-amber-950' : '' }}
                                {{ $cat === 'Takhassus' ? 'bg-emerald-400 text-emerald-950' : '' }}
                                {{ $cat === 'Tahsin' ? 'bg-teal-400 text-teal-950' : '' }}">
                                {{ $cat }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap sm:flex-col gap-2 flex-shrink-0" x-data="{ shareOpen: false }">
                <a href="{{ route('memorization.index', ['student_id' => $student->id]) }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-emerald-950 font-bold text-xs shadow-md transition-all">
                    <span>Catat Hafalan Sekarang</span>
                </a>
                <div class="relative">
                    <button type="button" @click="shareOpen = !shareOpen" @click.outside="shareOpen = false"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-emerald-800/80 hover:bg-emerald-700 text-white font-medium text-xs border border-emerald-600/50 shadow transition-all">
                        <span>Bagikan ke Orang Tua</span>
                    </button>
                    <div x-show="shareOpen" x-cloak x-transition
                         class="absolute right-0 sm:left-0 top-full mt-2 w-56 rounded-xl bg-white shadow-xl border border-slate-200 overflow-hidden z-20 text-slate-700">
                        <button type="button" @click="navigator.clipboard.writeText('{{ route('share.view', $student->share_token) }}'); showToast('Link Web Read-Only berhasil disalin!'); shareOpen = false"
                                class="w-full text-left px-3.5 py-2.5 text-xs font-semibold hover:bg-emerald-50">
                            Salin Web Link Orang Tua
                        </button>
                        <button type="button" @click="navigator.clipboard.writeText('{{ route('share.api', $student->share_token) }}'); showToast('Link Share API JSON berhasil disalin!'); shareOpen = false"
                                class="w-full text-left px-3.5 py-2.5 text-xs font-mono font-semibold hover:bg-emerald-50 border-t border-slate-100">
                            Salin URL Share API (JSON)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    @php
        $stats = $student->stats;
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Ayat Dihafal</span>
            <div class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $stats['total_verses'] }}</div>
            <span class="text-[11px] text-slate-400">dari {{ number_format(\App\Models\Student::TOTAL_QURAN_VERSES) }} ayat Al-Qur'an</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Surah Disetor</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_surahs'] }}</div>
            <span class="text-[11px] text-slate-400">Surah</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Persentase Total</span>
            <div class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['percentage_total'] }}%</div>
            <span class="text-[11px] text-slate-400">Keseluruhan Qur'an</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Grade Dominan</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">Grade {{ $stats['dominant_grade'] }}</div>
            <span class="text-[11px] text-slate-400">Kualitas Kelancaran</span>
        </div>
    </div>

    <!-- Memorization History per Surah -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg">Detail Rekapitulasi Surah</h3>
                <p class="text-xs text-slate-500">Daftar ayat dan surah yang telah disetorkan oleh murid ini</p>
            </div>
            <span class="px-3 py-1 bg-emerald-50 rounded-xl text-emerald-800 text-xs font-bold">
                {{ $surahGroups->count() }} Surah Aktif
            </span>
        </div>

        @if ($surahGroups->isEmpty())
            <div class="py-12 text-center text-slate-400">
                <p class="text-sm font-medium">Belum ada catatan setoran ayat untuk murid ini.</p>
                <div class="mt-3">
                    <a href="{{ route('memorization.index', ['student_id' => $student->id]) }}" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow transition-all">
                        Mulai Catat Hafalan Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($surahGroups as $surahId => $recordsInSurah)
                    @php
                        $surah = $recordsInSurah->first()->surah;
                        $totalVersesInSurah = $surah->total_verses;
                        $memorizedVersesCount = $recordsInSurah->count();
                        $pctSurah = round(($memorizedVersesCount / $totalVersesInSurah) * 100);
                    @endphp
                    <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition-all space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                    {{ $surah->number }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-extrabold text-slate-900 text-sm">{{ $surah->name_latin }}</h4>
                                        <span class="font-arabic text-emerald-800 text-base">({{ $surah->name_arabic }})</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 font-bold">Juz {{ $surah->juz_start }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400" title="{{ $surah->translation_id }} • {{ $surah->revelation_type }}">{{ $surah->total_verses }} Ayat</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 self-end sm:self-center">
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-800">{{ $memorizedVersesCount }}/{{ $totalVersesInSurah }} Ayat</span>
                                    <span class="text-[10px] text-emerald-700 font-bold block">({{ $pctSurah }}%)</span>
                                </div>
                                <a href="{{ route('quran.show', ['number' => $surah->number, 'student_id' => $student->id]) }}"
                                   class="px-2.5 py-1.5 rounded-lg bg-white border border-emerald-300 hover:bg-emerald-50 text-emerald-800 font-bold text-xs shadow-sm transition-all">
                                    Baca &amp; Highlight
                                </a>
                                <a href="{{ route('memorization.index', ['student_id' => $student->id, 'surah_id' => $surah->id]) }}"
                                   class="px-2.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition-all">
                                    Edit Hafalan ➔
                                </a>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-amber-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pctSurah }}%"></div>
                        </div>

                        <!-- Ayat Pills Grid -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach ($recordsInSurah->sortBy('verse_number') as $rec)
                                <div class="inline-flex items-center gap-1 px-2 py-1 rounded-lg border text-[11px] font-semibold
                                    {{ $rec->grade === 'A' ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : '' }}
                                    {{ $rec->grade === 'B' ? 'bg-blue-50 border-blue-300 text-blue-900' : '' }}
                                    {{ $rec->grade === 'C' ? 'bg-amber-50 border-amber-300 text-amber-900' : '' }}
                                    {{ $rec->grade === 'D' ? 'bg-rose-50 border-rose-300 text-rose-900' : '' }}"
                                     title="Disetor pada: {{ $rec->recorded_at?->format('d/m/Y') }} • Kategori: {{ $rec->category }}">
                                    <span>Ayat {{ $rec->verse_number }}</span>
                                    <span class="px-1 py-0.2 rounded text-[9px] font-extrabold bg-white shadow-xs">
                                        {{ $rec->grade }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
