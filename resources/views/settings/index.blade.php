@extends('layouts.app')

@section('title', 'Pengaturan Sekolah & Guru 3T - 3T App')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Sekolah & Guru 3T</h1>
        <p class="text-xs sm:text-sm text-slate-500">Konfigurasi identitas sekolah, logo kop laporan, tahun ajaran, dan daftar Guru Pembimbing 3T (Takhassus, Tahsin, Tahfizh)</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Logo Sekolah Section -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-24 h-24 rounded-2xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center p-2 flex-shrink-0 shadow-inner">
                    @if ($setting->logo_path && file_exists(public_path($setting->logo_path)))
                        <img src="{{ asset($setting->logo_path) }}" alt="Logo Sekolah" class="max-h-20 max-w-full object-contain">
                    @else
                        <div class="text-center text-slate-400">
                            <span class="text-2xl block">🏫</span>
                            <span class="text-[9px] font-bold block mt-1">Logo Sekolah</span>
                        </div>
                    @endif
                </div>
                <div class="space-y-1.5 flex-1 text-center sm:text-left">
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Unggah Logo Sekolah (Pojok Kiri Atas Kop Laporan)
                    </label>
                    <input type="file" name="logo" accept="image/*" class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                    <p class="text-[11px] text-slate-400">Format yang didukung: PNG, JPG, JPEG, WebP, SVG (Maks. 2MB). Logo ini akan muncul pada cetak Laporan A4 Landscape.</p>
                </div>
            </div>

            <!-- Nama Sekolah & Alamat -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Sekolah / Lembaga <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="school_name" value="{{ old('school_name', $setting->school_name) }}" required 
                           placeholder="Contoh: SMP ISLAM AL AZHAR 22 SENTRA PRIMER" 
                           class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Alamat Lengkap Sekolah
                    </label>
                    <textarea name="school_address" rows="2" 
                              class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500">{{ old('school_address', $setting->school_address) }}</textarea>
                </div>
            </div>

            <!-- Tahun Ajaran & Semester -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tahun Ajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $setting->academic_year) }}" required 
                           placeholder="Contoh: 2025/2026" 
                           class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Semester Aktif <span class="text-rose-500">*</span>
                    </label>
                    <select name="semester" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 font-bold">
                        <option value="1" {{ old('semester', $setting->semester) == '1' || old('semester', $setting->semester) === 'Ganjil' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ old('semester', $setting->semester) == '2' || old('semester', $setting->semester) === 'Genap' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>
            </div>

            <!-- Daftar Guru Pembimbing 3T (Takhassus, Tahsin, Tahfizh) -->
            <div class="p-5 rounded-3xl bg-emerald-50/70 border border-emerald-200/80 space-y-4">
                <div>
                    <h3 class="text-sm font-extrabold text-emerald-950">Daftar Guru Pembimbing 3T</h3>
                    <p class="text-xs text-emerald-700 mt-0.5">Nama guru pembimbing untuk program Takhassus, Tahsin, dan Tahfizh yang akan tercantum pada monitoring dan tanda tangan laporan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Guru Takhassus -->
                    <div class="space-y-1 bg-white p-3.5 rounded-2xl border border-emerald-200 shadow-2xs">
                        <label class="block text-xs font-extrabold text-emerald-900">
                            Guru Takhassus
                        </label>
                        <input type="text" name="teacher_takhassus" value="{{ old('teacher_takhassus', $setting->teacher_takhassus ?? 'Ust. Ichsan') }}" 
                               placeholder="Nama Guru Takhassus" 
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 font-bold">
                    </div>

                    <!-- Guru Tahsin -->
                    <div class="space-y-1 bg-white p-3.5 rounded-2xl border border-emerald-200 shadow-2xs">
                        <label class="block text-xs font-extrabold text-emerald-900">
                            Guru Tahsin
                        </label>
                        <input type="text" name="teacher_tahsin" value="{{ old('teacher_tahsin', $setting->teacher_tahsin ?? 'Ust. Ahmad Fauzi, S.Pd.I') }}" 
                               placeholder="Nama Guru Tahsin" 
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 font-bold">
                    </div>

                    <!-- Guru Tahfizh -->
                    <div class="space-y-1 bg-white p-3.5 rounded-2xl border border-emerald-200 shadow-2xs">
                        <label class="block text-xs font-extrabold text-emerald-900">
                            Guru Tahfizh
                        </label>
                        <input type="text" name="teacher_tahfizh" value="{{ old('teacher_tahfizh', $setting->teacher_tahfizh ?? 'Ust. Ichsan') }}" 
                               placeholder="Nama Guru Tahfizh" 
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 font-bold">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                    ✓ Simpan Pengaturan Sekolah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
