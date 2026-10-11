@extends('layouts.app')

@section('title', 'Dashboard - 3T App')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12 space-y-6" x-data="studentSearch({ autoSearchOnInit: true })">

    <!-- Single Google-Style Big Search Center Section -->
    <div class="text-center py-6 sm:py-8 space-y-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Pencarian Murid <span class="text-emerald-700">3T App</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-1">
                Takhassus • Tahsin • Tahfizh
            </p>
        </div>

        <!-- The Google-Style Big Search Bar -->
        <div class="max-w-2xl mx-auto relative">
            <div class="relative flex items-center bg-white rounded-full border-2 border-emerald-600/30 hover:border-emerald-600 focus-within:border-emerald-600 shadow-lg focus-within:shadow-xl focus-within:shadow-emerald-900/10 transition-all px-4 py-3 sm:py-3.5">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-700 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                
                <input type="text" 
                       x-model="searchQuery" 
                       @input.debounce.200ms="performSearch()"
                       @focus="showResults = true"
                       placeholder="Ketik nama murid, NIS, atau kelas (misal: 'Fatih', '8B', '2024')..." 
                       class="w-full bg-transparent border-0 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-0 text-sm sm:text-base font-medium">

                <!-- Clear button -->
                <button type="button" 
                        x-show="searchQuery" 
                        @click="searchQuery = ''; performSearch()" 
                        class="p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 mr-2 cursor-pointer">
                    ✕
                </button>

            </div>

            <!-- Quick Filter Tags Directly Under the Search Bar -->
            <div class="flex items-center justify-center gap-1.5 pt-3 flex-wrap text-xs">
                <span class="text-slate-400 font-medium mr-1 text-[11px]">Filter:</span>
                <button @click="setClassFilter('all')" :class="activeClass === 'all' ? 'bg-emerald-800 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Semua Kelas</button>
                <button @click="setClassFilter('7')" :class="activeClass === '7' ? 'bg-emerald-800 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Kelas 7</button>
                <button @click="setClassFilter('8')" :class="activeClass === '8' ? 'bg-emerald-800 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Kelas 8</button>
                <button @click="setClassFilter('9')" :class="activeClass === '9' ? 'bg-emerald-800 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Kelas 9</button>
                <span class="text-slate-300 mx-0.5">|</span>
                <button @click="setCategoryFilter('Tahfizh')" :class="activeCategory === 'Tahfizh' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Tahfizh</button>
                <button @click="setCategoryFilter('Takhassus')" :class="activeCategory === 'Takhassus' ? 'bg-emerald-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Takhassus</button>
                <button @click="setCategoryFilter('Tahsin')" :class="activeCategory === 'Tahsin' ? 'bg-teal-600 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100'" class="px-3 py-1 rounded-full border border-slate-200 shadow-2xs transition-all cursor-pointer">Tahsin</button>
            </div>

            <!-- Instant Search Results Panel (Dropdown under Search) -->
            <div x-show="showResults && results.length > 0" 
                 @click.outside="showResults = false"
                 class="absolute left-0 right-0 top-full mt-3 bg-white rounded-3xl shadow-2xl border border-slate-200 text-left overflow-hidden z-40 max-h-[55vh] overflow-y-auto divide-y divide-slate-100">
                
                <div class="p-3 bg-slate-50 border-b border-slate-100 text-xs text-slate-500">
                    <strong class="text-slate-800" x-text="results.length"></strong> murid ditemukan
                </div>

                <template x-for="student in results" :key="student.id">
                    <div class="p-3.5 hover:bg-emerald-50/50 transition-colors flex items-center justify-between gap-4 group">
                        <!-- Student Identity -->
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-xs flex-shrink-0"
                                 :class="student.gender === 'L' ? 'bg-emerald-700' : 'bg-rose-600'">
                                <span x-text="student.name.charAt(0)"></span>
                            </div>
                            <div class="truncate">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm group-hover:text-emerald-800 transition-colors truncate" x-text="student.name"></h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-slate-200 text-slate-700 flex-shrink-0" x-text="student.class_name"></span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                                    <span class="text-emerald-700 font-semibold" x-text="'Setoran Terakhir: ' + student.last_surah"></span>
                                    <span x-show="student.last_grade !== '-'" class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold" x-text="'Grade ' + student.last_grade"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button: Catat Hafalan -->
                        <div class="flex items-center flex-shrink-0">
                            <a :href="student.urls.record" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-extrabold shadow-sm hover:shadow-md transition-all">
                                <span>🟢 Catat Hafalan</span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- 12 Classes Matrix (7A-7D, 8A-8D, 9A-9D) Full Width -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base">Sebaran Murid 12 Kelas (7A s/d 9D)</h3>
                <p class="text-xs text-slate-500">Klik kelas untuk melihat daftar murid atau mencatat hafalan</p>
            </div>
            <a href="{{ route('students.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">
                Semua Murid →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
            @foreach ($classes as $cls)
                @php
                    $cnt = $classCounts[$cls] ?? 0;
                @endphp
                <a href="{{ route('students.index', ['class_name' => $cls]) }}" 
                   class="p-3 rounded-2xl border border-slate-200/80 hover:border-emerald-400 bg-slate-50/50 hover:bg-emerald-50/40 transition-all flex flex-col justify-between group">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-extrabold text-slate-800 group-hover:text-emerald-900">Kelas {{ $cls }}</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $cnt > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-500' }}">
                            {{ $cnt }}
                        </span>
                    </div>
                    <div class="mt-2 text-slate-300 group-hover:text-emerald-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Memorization Activity -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base">Aktivitas Setoran Terkini</h3>
                <p class="text-xs text-slate-500">Riwayat pencatatan hafalan ayat terbaru</p>
            </div>
            <a href="{{ route('memorization.index') }}" class="text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition-colors">
                + Input Setoran Baru
            </a>
        </div>

        @if ($recentActivities->isEmpty())
            <div class="py-8 text-center text-slate-400">
                <p class="text-xs">Belum ada riwayat setoran hafalan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="py-2.5 px-3">Murid</th>
                            <th class="py-2.5 px-3">Kelas</th>
                            <th class="py-2.5 px-3">Surah & Ayat</th>
                            <th class="py-2.5 px-3">Program</th>
                            <th class="py-2.5 px-3">Grade</th>
                            <th class="py-2.5 px-3">Tanggal</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentActivities as $act)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3 font-bold text-slate-900">
                                    {{ $act->student->name ?? '-' }}
                                    <span class="block text-[10px] text-slate-400 font-normal font-mono">NIS: {{ $act->student->nis ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-extrabold text-[11px]">
                                        {{ $act->student->class_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-medium text-emerald-950">
                                    {{ $act->surah->name_latin ?? '-' }} 
                                    <span class="px-1.5 py-0.5 bg-emerald-50 rounded text-emerald-700 font-bold">Ayat {{ $act->verse_number }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                        {{ $act->category === 'Tahfizh' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $act->category === 'Takhassus' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $act->category === 'Tahsin' ? 'bg-teal-100 text-teal-800' : '' }}">
                                        {{ $act->category }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="w-6 h-6 rounded-md flex items-center justify-center font-bold text-xs
                                        {{ $act->grade === 'A' ? 'bg-emerald-600 text-white' : '' }}
                                        {{ $act->grade === 'B' ? 'bg-blue-600 text-white' : '' }}
                                        {{ $act->grade === 'C' ? 'bg-amber-600 text-white' : '' }}
                                        {{ $act->grade === 'D' ? 'bg-rose-600 text-white' : '' }}">
                                        {{ $act->grade }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-500">
                                    {{ $act->recorded_at ? $act->recorded_at->format('d/m/Y') : $act->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('memorization.index', ['student_id' => $act->student_id, 'surah_id' => $act->surah_id]) }}" 
                                       class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-[11px] transition-colors">
                                        Catat ➔
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection
