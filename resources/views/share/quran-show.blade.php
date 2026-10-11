<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $surah->name_latin }} - Baca Al-Qur'an - {{ $student->name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col pb-16">

    <header class="bg-emerald-900 text-white border-b border-emerald-800 shadow-md">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-sm shadow flex-shrink-0">
                    {{ $surah->number }}
                </span>
                <div>
                    <span class="font-extrabold text-sm sm:text-base tracking-tight block">{{ $surah->name_latin }}</span>
                    <p class="text-[11px] text-emerald-200">{{ $surah->total_verses }} Ayat &middot; {{ $student->name }}</p>
                </div>
            </div>
            <a href="{{ route('share.quran-index', $student->share_token) }}" class="text-xs font-bold text-amber-300 hover:text-amber-200">
                ← Daftar Surah
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 pt-6 space-y-4 flex-1 w-full">
        @if ($unavailable)
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200/80 shadow-sm text-slate-400 text-sm">
                Konten Al-Qur'an sedang tidak tersedia, coba lagi nanti.
            </div>
        @else
            <x-quran.reader :surah="$surah" :verses="$verses" :memorized-verses="$memorizedVerses" />
        @endif
    </main>

    <footer class="text-center text-xs text-slate-400 mt-8">
        <p>{{ $setting->school_name }} • Sistem Pencatatan 3T App</p>
    </footer>

</body>
</html>
