@extends('layouts.app')

@section('title', 'Catat Hafalan 3T - ' . $selectedClass . ' - ' . $selectedSurah->name_latin)

@section('content')
<div class="max-w-[100vw] px-4 sm:px-6 lg:px-8 pt-4 pb-16 space-y-4" x-data="matrixMemorizationEngine()">

    <!-- 1. Top Level Selector: Tingkat & Semester -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
        
        <!-- Tingkat: Kelas 7, Kelas 8, Kelas 9 -->
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 mr-1">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                TINGKAT:
            </span>
            @foreach (['7' => 'Kelas 7', '8' => 'Kelas 8', '9' => 'Kelas 9'] as $lvl => $label)
                <a href="{{ route('memorization.index', ['level' => $lvl, 'semester' => $selectedSemester, 'surah_id' => $selectedSurah->id, 'category' => $selectedCategory]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $gradeLevel == $lvl ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Semester: Semester 1, Semester 2 -->
        <div class="flex items-center gap-2 flex-wrap self-start sm:self-center">
            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 mr-1">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                SEMESTER:
            </span>
            @foreach (['1' => 'Semester 1', '2' => 'Semester 2'] as $sem => $label)
                <a href="{{ route('memorization.index', ['level' => $gradeLevel, 'class_name' => $selectedClass, 'semester' => $sem, 'surah_id' => $selectedSurah->id, 'category' => $selectedCategory]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $selectedSemester == $sem ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- 2. Rombel Bar & Teacher Info Card -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Rombel Selection Pills -->
        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-500">Pilih Rombongan Belajar (Rombel):</label>
            <div class="flex items-center gap-2 flex-wrap">
                @foreach ($classesInLevel as $cls)
                    @php $cnt = $classCounts[$cls] ?? 0; @endphp
                    <a href="{{ route('memorization.index', ['level' => $gradeLevel, 'class_name' => $cls, 'semester' => $selectedSemester, 'surah_id' => $selectedSurah->id, 'category' => $selectedCategory]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-extrabold transition-all border {{ $selectedClass === $cls ? 'bg-emerald-700 border-emerald-800 text-white shadow-sm ring-2 ring-emerald-500/30' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                        <span>Kelas {{ $cls }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $selectedClass === $cls ? 'bg-emerald-850 text-amber-300' : 'bg-slate-100 text-slate-600' }}">
                            {{ $cnt }} Murid
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Teacher / Guru Pembimbing 3T Badge -->
        <div class="bg-emerald-50/80 border border-emerald-200/80 rounded-2xl p-2.5 px-4 flex items-center justify-between gap-4 self-start lg:self-center">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-bold text-xs shadow-2xs flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold text-emerald-800 uppercase tracking-wider">GURU PEMBIMBING {{ strtoupper($selectedCategory) }}</span>
                    <span class="text-xs font-bold text-slate-800">
                        @if ($selectedCategory === 'Tahsin')
                            {{ $setting->teacher_tahsin ?? 'Ust. Ahmad Fauzi, S.Pd.I' }}
                        @elseif ($selectedCategory === 'Takhassus')
                            {{ $setting->teacher_takhassus ?? 'Ust. Ichsan' }}
                        @else
                            {{ $setting->teacher_tahfizh ?? 'Ust. Ichsan' }}
                        @endif
                    </span>
                </div>
            </div>
            <a href="{{ route('settings.index') }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 bg-white px-2 py-1 rounded-lg border border-emerald-200 shadow-2xs">
                Ubah
            </a>
        </div>
    </div>

    <!-- 3. Target Hafalan Surat & Searchable Dropdown Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Left: Current Surah Target Info -->
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center flex-shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">TARGET HAFALAN SURAT:</span>
                    <span class="px-2 py-0.2 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Sem {{ $selectedSemester }}</span>
                </div>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">
                        {{ $selectedSurah->name_latin }} 
                        <span class="font-arabic text-amber-600 text-lg font-bold">({{ $selectedSurah->name_arabic }})</span>
                    </h2>
                    <span class="text-xs text-slate-500 font-medium">
                        • Surat ke-{{ $selectedSurah->number }} ({{ $selectedSurah->total_verses }} Ayat) • Juz {{ $selectedSurah->juz_start }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Right: Searchable Surah Dropdown Selector -->
        <div class="flex items-center gap-2.5 self-start md:self-center w-full md:w-auto" x-data="{ surahSearchOpen: false, surahFilter: '' }">
            <span class="text-xs font-bold text-slate-500 whitespace-nowrap">Pilih Surat:</span>
            
            <div class="relative w-full md:w-80">
                <button type="button" 
                        @click="surahSearchOpen = !surahSearchOpen" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-xs font-bold text-slate-800 shadow-2xs cursor-pointer">
                    <span class="truncate">{{ $selectedSurah->number }}. {{ $selectedSurah->name_latin }} ({{ $selectedSurah->total_verses }} Ayat)</span>
                    <svg class="w-4 h-4 text-slate-400 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Searchable Dropdown Popup -->
                <div x-show="surahSearchOpen" 
                     x-cloak
                     @click.outside="surahSearchOpen = false" 
                     class="absolute right-0 mt-1.5 w-80 max-h-80 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden flex flex-col">
                    
                    <div class="p-2 border-b border-slate-100 bg-slate-50">
                        <input type="text" 
                               x-model="surahFilter" 
                               placeholder="Cari surat (nama / nomor)..." 
                               class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                    </div>

                    <div class="overflow-y-auto divide-y divide-slate-100 text-xs">
                        @foreach ($allSurahs as $sur)
                            <a href="{{ route('memorization.index', ['level' => $gradeLevel, 'class_name' => $selectedClass, 'semester' => $selectedSemester, 'surah_id' => $sur->id, 'category' => $selectedCategory]) }}" 
                               x-show="!surahFilter || '{{ strtolower($sur->name_latin) }} {{ $sur->number }}'.includes(surahFilter.toLowerCase())"
                               class="px-3.5 py-2.5 hover:bg-emerald-50 flex items-center justify-between transition-colors {{ $selectedSurah->id === $sur->id ? 'bg-emerald-50 font-bold text-emerald-800' : 'text-slate-700' }}">
                                <span>{{ $sur->number }}. {{ $sur->name_latin }}</span>
                                <span class="text-[11px] text-slate-400 font-normal">{{ $sur->total_verses }} Ayat (Juz {{ $sur->juz_start }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Program 3T, Grade Nilai, & Mode Edit/View Only Bar -->
    <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        
        <!-- Left: Program 3T & Grade Nilai Tabs -->
        <div class="flex items-center gap-4 flex-wrap">
            <!-- Program 3T Selector -->
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-xs font-bold text-slate-500 mr-1">Program 3T:</span>
                @foreach (['Tahfizh', 'Takhassus', 'Tahsin'] as $cat)
                    <a href="{{ route('memorization.index', ['level' => $gradeLevel, 'class_name' => $selectedClass, 'semester' => $selectedSemester, 'surah_id' => $selectedSurah->id, 'category' => $cat]) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ $selectedCategory === $cat ? 'bg-emerald-800 text-white shadow-2xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <!-- Grade Nilai Selector -->
            <div class="flex items-center gap-1 pl-2 border-l border-slate-200">
                <span class="text-xs font-bold text-slate-500 mr-1">Grade Nilai:</span>
                <button type="button" @click="activeGrade = 'A'" :class="activeGrade === 'A' ? 'bg-emerald-600 text-white font-extrabold ring-2 ring-emerald-400 shadow-2xs' : 'bg-slate-100 text-slate-700'" class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer" title="Grade A (Mutqin)">A</button>
                <button type="button" @click="activeGrade = 'B'" :class="activeGrade === 'B' ? 'bg-blue-600 text-white font-extrabold ring-2 ring-blue-400 shadow-2xs' : 'bg-slate-100 text-slate-700'" class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer" title="Grade B (Lancar)">B</button>
                <button type="button" @click="activeGrade = 'C'" :class="activeGrade === 'C' ? 'bg-amber-500 text-white font-extrabold ring-2 ring-amber-400 shadow-2xs' : 'bg-slate-100 text-slate-700'" class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer" title="Grade C (Cukup)">C</button>
                <button type="button" @click="activeGrade = 'D'" :class="activeGrade === 'D' ? 'bg-rose-500 text-white font-extrabold ring-2 ring-rose-400 shadow-2xs' : 'bg-slate-100 text-slate-700'" class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer" title="Grade D (Kurang)">D</button>
            </div>
        </div>

        <!-- Right: Mode Edit / View Only Button & Autosave Status -->
        <div class="flex items-center gap-2.5 self-start lg:self-center flex-wrap">
            
            <!-- Mode Switch Button -->
            <button type="button" 
                    @click="toggleEditMode()" 
                    :class="isEditMode ? 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 text-slate-950 ring-4 ring-amber-300/80 shadow-md font-extrabold' : 'bg-emerald-800 hover:bg-emerald-900 text-white font-bold shadow-2xs'"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs transition-all duration-200 cursor-pointer">
                <template x-if="isEditMode">
                    <span class="flex items-center gap-1.5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-950 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-950"></span>
                        </span>
                        <span>✏️ Mode Edit (Aktif) — Klik Selesai ✓</span>
                    </span>
                </template>
                <template x-if="!isEditMode">
                    <span class="flex items-center gap-1.5">
                        <span>🔒 Mode View Only (Klik untuk Edit)</span>
                    </span>
                </template>
            </button>

            <!-- Autosave Status Badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border"
                 :class="syncStatus.class">
                <span class="w-2 h-2 rounded-full" :class="syncStatus.dotClass"></span>
                <span x-text="syncStatus.text">✓ Siap mencatat</span>
            </div>
        </div>
    </div>

    <!-- 5. Student Filter & Search Row -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-slate-200/80 shadow-2xs">
        
        <!-- Live Search Student in Table -->
        <div class="relative flex-1 max-w-md">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" 
                   x-model="studentFilter" 
                   placeholder="Cari nama murid di kelas ini..." 
                   class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-slate-50 hover:bg-white">
        </div>

        <!-- Filter Status: Semua, Tuntas, Proses -->
        <div class="flex items-center gap-1.5 self-end sm:self-center text-xs font-semibold">
            <span class="text-slate-400 mr-1 hidden sm:inline">Status:</span>
            <button @click="statusFilter = 'all'" 
                    :class="statusFilter === 'all' ? 'bg-slate-800 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                Semua ({{ count($students) }})
            </button>
            <button @click="statusFilter = 'tuntas'" 
                    :class="statusFilter === 'tuntas' ? 'bg-emerald-700 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                Tuntas (<span x-text="tuntasCount"></span>)
            </button>
            <button @click="statusFilter = 'proses'" 
                    :class="statusFilter === 'proses' ? 'bg-amber-600 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                Proses (<span x-text="prosesCount"></span>)
            </button>
        </div>
    </div>

    <!-- 6. THE SPREADSHEET MATRIX CHECKLIST TABLE -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        @if ($students->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <p class="text-sm font-bold">Belum ada data murid di Kelas {{ $selectedClass }}.</p>
                <p class="text-xs text-slate-400 mt-1">Silakan gunakan menu "Import Copas Excel" untuk menambahkan murid.</p>
            </div>
        @else
            <div class="overflow-x-auto max-h-[70vh]">
                <table class="w-full border-collapse text-xs select-none">
                    
                    <!-- Table Header -->
                    <thead class="sticky top-0 z-30 bg-slate-100/95 backdrop-blur-xs text-slate-700 shadow-2xs">
                        
                        <!-- Row 1: Verse Numbers -->
                        <tr class="border-b border-slate-200 text-center font-bold">
                            <!-- Frozen Column 1: No -->
                            <th class="py-3 px-3 w-12 sticky left-0 z-40 bg-slate-100 border-r border-slate-200 text-center">
                                No
                            </th>
                            <!-- Frozen Column 2: Nama Murid -->
                            <th class="py-3 px-4 min-w-[220px] max-w-[260px] sticky left-12 z-40 bg-slate-100 border-r border-slate-200 text-left">
                                Nama Murid ({{ count($students) }})
                            </th>
                            
                            <!-- Verse Numbers: 1 to N -->
                            @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                                <th class="py-2.5 px-1 min-w-[38px] max-w-[42px] border-r border-slate-200 text-slate-800 font-extrabold text-[12px]">
                                    {{ $v }}
                                </th>
                            @endfor
                        </tr>

                        <!-- Row 2: Per-Verse Summary Count -->
                        <tr class="border-b border-slate-300 text-center font-extrabold text-[11px] bg-slate-50 text-slate-500">
                            <th class="py-2 px-3 sticky left-0 z-40 bg-slate-50 border-r border-slate-200"></th>
                            <th class="py-2 px-4 sticky left-12 z-40 bg-slate-50 border-r border-slate-200 text-left text-[11px] text-slate-500">
                                Total Murid Hafal / Ayat
                            </th>
                            @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                                <th class="py-1.5 px-1 border-r border-slate-200 font-extrabold text-emerald-800 text-[11px]">
                                    <span x-text="verseSummaryCounts[{{ $v }}] || 0"></span>
                                </th>
                            @endfor
                        </tr>
                    </thead>

                    <!-- Table Body: Students & Verses -->
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($students as $index => $st)
                            @php
                                $stId = $st->id;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors"
                                x-show="isStudentVisible({{ $stId }}, '{{ strtolower($st->name) }}')"
                                :key="{{ $stId }}">
                                
                                <!-- Column 1: Row No -->
                                <td class="py-2 px-3 sticky left-0 z-20 bg-white border-r border-slate-200 text-center font-bold text-slate-500">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Column 2: Student Name & Badges -->
                                <td class="py-2 px-4 sticky left-12 z-20 bg-white border-r border-slate-200">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="truncate">
                                            <span class="font-extrabold text-slate-900 text-xs block truncate">{{ $st->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">NIS: {{ $st->nis }}</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span x-show="studentStatsMap[{{ $stId }}]?.is_tuntas" 
                                                  class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold border border-emerald-300">
                                                ✓ Tuntas
                                            </span>
                                            <span x-show="!studentStatsMap[{{ $stId }}]?.is_tuntas && studentStatsMap[{{ $stId }}]?.memorized_count > 0" 
                                                  class="px-1.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[10px] font-bold border border-amber-200"
                                                  x-text="studentStatsMap[{{ $stId }}]?.memorized_count + ' Ayat'">
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Columns 3 to N: Verse Checklist Cells -->
                                @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                                    <td class="p-0.5 border-r border-slate-200 text-center align-middle">
                                        <button type="button" 
                                                @click="toggleCell({{ $stId }}, {{ $v }})"
                                                :class="getCellClass({{ $stId }}, {{ $v }})"
                                                class="w-full h-8 flex items-center justify-center rounded-md font-bold text-[11px] transition-all duration-100 transform active:scale-90 cursor-pointer shadow-2xs select-none"
                                                :title="'Klik untuk centang / batalkan Ayat {{ $v }} - ' + '{{ $st->name }}'">
                                            <template x-if="isMemorized({{ $stId }}, {{ $v }})">
                                                <span>✓</span>
                                            </template>
                                            <template x-if="!isMemorized({{ $stId }}, {{ $v }})">
                                                <span class="text-slate-300 font-normal hover:text-slate-600">{{ $v }}</span>
                                            </template>
                                        </button>
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
function matrixMemorizationEngine() {
    return {
        surahId: {{ $selectedSurah->id }},
        totalVerses: {{ $selectedSurah->total_verses }},
        category: '{{ $selectedCategory }}',
        isEditMode: false,
        activeGrade: 'A',
        studentFilter: '',
        statusFilter: 'all',
        
        // Matrix data loaded from server
        matrix: @json($matrix),
        verseSummaryCounts: @json($verseSummary),
        studentStatsMap: @json($studentStats),
        tuntasCount: {{ $tuntasCount }},
        prosesCount: {{ $prosesCount }},

        syncStatus: {
            text: '✓ Siap',
            class: 'text-slate-700 bg-slate-50 border-slate-200',
            dotClass: 'bg-emerald-500'
        },

        toggleEditMode() {
            this.isEditMode = !this.isEditMode;
            if (this.isEditMode) {
                showToast('✏️ Mode Edit Aktif: Silakan klik kotak nomor ayat untuk mencatat/mengubah hafalan.');
            } else {
                showToast('🔒 Mode View Only Aktif: Data hafalan terkunci rapi.');
            }
        },

        isMemorized(studentId, verseNum) {
            return !!(this.matrix[studentId] && this.matrix[studentId][verseNum]);
        },

        getCellClass(studentId, verseNum) {
            const isChecked = this.isMemorized(studentId, verseNum);
            const cursorClass = this.isEditMode ? 'cursor-pointer hover:scale-105 active:scale-90' : 'cursor-default';

            if (isChecked) {
                const grade = this.matrix[studentId][verseNum].grade || 'A';
                if (grade === 'B') return `bg-blue-600 text-white ${this.isEditMode ? 'hover:bg-blue-700' : ''} font-extrabold ${cursorClass}`;
                if (grade === 'C') return `bg-amber-500 text-white ${this.isEditMode ? 'hover:bg-amber-600' : ''} font-extrabold ${cursorClass}`;
                if (grade === 'D') return `bg-rose-500 text-white ${this.isEditMode ? 'hover:bg-rose-600' : ''} font-extrabold ${cursorClass}`;
                return `bg-emerald-600 text-white ${this.isEditMode ? 'hover:bg-emerald-700' : ''} font-extrabold ${cursorClass}`;
            }
            return `bg-slate-50/60 ${this.isEditMode ? 'hover:bg-emerald-100 text-slate-500' : 'text-slate-300'} ${cursorClass}`;
        },

        isStudentVisible(studentId, studentNameLower) {
            // Search filter
            if (this.studentFilter && !studentNameLower.includes(this.studentFilter.toLowerCase())) {
                return false;
            }

            // Status filter
            const stats = this.studentStatsMap[studentId];
            if (this.statusFilter === 'tuntas' && !stats?.is_tuntas) {
                return false;
            }
            if (this.statusFilter === 'proses' && stats?.is_tuntas) {
                return false;
            }

            return true;
        },

        setSyncing() {
            this.syncStatus = {
                text: 'Menyimpan...',
                class: 'text-amber-800 bg-amber-50 border-amber-200',
                dotClass: 'bg-amber-500 animate-ping'
            };
        },

        setSaved(msg) {
            this.syncStatus = {
                text: msg || '✓ Tersimpan ke database',
                class: 'text-emerald-800 bg-emerald-50 border-emerald-200 font-bold',
                dotClass: 'bg-emerald-500'
            };
        },

        setError() {
            this.syncStatus = {
                text: '✕ Gagal',
                class: 'text-rose-800 bg-rose-50 border-rose-200',
                dotClass: 'bg-rose-500'
            };
        },

        toggleCell(studentId, verseNum) {
            if (!this.isEditMode) {
                showToast('Aktifkan Mode Edit terlebih dahulu untuk mencatat atau mengubah setoran ayat!', 'warning');
                return;
            }

            this.setSyncing();

            // Optimistic Toggle locally
            if (!this.matrix[studentId]) {
                this.matrix[studentId] = {};
            }

            const wasChecked = !!this.matrix[studentId][verseNum];
            if (wasChecked) {
                delete this.matrix[studentId][verseNum];
                this.verseSummaryCounts[verseNum] = Math.max(0, (this.verseSummaryCounts[verseNum] || 1) - 1);
            } else {
                this.matrix[studentId][verseNum] = { grade: this.activeGrade };
                this.verseSummaryCounts[verseNum] = (this.verseSummaryCounts[verseNum] || 0) + 1;
            }

            // Update stats
            const memCount = Object.keys(this.matrix[studentId]).length;
            const isTuntas = memCount >= this.totalVerses;
            this.studentStatsMap[studentId] = {
                memorized_count: memCount,
                is_tuntas: isTuntas
            };

            this.recalculateTotalStats();

            // Send AJAX to DB
            fetch('{{ route('memorization.toggle-verse') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: studentId,
                    surah_id: this.surahId,
                    verse_number: verseNum,
                    category: this.category,
                    grade: this.activeGrade,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.setSaved(`✓ Ayat ${verseNum} tersimpan`);
                } else {
                    this.setError();
                }
            })
            .catch(err => {
                console.error(err);
                this.setError();
            });
        },

        recalculateTotalStats() {
            let tuntas = 0;
            let proses = 0;
            for (const sId in this.studentStatsMap) {
                if (this.studentStatsMap[sId].is_tuntas) {
                    tuntas++;
                } else {
                    proses++;
                }
            }
            this.tuntasCount = tuntas;
            this.prosesCount = proses;
        }
    }
}
</script>
@endpush
@endsection
