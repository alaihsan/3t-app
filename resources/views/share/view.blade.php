<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progres Hafalan 3T - {{ $student->name }} (Kelas {{ $student->class_name }})</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col pb-16">

    <!-- Public Header -->
    <header class="bg-emerald-900 text-white border-b border-emerald-800 shadow-md">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-sm shadow">
                    3T
                </div>
                <div>
                    <span class="font-extrabold text-sm sm:text-base tracking-tight block">{{ $setting->school_name }}</span>
                    <p class="text-[11px] text-emerald-200">Laporan Perkembangan Hafalan Al-Qur'an (Mode Read-Only)</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-emerald-950/80 text-amber-300 text-xs font-bold border border-amber-400/30">
                TA {{ $setting->academic_year }}
            </span>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 pt-6 space-y-6 flex-1 w-full">

        <!-- Student Profile Card -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-850 to-teal-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-islamic-pattern pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
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
                        <div class="flex items-center gap-2 text-xs text-emerald-200 mt-1">
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
                                    Program {{ $cat }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-right sm:text-right bg-emerald-950/60 p-3.5 rounded-2xl border border-emerald-700/50">
                    <span class="text-[11px] text-emerald-300 font-semibold block">Guru Pembimbing:</span>
                    <span class="text-xs font-bold text-white">{{ $setting->teacher_name }}</span>
                    <span class="text-[10px] text-emerald-400 block mt-0.5">Semester {{ $setting->semester }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        @php
            $percentageTotal = min(100, round(($totalVersesMemorized / $totalQuranVerses) * 100, 2));
            arsort($gradeCounts);
            $dominantGrade = !empty($gradeCounts) ? array_key_first($gradeCounts) : '-';
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Ayat Dihafal</span>
                <div class="text-2xl font-extrabold text-emerald-700 mt-1">{{ number_format($totalVersesMemorized) }}</div>
                <span class="text-[11px] text-slate-400">Ayat Al-Qur'an</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Surah Disetor</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalSurahsTouched }}</div>
                <span class="text-[11px] text-slate-400">Surah</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Progres Al-Qur'an</span>
                <div class="text-2xl font-extrabold text-amber-600 mt-1">{{ $percentageTotal }}%</div>
                <span class="text-[11px] text-slate-400">Keseluruhan 30 Juz</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Predikat / Grade</span>
                <div class="text-2xl font-extrabold text-emerald-600 mt-1">Grade {{ $dominantGrade }}</div>
                <span class="text-[11px] text-slate-400">Kelancaran Setoran</span>
            </div>
        </div>

        <!-- Juz 1-30 Overview Grid -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Peta Capaian Juz (Juz 1 s/d Juz 30)</h3>
                    <p class="text-xs text-slate-500">Status penyelesaian hafalan per juz</p>
                </div>
                <span class="text-[11px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-1 rounded-lg">30 Juz</span>
            </div>

            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2 pt-1">
                @foreach ($juzStats as $j => $jStat)
                    <div class="p-2 rounded-xl text-center border text-xs transition-all {{ $jStat['has_progress'] ? 'bg-emerald-600 border-emerald-700 text-white font-extrabold shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-400 font-medium' }}">
                        <span class="block text-[10px] opacity-80">Juz</span>
                        <span class="text-sm font-extrabold">{{ $j }}</span>
                        @if ($jStat['has_progress'])
                            <span class="block text-[9px] mt-0.5 text-amber-200 font-bold">{{ $jStat['memorized_count'] }} ay</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Memorization Breakdown per Surah -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg">Rincian Riwayat Setoran per Surah</h3>
                <p class="text-xs text-slate-500">Daftar surah beserta nomor ayat dan grade nilai yang telah tuntas disetorkan</p>
            </div>

            @if ($surahGroups->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <p class="text-sm">Belum ada catatan setoran hafalan untuk murid ini.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($surahGroups as $surahId => $recordsInSurah)
                        @php
                            $surah = $recordsInSurah->first()->surah;
                            $memorizedCount = $recordsInSurah->count();
                            $pct = round(($memorizedCount / $surah->total_verses) * 100);
                        @endphp
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                        {{ $surah->number }}
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-extrabold text-slate-900 text-sm">{{ $surah->name_latin }}</h4>
                                            <span class="font-arabic text-emerald-800 text-base">({{ $surah->name_arabic }})</span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">{{ $surah->translation_id }} • {{ $surah->total_verses }} Ayat • Juz {{ $surah->juz_start }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-extrabold text-slate-800">{{ $memorizedCount }} / {{ $surah->total_verses }} Ayat</span>
                                    <span class="text-[10px] text-emerald-700 font-bold block">({{ $pct }}%)</span>
                                    <a href="{{ route('share.quran-show', ['share_token' => $student->share_token, 'number' => $surah->number]) }}" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold underline">
                                        Baca Al-Qur'an →
                                    </a>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-amber-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>

                            <!-- Ayat Badges -->
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @foreach ($recordsInSurah->sortBy('verse_number') as $rec)
                                    <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border text-[11px] font-semibold
                                        {{ $rec->grade === 'A' ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : '' }}
                                        {{ $rec->grade === 'B' ? 'bg-blue-50 border-blue-300 text-blue-900' : '' }}
                                        {{ $rec->grade === 'C' ? 'bg-amber-50 border-amber-300 text-amber-900' : '' }}
                                        {{ $rec->grade === 'D' ? 'bg-rose-50 border-rose-300 text-rose-900' : '' }}">
                                        <span>Ayat {{ $rec->verse_number }}</span>
                                        <span class="px-1 rounded text-[9px] font-extrabold bg-white shadow-xs">{{ $rec->grade }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </main>

    <!-- Public Footer -->
    <footer class="text-center text-xs text-slate-400 mt-8">
        <p>{{ $setting->school_name }} • Sistem Pencatatan 3T App</p>
    </footer>

</body>
</html>
