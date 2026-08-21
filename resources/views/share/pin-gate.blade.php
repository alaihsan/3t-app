<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Terproteksi PIN - 3T App</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-2xl border border-slate-200 text-center space-y-6">
        
        <!-- Emblem Header -->
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-700 to-teal-900 text-amber-300 flex items-center justify-center mx-auto shadow-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <div>
            <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-emerald-100 text-emerald-800">
                PROGRES HAFALAN 3T
            </span>
            <h1 class="text-xl font-extrabold text-slate-900 mt-2">Halaman Terproteksi PIN</h1>
            <p class="text-xs text-slate-500 mt-1">
                Laporan perkembangan hafalan murid <strong class="text-slate-800">{{ $student->name }}</strong> (Kelas {{ $student->class_name }})
            </p>
        </div>

        @if ($errors->has('pin'))
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first('pin') }}
            </div>
        @endif

        <form action="{{ route('share.verify-pin', $student->share_token) }}" method="POST" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 text-center">
                    Masukkan PIN Akses
                </label>
                <input type="password" name="pin" required autofocus 
                       placeholder="••••••" 
                       class="w-full text-center tracking-widest text-lg font-mono py-3 px-4 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <p class="text-[11px] text-slate-400 text-center mt-1">Gunakan PIN yang diberikan oleh pihak sekolah / wali kelas.</p>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all">
                Buka Laporan Progres ➔
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-400">
            {{ $setting->school_name }} • 3T App
        </div>

    </div>

</body>
</html>
