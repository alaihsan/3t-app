<?php

namespace App\Http\Controllers;

use App\Models\MemorizationRecord;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Surah;
use App\Services\QuranProgressService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShareController extends Controller
{
    /**
     * Web Read-Only Progress View for Parents / Public
     */
    public function view(string $share_token, Request $request): View
    {
        $student = Student::where('share_token', $share_token)->firstOrFail();
        $setting = SchoolSetting::getSettings();

        if ($this->pinRequired($student) && !$this->isUnlocked($request, $student)) {
            return view('share.pin-gate', compact('student', 'setting'));
        }

        $records = MemorizationRecord::where('student_id', $student->id)
            ->with('surah')
            ->orderBy('recorded_at', 'desc')
            ->orderBy('surah_id', 'desc')
            ->get();

        // Group records by Surah
        $surahGroups = $records->groupBy('surah_id');

        // Total memorized verses
        $totalVersesMemorized = $records->count();
        $totalSurahsTouched = $surahGroups->count();

        // Grade stats
        $gradeCounts = $records->pluck('grade')->countBy()->toArray();

        // Juz breakdown (Juz 1 to 30)
        $allSurahs = Surah::all()->keyBy('id');
        $juzStats = [];
        for ($j = 1; $j <= 30; $j++) {
            $surahsInJuz = $allSurahs->filter(function ($s) use ($j) {
                return $s->juz_start <= $j && $s->juz_end >= $j;
            });

            $juzVersesMemorized = $records->filter(function ($rec) use ($surahsInJuz) {
                return $surahsInJuz->has($rec->surah_id);
            })->count();

            $juzStats[$j] = [
                'juz' => $j,
                'memorized_count' => $juzVersesMemorized,
                'has_progress' => $juzVersesMemorized > 0,
            ];
        }

        return view('share.view', compact(
            'student',
            'setting',
            'records',
            'surahGroups',
            'totalVersesMemorized',
            'totalSurahsTouched',
            'gradeCounts',
            'juzStats'
        ) + ['totalQuranVerses' => Student::TOTAL_QURAN_VERSES]);
    }

    /**
     * Verify PIN submitted from the pin-gate form and unlock the share view for this session.
     */
    public function verifyPin(string $share_token, Request $request): RedirectResponse
    {
        $student = Student::where('share_token', $share_token)->firstOrFail();

        $request->validate([
            'pin' => ['required', 'string'],
        ]);

        if (!$this->pinRequired($student) || $request->input('pin') !== $student->access_pin) {
            return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah.']);
        }

        $request->session()->put("share_unlocked_{$student->id}", true);

        return redirect()->route('share.view', $student->share_token);
    }

    /**
     * Share API URL Endpoint (JSON Response)
     */
    public function apiProgress(string $share_token, Request $request): JsonResponse
    {
        $student = Student::where('share_token', $share_token)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Murid tidak ditemukan atau token tidak valid.',
            ], 404);
        }

        if ($this->pinRequired($student) && $request->query('pin') !== $student->access_pin) {
            return response()->json([
                'status' => 'error',
                'message' => 'PIN akses diperlukan atau tidak valid.',
            ], 401);
        }

        $setting = SchoolSetting::getSettings();

        $records = MemorizationRecord::where('student_id', $student->id)
            ->with('surah')
            ->orderBy('surah_id')
            ->orderBy('verse_number')
            ->get();

        $groupedBySurah = $records->groupBy('surah_id')->map(function ($items, $surahId) {
            $surah = $items->first()->surah;
            return [
                'surah_nomor' => $surah->number,
                'surah_nama_latin' => $surah->name_latin,
                'surah_nama_arab' => $surah->name_arabic,
                'total_ayat_surah' => $surah->total_verses,
                'ayat_dihafal_count' => $items->count(),
                'persentase_surah' => round(($items->count() / $surah->total_verses) * 100, 1),
                'ayat_list' => $items->map(function ($item) {
                    return [
                        'ayat' => $item->verse_number,
                        'grade' => $item->grade,
                        'program' => $item->category,
                        'tanggal' => $item->recorded_at ? $item->recorded_at->format('Y-m-d') : null,
                    ];
                })->values(),
            ];
        })->values();

        $totalVersesMemorized = $records->count();
        $totalPercentage = round(($totalVersesMemorized / Student::TOTAL_QURAN_VERSES) * 100, 2);

        return response()->json([
            'status' => 'success',
            'sekolah' => [
                'nama' => $setting->school_name,
                'tahun_ajaran' => $setting->academic_year,
                'semester' => $setting->semester,
                'guru_pembimbing' => $setting->teacher_name,
            ],
            'murid' => [
                'nis' => $student->nis,
                'nama' => $student->name,
                'jenis_kelamin' => $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'kelas' => $student->class_name,
                'program_3t' => $student->categories,
            ],
            'ringkasan_progres' => [
                'total_ayat_dihafal' => $totalVersesMemorized,
                'total_surah_disetor' => $groupedBySurah->count(),
                'persentase_total_quran' => $totalPercentage . '%',
                'sebaran_grade' => $records->pluck('grade')->countBy()->toArray(),
            ],
            'detail_hafalan_per_surah' => $groupedBySurah,
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Daftar surah untuk dibaca dari sisi orang tua (gated by PIN seperti view()).
     */
    public function quranIndex(string $share_token, Request $request): View
    {
        $student = Student::where('share_token', $share_token)->firstOrFail();
        $setting = SchoolSetting::getSettings();

        if ($this->pinRequired($student) && !$this->isUnlocked($request, $student)) {
            return view('share.pin-gate', compact('student', 'setting'));
        }

        $surahs = Surah::orderBy('number')->get();

        return view('share.quran-index', compact('student', 'setting', 'surahs'));
    }

    /**
     * Baca satu surah untuk murid di balik token share, dengan progres hafalan
     * murid itu otomatis ter-highlight. $student SELALU diturunkan dari
     * share_token, TIDAK PERNAH dari parameter request lain - jangan ubah ini,
     * supaya pengunjung tidak bisa melihat hafalan murid lain lewat URL.
     */
    public function quranShow(string $share_token, int $number, Request $request, QuranProgressService $quranProgress): View
    {
        $student = Student::where('share_token', $share_token)->firstOrFail();
        $setting = SchoolSetting::getSettings();

        if ($this->pinRequired($student) && !$this->isUnlocked($request, $student)) {
            return view('share.pin-gate', compact('student', 'setting'));
        }

        $surah = Surah::where('number', $number)->firstOrFail();

        try {
            $data = $quranProgress->buildReadingData($surah, $student);
        } catch (RequestException|ConnectionException $e) {
            return view('share.quran-show', [
                'surah' => $surah,
                'verses' => [],
                'memorizedVerses' => [],
                'student' => $student,
                'setting' => $setting,
                'unavailable' => true,
            ]);
        }

        return view('share.quran-show', [...$data, 'student' => $student, 'setting' => $setting, 'unavailable' => false]);
    }

    private function pinRequired(Student $student): bool
    {
        return filled($student->access_pin);
    }

    private function isUnlocked(Request $request, Student $student): bool
    {
        return $request->session()->get("share_unlocked_{$student->id}") === true;
    }
}
