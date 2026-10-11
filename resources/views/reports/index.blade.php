@extends('layouts.app')

@section('title', 'Laporan Monitoring Hafalan Al-Qur\'an - 3T App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6" 
     x-data="{
         signTitle: '{{ $signTitle }}',
         signName: '{{ $signName }}',
         activePreset: 'tahfizh',
         teachers: {
             tahfizh: '{{ $setting->teacher_tahfizh ?? 'Ust. Ichsan' }}',
             tahsin: '{{ $setting->teacher_tahsin ?? 'Ust. Ahmad Fauzi, S.Pd.I' }}',
             takhassus: '{{ $setting->teacher_takhassus ?? 'Ust. Ichsan' }}',
             mapel: '{{ $setting->teacher_tahfizh ?? 'Ust. Ichsan' }}'
         },
         selectTeacher(role, title, name) {
             this.activePreset = role;
             this.signTitle = title;
             this.signName = name;
         }
     }">

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Monitoring Hafalan Al-Qur'an</h1>
            <p class="text-xs sm:text-sm text-slate-500">Format cetak monitoring per Surat per Kelas (campuran Tahsin, Takhassus, Tahfizh) siap cetak kertas A4 Landscape</p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a :href="'{{ route('reports.print-a4-landscape') }}?class_name={{ $selectedClass }}&surah_id={{ $selectedSurah->id }}&semester={{ $selectedSemester }}&sign_title=' + encodeURIComponent(signTitle) + '&sign_name=' + encodeURIComponent(signName)" 
               target="_blank"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all cursor-pointer">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Lembar A4 Landscape</span>
            </a>
        </div>
    </div>

    <!-- Filter Control Card -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs space-y-5">
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-10 gap-4 items-end">
            
            <!-- Pilih Kelas (7A - 9D) -->
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Pilih Kelas</label>
                <select name="class_name" onchange="this.form.submit()" class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-300 font-extrabold bg-slate-50 focus:bg-white text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    @foreach ($classes as $cls)
                        <option value="{{ $cls }}" {{ $selectedClass === $cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Surat (Dropdown 114 Surah) -->
            <div class="sm:col-span-5 relative">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Target Surat Al-Qur'an</label>
                <select name="surah_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-300 font-bold bg-slate-50 focus:bg-white text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    @foreach ($allSurahs as $sur)
                        <option value="{{ $sur->id }}" {{ $selectedSurah->id === $sur->id ? 'selected' : '' }}>
                            {{ $sur->number }}. {{ $sur->name_latin }} ({{ $sur->total_verses }} Ayat) - Juz {{ $sur->juz_start }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Semester -->
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Semester</label>
                <select name="semester" onchange="this.form.submit()" class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-300 font-bold bg-slate-50 focus:bg-white text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    <option value="1" {{ $selectedSemester == '1' ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ $selectedSemester == '2' ? 'selected' : '' }}>Semester 2</option>
                </select>
            </div>
        </form>

        <!-- Kustomisasi Pilihan Tanda Tangan Guru (Bagian Kanan Saja) -->
        <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 space-y-3.5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-emerald-200/60 pb-2">
                <div>
                    <h3 class="text-xs font-extrabold text-emerald-950 uppercase tracking-wider">
                        Pilih Guru untuk Tanda Tangan Laporan (Sebelah Kanan Bawah)
                    </h3>
                    <p class="text-[11px] text-emerald-800">Klik salah satu guru di bawah ini untuk langsung menentukan tanda tangan laporan yang akan dicetak.</p>
                </div>
            </div>

            <!-- 4 Quick Choice Buttons: Guru Tahfizh, Guru Tahsin, Guru Takhassus, Guru Mapel -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <!-- Guru Tahfizh -->
                <button type="button" 
                        @click="selectTeacher('tahfizh', 'Guru Tahfizh', teachers.tahfizh)"
                        :class="activePreset === 'tahfizh' ? 'bg-emerald-800 text-white shadow-sm ring-2 ring-emerald-500' : 'bg-white text-slate-800 hover:bg-emerald-100/50 border border-emerald-200'"
                        class="p-2.5 rounded-xl text-left transition-all cursor-pointer">
                    <span class="block text-[10px] font-extrabold uppercase" :class="activePreset === 'tahfizh' ? 'text-amber-300' : 'text-emerald-800'">Guru Tahfizh</span>
                    <span class="block text-xs font-bold truncate mt-0.5" x-text="teachers.tahfizh"></span>
                </button>

                <!-- Guru Tahsin -->
                <button type="button" 
                        @click="selectTeacher('tahsin', 'Guru Tahsin', teachers.tahsin)"
                        :class="activePreset === 'tahsin' ? 'bg-emerald-800 text-white shadow-sm ring-2 ring-emerald-500' : 'bg-white text-slate-800 hover:bg-emerald-100/50 border border-emerald-200'"
                        class="p-2.5 rounded-xl text-left transition-all cursor-pointer">
                    <span class="block text-[10px] font-extrabold uppercase" :class="activePreset === 'tahsin' ? 'text-amber-300' : 'text-emerald-800'">Guru Tahsin</span>
                    <span class="block text-xs font-bold truncate mt-0.5" x-text="teachers.tahsin"></span>
                </button>

                <!-- Guru Takhassus -->
                <button type="button" 
                        @click="selectTeacher('takhassus', 'Guru Takhassus', teachers.takhassus)"
                        :class="activePreset === 'takhassus' ? 'bg-emerald-800 text-white shadow-sm ring-2 ring-emerald-500' : 'bg-white text-slate-800 hover:bg-emerald-100/50 border border-emerald-200'"
                        class="p-2.5 rounded-xl text-left transition-all cursor-pointer">
                    <span class="block text-[10px] font-extrabold uppercase" :class="activePreset === 'takhassus' ? 'text-amber-300' : 'text-emerald-800'">Guru Takhassus</span>
                    <span class="block text-xs font-bold truncate mt-0.5" x-text="teachers.takhassus"></span>
                </button>

                <!-- Guru Mapel Al-Qur'an -->
                <button type="button" 
                        @click="selectTeacher('mapel', 'Guru Mapel Al-Qur\'an', teachers.tahfizh)"
                        :class="activePreset === 'mapel' ? 'bg-emerald-800 text-white shadow-sm ring-2 ring-emerald-500' : 'bg-white text-slate-800 hover:bg-emerald-100/50 border border-emerald-200'"
                        class="p-2.5 rounded-xl text-left transition-all cursor-pointer">
                    <span class="block text-[10px] font-extrabold uppercase" :class="activePreset === 'mapel' ? 'text-amber-300' : 'text-emerald-800'">Guru Mapel Al-Qur'an</span>
                    <span class="block text-xs font-bold truncate mt-0.5" x-text="teachers.mapel"></span>
                </button>
            </div>

            <!-- Detail Input Jabatan & Nama -->
            <x-collapsible-panel trigger="Atau isi manual">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Jabatan Tanda Tangan</label>
                        <input type="text" x-model="signTitle" @input="activePreset = 'custom'" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-white font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Guru Yang Menandatangani</label>
                        <input type="text" x-model="signName" @input="activePreset = 'custom'" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-white font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </x-collapsible-panel>
        </div>
    </div>

    <!-- Live Preview Sheet Card (Matching A4 Landscape Layout) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-4">
        
        <!-- Header Kop Laporan -->
        <div class="text-center space-y-1 pb-2">
            <h2 class="text-base sm:text-lg font-extrabold tracking-wide uppercase text-slate-900">
                {{ $setting->school_name }}
            </h2>
            <h3 class="text-sm sm:text-base font-extrabold tracking-wider uppercase text-emerald-800">
                LAPORAN MONITORING HAFALAN AL-QUR'AN
            </h3>
            <p class="text-xs font-bold text-slate-600">
                TAHUN PELAJARAN {{ $setting->academic_year }}
            </p>
        </div>

        <hr class="border-t-2 border-slate-800 my-2">

        <!-- Meta Information Box -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between text-xs leading-relaxed gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <div class="space-y-1">
                <div><span class="font-semibold text-slate-500">Rombongan Belajar:</span> <strong class="text-slate-900">Kelas {{ $selectedClass }}</strong></div>
                <div><span class="font-semibold text-slate-500">Program Pembinaan:</span> <strong class="text-emerald-800">Takhassus • Tahsin • Tahfizh</strong></div>
                <div><span class="font-semibold text-slate-500">Total Murid:</span> <strong class="text-slate-900">{{ count($students) }} Siswa</strong> (Urut A-Z)</div>
            </div>

            <div class="space-y-1 sm:text-right">
                <div>
                    <span class="font-semibold text-slate-500">Target Surat:</span> 
                    <strong class="text-slate-900">{{ $selectedSurah->name_latin }}</strong> 
                    <span class="font-arabic font-bold text-slate-800 text-xs">({{ $selectedSurah->name_arabic }})</span>
                </div>
                <div><span class="font-semibold text-slate-500">Semester & Target Ayat:</span> <strong class="text-slate-900">Semester {{ $selectedSemester }} • {{ $selectedSurah->total_verses }} Ayat</strong></div>
                <div><span class="font-semibold text-slate-500">Tanggal Cetak:</span> <strong class="text-slate-900">{{ now()->translatedFormat('d F Y') }}</strong></div>
            </div>
        </div>

        <!-- Table Matrix Checklist Preview -->
        <div class="overflow-x-auto border border-slate-300 rounded-2xl">
            <table class="w-full border-collapse text-[11px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-900 text-center font-bold">
                        <th class="border border-slate-300 py-2 px-2 w-8 text-center">No</th>
                        <th class="border border-slate-300 py-2 px-3 text-left min-w-[160px]">Nama Murid</th>
                        
                        @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                            <th class="border border-slate-300 py-1.5 px-0.5 text-center font-extrabold text-[10px] min-w-[18px]">
                                {{ $v }}
                            </th>
                        @endfor

                        <th class="border border-slate-300 py-2 px-2 w-12 text-center font-extrabold">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($studentsData as $row)
                        <tr class="hover:bg-slate-50 text-slate-900">
                            <!-- No -->
                            <td class="border border-slate-300 py-1.5 px-1 text-center font-bold text-slate-500">
                                {{ $row['no'] }}
                            </td>
                            
                            <!-- Nama Murid -->
                            <td class="border border-slate-300 py-1.5 px-3 font-bold">
                                {{ $row['student']->name }}
                            </td>

                            <!-- Verses 1 to N -->
                            @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                                @php
                                    $isMemorized = isset($row['memorized_verses'][$v]);
                                @endphp
                                <td class="border border-slate-300 p-0 text-center align-middle {{ $isMemorized ? 'bg-emerald-100 text-emerald-900 font-extrabold' : 'bg-white' }}">
                                    @if ($isMemorized)
                                        <span class="text-xs">✓</span>
                                    @endif
                                </td>
                            @endfor

                            <!-- Percentage % -->
                            <td class="border border-slate-300 py-1.5 px-1 text-center font-extrabold {{ $row['percentage'] == 100 ? 'text-emerald-800 bg-emerald-50' : 'text-slate-700' }}">
                                {{ $row['percentage'] }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $selectedSurah->total_verses + 3 }}" class="py-8 text-center text-slate-400">
                                Belum ada data murid di Kelas {{ $selectedClass }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Signatures Preview (Sebelah Kanan Saja) -->
        <div class="pt-6 flex justify-end">
            <div class="w-64 space-y-12 text-center bg-slate-50 p-4 rounded-2xl border border-slate-200">
                <div>
                    <p class="font-bold text-slate-900" x-text="signTitle + ','"></p>
                </div>
                <div class="pt-4">
                    <p class="font-extrabold text-slate-900 underline underline-offset-4" x-text="signName"></p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
