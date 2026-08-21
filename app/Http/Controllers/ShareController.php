<?php

namespace App\Http\Controllers;

use App\Models\MemorizationRecord;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Surah;
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
        ));
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
        $totalQuranVerses = 6236;
        $totalPercentage = round(($totalVersesMemorized / $totalQuranVerses) * 100, 2);

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
}
