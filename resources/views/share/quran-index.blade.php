<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Al-Qur'an - {{ $student->name }} (Kelas {{ $student->class_name }})</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col pb-16">

    <header class="bg-emerald-900 text-white border-b border-emerald-800 shadow-md">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-sm shadow">
                    3T
                </div>
                <div>
                    <span class="font-extrabold text-sm sm:text-base tracking-tight block">{{ $setting->school_name }}</span>
                    <p class="text-[11px] text-emerald-200">Baca Al-Qur'an &middot; {{ $student->name }}</p>
                </div>
            </div>
            <a href="{{ route('share.view', $student->share_token) }}" class="text-xs font-bold text-amber-300 hover:text-amber-200">
                ← Kembali ke Progres
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 pt-6 space-y-4 flex-1 w-full">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <h1 class="font-extrabold text-slate-900 text-lg">Daftar Surah</h1>
            <p class="text-xs text-slate-500">Pilih surah untuk membaca teks Arab dan terjemahan. Ayat yang sudah dihafal {{ $student->name }} akan ditandai.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm divide-y divide-slate-100">
            @foreach ($surahs as $surah)
                <a href="{{ route('share.quran-show', ['share_token' => $student->share_token, 'number' => $surah->number]) }}"
                   class="flex items-center justify-between gap-3 p-4 hover:bg-emerald-50/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-bold text-xs flex items-center justify-center shadow-sm flex-shrink-0">
                            {{ $surah->number }}
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $surah->name_latin }}</h4>
                                <span class="font-arabic text-emerald-800 text-sm">{{ $surah->name_arabic }}</span>
                            </div>
                            <p class="text-[11px] text-slate-400">{{ $surah->total_verses }} Ayat</p>
                        </div>
                    </div>
                    <span class="text-emerald-600 text-xs">→</span>
                </a>
            @endforeach
        </div>
    </main>

    <footer class="text-center text-xs text-slate-400 mt-8">
        <p>{{ $setting->school_name }} • Sistem Pencatatan 3T App</p>
    </footer>

</body>
</html>
