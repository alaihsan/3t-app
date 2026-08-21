<?php

namespace App\Http\Controllers;

use App\Models\MemorizationRecord;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Surah;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemorizationController extends Controller
{
    public function index(Request $request): View
    {
        $setting = SchoolSetting::getSettings();
        $allSurahs = Surah::orderBy('number')->get();

        // 1. Tingkat & Rombel (Kelas)
        $gradeLevel = $request->input('level', '7');
        if (!in_array($gradeLevel, ['7', '8', '9'])) {
            $gradeLevel = '7';
        }

        $allClasses = ['7A', '7B', '7C', '7D', '8A', '8B', '8C', '8D', '9A', '9B', '9C', '9D'];
        $classesInLevel = array_values(array_filter($allClasses, fn($c) => str_starts_with($c, $gradeLevel)));

        $selectedClass = $request->input('class_name', $classesInLevel[0] ?? '7A');
        if (!in_array($selectedClass, $classesInLevel)) {
            $selectedClass = $classesInLevel[0] ?? '7A';
        }

        // Semester
        $selectedSemester = $request->input('semester', $setting->semester ?? '1');

        // 2. Surah Selection (Searchable)
        $selectedSurahId = $request->input('surah_id');
        if ($selectedSurahId) {
            $selectedSurah = Surah::find($selectedSurahId);
        } else {
            // Default to Surah Al-Mursalat (77) or An-Naba (78) or first surah
            $selectedSurah = Surah::where('number', 77)->first() ?? $allSurahs->first();
        }

        // 3. Category (Tahfizh / Takhassus / Tahsin)
        $selectedCategory = $request->input('category', 'Tahfizh');

        // 4. Students in the selected class
        $students = Student::where('class_name', $selectedClass)
            ->orderBy('name')
            ->get();

        // Class student counts for pills
        $classCounts = Student::selectRaw('class_name, count(*) as total')
            ->groupBy('class_name')
            ->pluck('total', 'class_name')
            ->toArray();

        // 5. Load all memorization records for this class & surah
        $studentIds = $students->pluck('id')->toArray();
        $records = MemorizationRecord::whereIn('student_id', $studentIds)
            ->where('surah_id', $selectedSurah->id)
            ->where('category', $selectedCategory)
            ->get();

        // Matrix mapping: [student_id => [verse_number => record]]
        $matrix = [];
        $verseSummary = array_fill(1, $selectedSurah->total_verses, 0);

        foreach ($records as $rec) {
            $matrix[$rec->student_id][$rec->verse_number] = [
                'grade' => $rec->grade,
                'id' => $rec->id,
            ];
            if (isset($verseSummary[$rec->verse_number])) {
                $verseSummary[$rec->verse_number]++;
            }
        }

        // Student status calculation (Tuntas vs Proses)
        $totalVerses = $selectedSurah->total_verses;
        $studentStats = [];
        $tuntasCount = 0;
        $prosesCount = 0;

        foreach ($students as $st) {
            $memorizedCount = isset($matrix[$st->id]) ? count($matrix[$st->id]) : 0;
            $isTuntas = ($memorizedCount >= $totalVerses && $totalVerses > 0);
            if ($isTuntas) {
                $tuntasCount++;
            } else {
                $prosesCount++;
            }

            $studentStats[$st->id] = [
                'memorized_count' => $memorizedCount,
                'is_tuntas' => $isTuntas,
            ];
        }

        return view('memorization.index', compact(
            'setting',
            'allSurahs',
            'gradeLevel',
            'classesInLevel',
            'selectedClass',
            'selectedSemester',
            'selectedSurah',
            'selectedCategory',
            'students',
            'classCounts',
            'matrix',
            'verseSummary',
            'studentStats',
            'tuntasCount',
            'prosesCount'
        ));
    }

    /**
     * Instant Autosave Toggle per Verse (Direct DB Save)
     */
    public function toggleVerse(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'surah_id' => 'required|exists:surahs,id',
            'verse_number' => 'required|integer|min:1',
            'category' => 'required|in:Takhassus,Tahsin,Tahfizh',
            'grade' => 'nullable|in:A,B,C,D',
        ]);

        $studentId = $request->input('student_id');
        $surahId = $request->input('surah_id');
        $verseNumber = $request->input('verse_number');
        $category = $request->input('category');
        $grade = $request->input('grade', 'A');

        $surah = Surah::findOrFail($surahId);

        $existing = MemorizationRecord::where('student_id', $studentId)
            ->where('surah_id', $surahId)
            ->where('verse_number', $verseNumber)
            ->where('category', $category)
            ->first();

        if ($existing) {
            $existing->delete();
            $isMemorized = false;
            $savedGrade = null;
        } else {
            MemorizationRecord::create([
                'student_id' => $studentId,
                'surah_id' => $surahId,
                'verse_number' => $verseNumber,
                'category' => $category,
                'grade' => $grade,
                'recorded_at' => now(),
            ]);
            $isMemorized = true;
            $savedGrade = $grade;
        }

        // Count for this student in this surah
        $studentMemorizedCount = MemorizationRecord::where('student_id', $studentId)
            ->where('surah_id', $surahId)
            ->where('category', $category)
            ->count();

        // Count for this verse in the whole class
        $student = Student::find($studentId);
        $classStudentIds = Student::where('class_name', $student->class_name)->pluck('id');
        $verseClassCount = MemorizationRecord::whereIn('student_id', $classStudentIds)
            ->where('surah_id', $surahId)
            ->where('verse_number', $verseNumber)
            ->where('category', $category)
            ->count();

        $isTuntas = ($studentMemorizedCount >= $surah->total_verses);

        return response()->json([
            'status' => 'success',
            'is_memorized' => $isMemorized,
            'student_id' => $studentId,
            'verse_number' => $verseNumber,
            'grade' => $savedGrade,
            'student_memorized_count' => $studentMemorizedCount,
            'verse_class_count' => $verseClassCount,
            'is_tuntas' => $isTuntas,
            'total_verses' => $surah->total_verses,
            'message' => $isMemorized ? '✓ Tersimpan ke database' : '✓ Ayat dihapus',
        ]);
    }

    /**
     * Bulk Range Save per student (optional API helper)
     */
    public function saveRange(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'surah_id' => 'required|exists:surahs,id',
            'verse_from' => 'required|integer|min:1',
            'verse_to' => 'required|integer|min:1',
            'category' => 'required|in:Takhassus,Tahsin,Tahfizh',
            'grade' => 'required|in:A,B,C,D',
            'action' => 'required|in:add,remove',
        ]);

        $studentId = $request->input('student_id');
        $surahId = $request->input('surah_id');
        $from = min($request->input('verse_from'), $request->input('verse_to'));
        $to = max($request->input('verse_from'), $request->input('verse_to'));
        $category = $request->input('category');
        $grade = $request->input('grade');
        $action = $request->input('action');

        $surah = Surah::findOrFail($surahId);
        $to = min($to, $surah->total_verses);

        if ($action === 'add') {
            for ($v = $from; $v <= $to; $v++) {
                MemorizationRecord::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'surah_id' => $surahId,
                        'verse_number' => $v,
                        'category' => $category,
                    ],
                    [
                        'grade' => $grade,
                        'recorded_at' => now(),
                    ]
                );
            }
        } else {
            MemorizationRecord::where('student_id', $studentId)
                ->where('surah_id', $surahId)
                ->where('category', $category)
                ->whereBetween('verse_number', [$from, $to])
                ->delete();
        }

        $totalMemorized = MemorizationRecord::where('student_id', $studentId)
            ->where('surah_id', $surahId)
            ->where('category', $category)
            ->count();

        return response()->json([
            'status' => 'success',
            'action' => $action,
            'grade' => $grade,
            'total_memorized' => $totalMemorized,
            'percentage' => round(($totalMemorized / $surah->total_verses) * 100),
        ]);
    }
}
