@extends('layouts.app')

@section('title', 'Manajemen Murid - 3T App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Murid 3T</h1>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('students.import-excel') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-xs sm:text-sm font-semibold shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span>Import Copas Excel</span>
            </a>
            <a href="{{ route('students.create') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs sm:text-sm font-bold shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Murid</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Box -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
        <x-collapsible-panel trigger="Filter &amp; Pencarian" :open="request()->anyFilled(['search', 'class_name', 'category', 'gender'])">
        <form action="{{ route('students.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Search Query -->
            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Pencarian</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari Nama atau NIS..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Filter Class -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kelas (7A-9D)</label>
                <select name="class_name" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $cls)
                        <option value="{{ $cls }}" {{ request('class_name') === $cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter 3T Category -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Program 3T</label>
                <select name="category" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Program</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Gender & Submit -->
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Gender</label>
                    <select name="gender" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua</option>
                        <option value="L" {{ request('gender') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ request('gender') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold shadow transition-all">
                    Filter
                </button>
            </div>
        </form>
        </x-collapsible-panel>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if ($students->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <p class="text-sm font-medium">Tidak ada data murid ditemukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <th class="py-3 px-4">Murid</th>
                            <th class="py-3 px-3">NIS</th>
                            <th class="py-3 px-3">Gender</th>
                            <th class="py-3 px-3">Kelas</th>
                            <th class="py-3 px-3">Program 3T</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($students as $student)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm flex-shrink-0 {{ $student->gender === 'L' ? 'bg-emerald-700' : 'bg-rose-500' }}">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('students.show', $student->id) }}" class="hover:text-emerald-700 transition-colors">
                                                {{ $student->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-slate-600 font-medium">
                                    {{ $student->nis }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $student->gender === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 font-extrabold text-[11px] border border-slate-200">
                                        {{ $student->class_name }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($student->categories ?? [] as $cat)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold
                                                {{ $cat === 'Tahfizh' ? 'bg-amber-100 text-amber-800 border border-amber-300' : '' }}
                                                {{ $cat === 'Takhassus' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : '' }}
                                                {{ $cat === 'Tahsin' ? 'bg-teal-100 text-teal-800 border border-teal-300' : '' }}">
                                                {{ $cat }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('memorization.index', ['student_id' => $student->id]) }}"
                                           class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[11px] shadow-sm transition-all">
                                            Catat
                                        </a>
                                        <a href="{{ route('students.show', $student->id) }}"
                                           class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors"
                                           title="Detail Rapor">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}"
                                           class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-800 transition-colors"
                                           title="Edit Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data murid {{ $student->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 transition-colors" title="Hapus Murid">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
