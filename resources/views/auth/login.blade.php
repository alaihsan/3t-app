<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - 3T App (Takhassus, Tahsin, & Tahfizh)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Subtle Islamic Background Motif & Glows -->
    <div class="absolute inset-0 bg-islamic-pattern opacity-10 pointer-events-none"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md bg-white text-slate-800 rounded-3xl p-8 shadow-2xl border border-emerald-800/30 space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-emerald-950 flex items-center justify-center mx-auto shadow-lg shadow-amber-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">3T <span class="text-emerald-700">App</span></h1>
                <p class="text-xs text-slate-500 font-medium">Takhassus • Tahsin • Tahfizh</p>
                <p class="text-xs text-emerald-800 font-bold mt-1">{{ $setting->school_name }}</p>
            </div>
        </div>

        @if (session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email Pengguna
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', 'ichsan@smpi-alazhar22.sch.id') }}" required 
                       placeholder="nama@sekolah.sch.id" 
                       class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi
                </label>
                <input type="password" name="password" id="password" value="B1smillah" required 
                       placeholder="••••••••" 
                       class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-lg hover:shadow-emerald-900/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span>Masuk ke 3T App ➔</span>
            </button>
        </form>

        <!-- Sample Credential Card Helper -->
        <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-900 text-[11px] space-y-1">
            <div class="flex items-center justify-between">
                <span class="font-bold">Akun Guru Pembimbing (Tersedia):</span>
            </div>
            <p>Email: <code class="font-mono font-bold text-emerald-900">ichsan@smpi-alazhar22.sch.id</code></p>
            <p>Password: <code class="font-mono font-bold text-emerald-900">B1smillah</code></p>
        </div>

        <div class="text-center pt-2 text-[11px] text-slate-400">
            Sistem Pencatatan Hafalan Al-Qur'an Berbasis Web
        </div>

    </div>

</body>
</html>
