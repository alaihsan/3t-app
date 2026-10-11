@extends('layouts.app')

@section('title', 'Import Murid Copas Excel - 3T App')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6" x-data="excelImportPreview()">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('students.index') }}" class="hover:text-emerald-700">Manajemen Murid</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Import Copas Excel</span>
    </div>

    <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 text-white p-6 rounded-3xl shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-islamic-pattern pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight">Import Murid Cepat (Copas Excel)</h1>
                <p class="text-xs sm:text-sm text-emerald-200 mt-1">Cukup copy tabel dari Microsoft Excel atau Google Sheets lalu paste di kolom bawah.</p>
            </div>
            <button type="button" @click="fillSampleData()" class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-500 text-emerald-950 font-bold text-xs shadow-md transition-all self-start sm:self-auto cursor-pointer">
                Gunakan Contoh Format Excel
            </button>
        </div>
    </div>

    <!-- Format Guide -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <x-collapsible-panel trigger="Lihat Format Kolom Excel">
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-amber-900 text-xs space-y-1">
                <p>Susunan kolom pada file Excel/Spreadsheet Anda berurutan <strong>5 kolom</strong> sebagai berikut:</p>
                <div class="overflow-x-auto py-2">
                    <table class="bg-white border border-amber-200 rounded-lg text-left text-[11px] font-mono">
                        <tr class="bg-amber-100/60 font-bold text-amber-950">
                            <th class="p-2 border-r border-amber-200">Kolom 1</th>
                            <th class="p-2 border-r border-amber-200">Kolom 2</th>
                            <th class="p-2 border-r border-amber-200">Kolom 3</th>
                            <th class="p-2 border-r border-amber-200">Kolom 4</th>
                            <th class="p-2">Kolom 5</th>
                        </tr>
                        <tr>
                            <td class="p-2 border-r border-amber-200">NIS (e.g. 20240701)</td>
                            <td class="p-2 border-r border-amber-200">Nama Murid</td>
                            <td class="p-2 border-r border-amber-200">Jenis Kelamin (L / P)</td>
                            <td class="p-2 border-r border-amber-200">Kelas (7A - 9D)</td>
                            <td class="p-2">Program 3T (Tahfizh / Takhassus / Tahsin)</td>
                        </tr>
                    </table>
                </div>
                <p class="text-slate-600 text-[11px]">Catatan: Jika murid mengikuti lebih dari 1 program (multi-kategori), pisahkan dengan koma pada kolom 5 (contoh: <code>Tahsin, Tahfizh</code>).</p>
            </div>
        </x-collapsible-panel>
    </div>

    <!-- Paste Form & Live Preview -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <form action="{{ route('students.process-import-excel') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Area Tempel / Paste Data Excel
                    </label>
                    <span class="text-xs text-slate-400">Tekan <kbd class="px-1.5 py-0.5 rounded bg-slate-100 border text-slate-600 font-mono text-[10px]">Ctrl+V / Cmd+V</kbd> di dalam kotak</span>
                </div>
                <textarea name="paste_data" 
                          x-model="rawPasteText" 
                          @input="parseData()" 
                          rows="7" 
                          placeholder="Salin 5 kolom murid dari Excel (NIS, Nama, Gender, Kelas, Program 3T) dan tempel di sini..." 
                          class="w-full font-mono text-xs p-3.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50/50"></textarea>
            </div>

            <!-- Live Table Preview -->
            <div x-show="parsedRows.length > 0" class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Live Preview Data (<span x-text="parsedRows.length"></span> Baris Dideteksi)
                    </h3>
                    <span class="text-xs text-emerald-700 font-semibold">Pastikan data di bawah sudah rapi sebelum klik Simpan</span>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl max-h-60 overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 sticky top-0 border-b border-slate-200 text-slate-600 font-semibold">
                            <tr>
                                <th class="p-2.5">No</th>
                                <th class="p-2.5">NIS</th>
                                <th class="p-2.5">Nama Murid</th>
                                <th class="p-2.5">L/P</th>
                                <th class="p-2.5">Kelas</th>
                                <th class="p-2.5">Program 3T</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(row, idx) in parsedRows" :key="idx">
                                <tr class="hover:bg-emerald-50/40">
                                    <td class="p-2.5 text-slate-400 font-mono" x-text="idx + 1"></td>
                                    <td class="p-2.5 font-mono font-medium text-slate-800" x-text="row.nis"></td>
                                    <td class="p-2.5 font-bold text-slate-900" x-text="row.name"></td>
                                    <td class="p-2.5 font-semibold" :class="row.gender === 'L' ? 'text-blue-700' : 'text-rose-700'" x-text="row.gender"></td>
                                    <td class="p-2.5">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 font-extrabold text-slate-800 border" x-text="row.class_name"></span>
                                    </td>
                                    <td class="p-2.5">
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="cat in row.categories" :key="cat">
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800" x-text="cat"></span>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('students.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs">
                    Batal
                </a>
                <button type="submit" 
                        :disabled="parsedRows.length === 0" 
                        class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                    ✓ Simpan ke Database (<span x-text="parsedRows.length"></span> Murid)
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function excelImportPreview() {
    return {
        rawPasteText: '',
        parsedRows: [],
        parseData() {
            const lines = this.rawPasteText.split(/\r\n|\r|\n/);
            const results = [];

            lines.forEach((line, index) => {
                const trimmed = line.trim();
                if (!trimmed) return;

                let cols = [];
                if (trimmed.includes('\t')) {
                    cols = trimmed.split('\t');
                } else if (trimmed.includes(';')) {
                    cols = trimmed.split(';');
                } else {
                    cols = trimmed.split(',');
                }

                cols = cols.map(c => c.trim().replace(/^["']|["']$/g, ''));

                // Skip header if detected
                if (index === 0 && (cols[0].toLowerCase().includes('nis') || cols[1]?.toLowerCase().includes('nama'))) {
                    return;
                }

                if (cols.length >= 4) {
                    const gender = (cols[2].toUpperCase().startsWith('P') || cols[2].toUpperCase().startsWith('W')) ? 'P' : 'L';
                    const className = cols[3].toUpperCase().replace(/\s+/g, '');
                    
                    // Parse categories
                    const catRaw = cols[4] || 'Tahfizh';
                    const categories = [];
                    if (catRaw.toLowerCase().includes('takhassus')) categories.push('Takhassus');
                    if (catRaw.toLowerCase().includes('tahsin')) categories.push('Tahsin');
                    if (catRaw.toLowerCase().includes('tahfizh')) categories.push('Tahfizh');
                    if (categories.length === 0) categories.push('Tahfizh');

                    results.push({
                        nis: cols[0],
                        name: cols[1],
                        gender: gender,
                        class_name: className,
                        categories: categories
                    });
                }
            });

            this.parsedRows = results;
        },
        fillSampleData() {
            this.rawPasteText = `202407011\tAhmad Raihan\tL\t7A\tTahfizh, Takhassus
202407012\tZaskia Mecca\tP\t7A\tTahfizh
202407013\tFaris Al-Baqir\tL\t7B\tTahsin, Tahfizh
202408014\tSyifa Fauziah\tP\t8C\tTahsin
202409015\tTariq bin Ziyad\tL\t9A\tTakhassus`;
            this.parseData();
        }
    }
}
</script>
@endpush
@endsection
