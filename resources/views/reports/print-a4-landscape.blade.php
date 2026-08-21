<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring Hafalan Al-Qur'an - Kelas {{ $selectedClass }} - {{ $selectedSurah->name_latin }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 10mm;
        }

        @media print {
            body {
                background: white !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body class="bg-white text-slate-900 font-sans p-2 sm:p-6 antialiased text-xs">

    <!-- Print Action Bar (Hidden on Print) -->
    <div class="no-print max-w-7xl mx-auto mb-6 p-4 rounded-3xl bg-slate-900 text-white flex flex-col md:flex-row items-center justify-between gap-4 shadow-2xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.index', ['class_name' => $selectedClass, 'surah_id' => $selectedSurah->id, 'semester' => $selectedSemester, 'sign_title' => $signTitle, 'sign_name' => $signName]) }}" 
               class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200">
                ← Kembali
            </a>
            <div class="text-xs text-slate-300">
                <span>Format: <strong>A4 Landscape</strong> • Kelas {{ $selectedClass }} • {{ $selectedSurah->name_latin }} ({{ $selectedSurah->total_verses }} Ayat) • Tanda Tangan: <strong>{{ $signTitle }}</strong> ({{ $signName }})</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow-lg transition-all cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Main Print Container -->
    <div class="max-w-[297mm] mx-auto space-y-3">
        
        <!-- Header Kop Surat -->
        <div class="text-center space-y-1">
            <h1 class="text-base sm:text-lg font-extrabold tracking-wide uppercase text-slate-900">
                {{ $setting->school_name }}
            </h1>
            <h2 class="text-sm sm:text-base font-extrabold tracking-wider uppercase text-emerald-800">
                LAPORAN MONITORING HAFALAN AL-QUR'AN
            </h2>
            <p class="text-xs font-bold text-slate-700">
                TAHUN PELAJARAN {{ $setting->academic_year }}
            </p>
        </div>

        <hr class="border-t-2 border-slate-900 my-1.5">

        <!-- Meta Information Box (2 Columns) -->
        <div class="flex items-start justify-between text-[11px] leading-relaxed pb-1">
            <!-- Left Info -->
            <div class="space-y-0.5">
                <div><span class="font-bold text-slate-700">Rombongan Belajar:</span> <strong class="text-slate-900">Kelas {{ $selectedClass }}</strong></div>
                <div><span class="font-bold text-slate-700">Program Pembinaan:</span> <strong class="text-emerald-800">Takhassus • Tahsin • Tahfizh</strong></div>
                <div><span class="font-bold text-slate-700">Total Murid:</span> <strong class="text-slate-900">{{ count($students) }} Siswa</strong> (Urut A-Z)</div>
            </div>

            <!-- Right Info -->
            <div class="space-y-0.5 text-right">
                <div>
                    <span class="font-bold text-slate-700">Target Surat:</span> 
                    <strong class="text-slate-900">{{ $selectedSurah->name_latin }}</strong> 
                    <span class="font-arabic font-bold text-slate-800 text-xs">({{ $selectedSurah->name_arabic }})</span>
                </div>
                <div><span class="font-bold text-slate-700">Semester & Target Ayat:</span> <strong class="text-slate-900">Semester {{ $selectedSemester }} • {{ $selectedSurah->total_verses }} Ayat</strong></div>
                <div><span class="font-bold text-slate-700">Tanggal Cetak:</span> <strong class="text-slate-900">{{ now()->translatedFormat('d F Y') }}</strong></div>
            </div>
        </div>

        <!-- The Monitoring Checklist Table Matrix -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-slate-700 text-[10px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-900 text-center font-bold">
                        <th class="border border-slate-700 py-1.5 px-1 w-7 text-center">No</th>
                        <th class="border border-slate-700 py-1.5 px-2 text-left min-w-[140px] max-w-[180px]">Nama Murid</th>
                        
                        <!-- Verse Columns 1 to N -->
                        @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                            <th class="border border-slate-700 py-1 px-0.5 text-center font-extrabold text-[9px] min-w-[15px]">
                                {{ $v }}
                            </th>
                        @endfor

                        <th class="border border-slate-700 py-1.5 px-1 w-10 text-center font-extrabold">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentsData as $row)
                        <tr class="hover:bg-slate-50 text-slate-900">
                            <!-- No -->
                            <td class="border border-slate-700 py-1 px-1 text-center font-bold">
                                {{ $row['no'] }}
                            </td>
                            
                            <!-- Nama Murid -->
                            <td class="border border-slate-700 py-1 px-2 font-bold truncate">
                                {{ $row['student']->name }}
                            </td>

                            <!-- Verses 1 to N -->
                            @for ($v = 1; $v <= $selectedSurah->total_verses; $v++)
                                @php
                                    $isMemorized = isset($row['memorized_verses'][$v]);
                                @endphp
                                <td class="border border-slate-700 p-0 text-center align-middle {{ $isMemorized ? 'bg-emerald-100 text-emerald-900 font-extrabold' : 'bg-white' }}">
                                    @if ($isMemorized)
                                        <span class="text-[10px] leading-none">✓</span>
                                    @endif
                                </td>
                            @endfor

                            <!-- Percentage % -->
                            <td class="border border-slate-700 py-1 px-1 text-center font-extrabold {{ $row['percentage'] == 100 ? 'text-emerald-800 bg-emerald-50' : 'text-slate-700' }}">
                                {{ $row['percentage'] }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signature Section (Hanya di Sebelah Kanan Saja Sesuai Pilihan Guru) -->
        <div class="pt-6 flex justify-end text-[11px] leading-normal" style="page-break-inside: avoid;">
            <div class="w-64 text-center space-y-12">
                <div>
                    <p class="font-bold text-slate-900">{{ $signTitle }},</p>
                </div>
                <div class="pt-4">
                    <p class="font-extrabold text-slate-900 underline underline-offset-4">{{ $signName }}</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
