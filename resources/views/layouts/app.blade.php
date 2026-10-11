<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '3T App') - Takhassus, Tahsin, & Tahfizh</title>

    <!-- Google Fonts: Plus Jakarta Sans & Amiri Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false, bigSearchOpen: false }">

    <div class="min-h-full flex">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden no-print"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Navigation (Statis & Selalu Terlihat di Layar) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-64 h-screen bg-emerald-950 text-white flex flex-col justify-between transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen shadow-2xl border-r border-emerald-850/80 no-print flex-shrink-0">
            
            <!-- Top Branding & Navigation (Scrollable Inner) -->
            <div class="flex flex-col flex-1 min-h-0 overflow-y-auto">
                <!-- App Header in Sidebar -->
                <div class="p-5 border-b border-emerald-850/80 flex items-center justify-between flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-md shadow-amber-900/40 group-hover:scale-105 transition-transform flex-shrink-0">
                            <svg class="w-6 h-6 text-emerald-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-white">3T <span class="text-amber-400">App</span></span>
                    </a>

                    <!-- Close Button (Mobile only) -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-emerald-400 hover:text-white p-1">
                        ✕
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-3 space-y-1 text-xs sm:text-sm font-medium">
                    <div class="px-3 pt-3 pb-1.5 text-[10px] font-bold text-emerald-400/60 uppercase tracking-widest">
                        Menu Utama
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-850 text-amber-300 font-bold shadow-inner border-l-4 border-amber-400' : 'text-emerald-100/90 hover:bg-emerald-900 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-amber-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Catat Hafalan -->
                    <a href="{{ route('memorization.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('memorization.*') ? 'bg-emerald-850 text-amber-300 font-bold shadow-inner border-l-4 border-amber-400' : 'text-emerald-100/90 hover:bg-emerald-900 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('memorization.*') ? 'text-amber-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Catat Hafalan (3T)</span>
                    </a>

                    <!-- Manajemen Murid with Sub-Menu -->
                    <div x-data="{ open: {{ request()->routeIs('students.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button type="button" 
                                @click="open = !open" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('students.*') ? 'bg-emerald-850 text-amber-300 font-bold shadow-inner border-l-4 border-amber-400' : 'text-emerald-100/90 hover:bg-emerald-900 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ request()->routeIs('students.*') ? 'text-amber-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Manajemen Murid</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180 text-amber-400' : 'text-emerald-400/60'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Sub-Menu Items -->
                        <div x-show="open" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                            <a href="{{ route('students.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('students.index') || request()->routeIs('students.show') || request()->routeIs('students.edit') ? 'bg-emerald-900/90 text-amber-300 font-bold' : 'text-emerald-200/80 hover:bg-emerald-900/50 hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('students.index') ? 'bg-amber-400' : 'bg-emerald-500' }}"></span>
                                <span>Daftar Murid</span>
                            </a>
                            <a href="{{ route('students.import-excel') }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('students.import-excel') ? 'bg-emerald-900/90 text-amber-300 font-bold' : 'text-emerald-200/80 hover:bg-emerald-900/50 hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('students.import-excel') ? 'bg-amber-400' : 'bg-emerald-500' }}"></span>
                                <span>Import Copas Excel</span>
                            </a>
                            <a href="{{ route('students.create') }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('students.create') ? 'bg-emerald-900/90 text-amber-300 font-bold' : 'text-emerald-200/80 hover:bg-emerald-900/50 hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('students.create') ? 'bg-amber-400' : 'bg-emerald-500' }}"></span>
                                <span>Tambah Murid</span>
                            </a>
                        </div>
                    </div>

                    <!-- Laporan Rekap 3T -->
                    <a href="{{ route('reports.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('reports.*') ? 'bg-emerald-850 text-amber-300 font-bold shadow-inner border-l-4 border-amber-400' : 'text-emerald-100/90 hover:bg-emerald-900 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-amber-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Laporan 3T (A4)</span>
                    </a>

                    <!-- Baca Al-Qur'an -->
                    <a href="{{ route('quran.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('quran.*') ? 'bg-emerald-850 text-amber-300 font-bold shadow-inner border-l-4 border-amber-400' : 'text-emerald-100/90 hover:bg-emerald-900 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('quran.*') ? 'text-amber-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                        </svg>
                        <span>Al-Qur'an</span>
                    </a>

                    <div class="px-3 pt-4 pb-1.5 text-[10px] font-bold text-emerald-400/60 uppercase tracking-widest">
                        Konfigurasi
                    </div>

                    <!-- Pengaturan Sekolah -->
                    <a href="{{ route('settings.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('settings.*') ? 'bg-emerald-850 text-amber-300 font-bold shadow-inner border-l-4 border-amber-400' : 'text-emerald-100/90 hover:bg-emerald-900 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('settings.*') ? 'text-amber-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Pengaturan Kop & Guru</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom User Profile & Logout in Sidebar (Statis & Pinned di Bawah) -->
            <div class="p-4 border-t border-emerald-850/80 bg-emerald-950/95 flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Ust. Ichsan' }}</p>
                            <p class="text-[10px] text-emerald-300/80 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg bg-emerald-900 hover:bg-rose-900 text-emerald-300 hover:text-rose-200 transition-colors cursor-pointer" title="Keluar / Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper & Header -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Navbar Bar -->
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200/80 shadow-xs backdrop-blur-md bg-opacity-95 no-print">
                <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                    
                    <!-- Left: Mobile Menu Button & App Title -->
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <span class="text-sm font-extrabold text-emerald-950 tracking-tight lg:hidden">3T App</span>
                    </div>

                    <!-- Right Info Header: School Name Only -->
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline-flex max-w-xs truncate px-3 py-1 rounded-full bg-emerald-50 text-emerald-900 text-[11px] font-bold border border-emerald-200/80">
                            {{ $globalSchoolSetting->school_name ?? 'SMP ISLAM AL AZHAR 22 SENTRA PRIMER' }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto pb-16">
                <x-flash-message type="success" :message="session('success')" />
                <x-flash-message type="warning" :message="session('warning')" />
                @if ($errors->any())
                    <x-flash-message type="error" :message="$errors->all()" />
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <!-- Google-Style Big Search Modal Component -->
    <div x-show="bigSearchOpen" 
         x-cloak
         @keydown.window.prevent.cmd.k="bigSearchOpen = true; $nextTick(() => $refs.bigSearchInput.focus())"
         @keydown.window.prevent.ctrl.k="bigSearchOpen = true; $nextTick(() => $refs.bigSearchInput.focus())"
         @keydown.escape.window="bigSearchOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20"
         role="dialog" aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="bigSearchOpen" 
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
             @click="bigSearchOpen = false"></div>

        <!-- Search Box Container -->
        <div x-show="bigSearchOpen" 
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative mx-auto max-w-3xl transform overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 transition-all"
             x-data="studentSearch({ autoSearchOnInit: false })"
             x-init="$watch('bigSearchOpen', (open) => { if (open) performSearch(); })">

            <!-- Input Bar -->
            <div class="relative flex items-center px-4 py-3.5 border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 via-white to-amber-50/30">
                <svg class="w-6 h-6 text-emerald-700 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input x-ref="bigSearchInput"
                       type="text" 
                       x-model="searchQuery"
                       @input.debounce.250ms="performSearch()"
                       class="w-full bg-transparent border-0 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-0 text-sm sm:text-base font-medium"
                       placeholder="Cari nama murid, NIS, kelas (7A-9D), atau program...">
                
                <button @click="bigSearchOpen = false" class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 font-semibold cursor-pointer">
                    ESC
                </button>
            </div>

            <!-- Filter Pills -->
            <div class="flex items-center gap-1.5 px-4 py-2 bg-slate-50 border-b border-slate-100 overflow-x-auto text-xs">
                <span class="text-slate-400 font-medium mr-1 flex-shrink-0">Filter Cepat:</span>
                <button @click="setClassFilter('all')" :class="activeClass === 'all' ? 'bg-emerald-700 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Semua</button>
                <button @click="setClassFilter('7')" :class="activeClass === '7' ? 'bg-emerald-700 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Kelas 7</button>
                <button @click="setClassFilter('8')" :class="activeClass === '8' ? 'bg-emerald-700 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Kelas 8</button>
                <button @click="setClassFilter('9')" :class="activeClass === '9' ? 'bg-emerald-700 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Kelas 9</button>
                <span class="text-slate-300 mx-1">|</span>
                <button @click="setCategoryFilter('Tahfizh')" :class="activeCategory === 'Tahfizh' ? 'bg-amber-600 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Tahfizh</button>
                <button @click="setCategoryFilter('Takhassus')" :class="activeCategory === 'Takhassus' ? 'bg-emerald-600 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Takhassus</button>
                <button @click="setCategoryFilter('Tahsin')" :class="activeCategory === 'Tahsin' ? 'bg-teal-600 text-white font-semibold' : 'bg-white text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-full border border-slate-200 transition-all cursor-pointer">Tahsin</button>
            </div>

            <!-- Search Results Content -->
            <div class="max-h-[60vh] overflow-y-auto p-4 divide-y divide-slate-100">
                <!-- Loading State -->
                <div x-show="loading" class="py-12 text-center text-slate-400">
                    <svg class="animate-spin w-8 h-8 mx-auto text-emerald-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-xs font-medium">Mencari murid...</p>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && results.length === 0" class="py-10 text-center text-slate-400">
                    <p class="text-xs font-medium text-slate-600">Tidak ada data murid yang sesuai</p>
                </div>

                <!-- Result Cards -->
                <template x-for="student in results" :key="student.id">
                    <div class="py-3 px-3 hover:bg-emerald-50/50 rounded-2xl transition-all flex items-center justify-between gap-4 group">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-xs flex-shrink-0"
                                 :class="student.gender === 'L' ? 'bg-gradient-to-br from-emerald-600 to-teal-700' : 'bg-gradient-to-br from-amber-500 to-rose-600'">
                                <span x-text="student.name.charAt(0)"></span>
                            </div>
                            <div class="truncate">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm group-hover:text-emerald-800 truncate" x-text="student.name"></h4>
                                    <span class="px-2 py-0.2 rounded text-[10px] font-bold bg-slate-200 text-slate-700 flex-shrink-0" x-text="student.class_name"></span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    <span class="text-emerald-700 font-semibold" x-text="'Setoran Terakhir: ' + student.last_surah"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex items-center flex-shrink-0">
                            <a :href="student.urls.record" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-extrabold shadow-sm transition-all">
                                <span>🟢 Catat Hafalan</span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Quick Toast Alert -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 space-y-2 pointer-events-none"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `px-4 py-3 rounded-2xl shadow-lg text-xs font-semibold text-white pointer-events-auto transform transition-all duration-300 flex items-center gap-2 ${type === 'success' ? 'bg-emerald-900 border border-emerald-700' : 'bg-rose-800'}`;
            toast.innerHTML = `
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>
</html>
