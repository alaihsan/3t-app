@extends('layouts.app')

@section('title', 'Tambah Murid Baru - 3T App')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('students.index') }}" class="hover:text-emerald-700">Manajemen Murid</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Tambah Murid</span>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <h1 class="text-xl font-extrabold text-slate-900">Tambah Data Murid Baru</h1>

        <form action="{{ route('students.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- NIS & Nama -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        NIS (Nomor Induk) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nis" value="{{ old('nis') }}" required 
                           placeholder="Contoh: 202407001" 
                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Lengkap Murid <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="Nama lengkap murid" 
                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- Gender & Kelas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2 mt-1">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="gender" value="L" {{ old('gender', 'L') === 'L' ? 'checked' : '' }} class="text-emerald-600">
                            <span class="text-xs font-semibold text-slate-700">Laki-laki (L)</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="gender" value="P" {{ old('gender') === 'P' ? 'checked' : '' }} class="text-emerald-600">
                            <span class="text-xs font-semibold text-slate-700">Perempuan (P)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Kelas Terstandar (7A-9D) <span class="text-rose-500">*</span>
                    </label>
                    <select name="class_name" required class="w-full mt-1 px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 font-bold">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($classes as $cls)
                            <option value="{{ $cls }}" {{ old('class_name') === $cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Program 3T (Multi Category Checkboxes) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kategori / Program 3T <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 mt-1">
                    @foreach ($categories as $cat)
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-emerald-50/50">
                            <input type="checkbox" name="categories[]" value="{{ $cat }}"
                                   {{ is_array(old('categories')) && in_array($cat, old('categories')) ? 'checked' : (old('categories') === null && $cat === 'Tahfizh' ? 'checked' : '') }}
                                   class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-800">{{ $cat }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Catatan Guru -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Catatan Guru / Pembimbing (Opsional)
                </label>
                <input type="text" name="notes" value="{{ old('notes') }}"
                       placeholder="Catatan tambahan mengenai santri/murid..."
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- PIN Akses Share -->
            <x-collapsible-panel trigger="PIN Akses Share (Opsional)" :open="old('access_pin') ? true : false">
                <input type="text" name="access_pin" value="{{ old('access_pin') }}" maxlength="20"
                       placeholder="Kosongkan untuk digenerate otomatis"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 font-mono">
                <p class="text-[11px] text-slate-400 mt-1">PIN ini diminta saat orang tua membuka link share progres murid.</p>
            </x-collapsible-panel>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('students.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition-all">
                    ✓ Simpan Murid
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
