<?php

namespace App\Http\Controllers;

use App\Models\MemorizationRecord;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Surah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $setting = SchoolSetting::getSettings();
        
        $totalStudents = Student::count();
        $totalVersesMemorized = MemorizationRecord::count();
        
        // Count per 3T Category
        $takhassusCount = Student::whereJsonContains('categories', 'Takhassus')->count();
        $tahfizhCount = Student::whereJsonContains('categories', 'Tahfizh')->count();
        $tahsinCount = Student::whereJsonContains('categories', 'Tahsin')->count();

        // Grade distributions
        $gradeDistribution = MemorizationRecord::selectRaw('grade, count(*) as count')
            ->groupBy('grade')
            ->pluck('count', 'grade')
            ->toArray();

        // Recent memorization activities
        $recentActivities = MemorizationRecord::with(['student', 'surah'])
            ->latest('recorded_at')
            ->latest('id')
            ->take(8)
            ->get();

        // Class breakdown
        $classes = Student::CLASSES;
        $classCounts = [];
        foreach ($classes as $cls) {
            $classCounts[$cls] = Student::where('class_name', $cls)->count();
        }

        return view('dashboard', compact(
            'setting',
            'totalStudents',
            'totalVersesMemorized',
            'takhassusCount',
            'tahfizhCount',
            'tahsinCount',
            'gradeDistribution',
            'recentActivities',
            'classCounts',
            'classes'
        ));
    }

    /**
     * Google-Style Big Search API
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));
        $classFilter = $request->input('class');
        $categoryFilter = $request->input('category');
        $genderFilter = $request->input('gender');

        $studentsQuery = Student::query();

        if (!empty($query)) {
            $studentsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('nis', 'like', "%{$query}%")
                  ->orWhere('class_name', 'like', "%{$query}%");
            });
        }

        if (!empty($classFilter) && $classFilter !== 'all') {
            if (in_array($classFilter, ['7', '8', '9'])) {
                $studentsQuery->where('class_name', 'like', "{$classFilter}%");
            } else {
                $studentsQuery->where('class_name', $classFilter);
            }
        }

        if (!empty($categoryFilter) && $categoryFilter !== 'all') {
            $studentsQuery->whereJsonContains('categories', $categoryFilter);
        }

        if (!empty($genderFilter) && $genderFilter !== 'all') {
            $studentsQuery->where('gender', $genderFilter);
        }

        $results = $studentsQuery->withCount('memorizationRecords')
            ->take(20)
            ->get()
            ->map(function ($student) {
                $totalVerses = $student->memorization_records_count;
                $percentage = min(100, round(($totalVerses / 6236) * 100, 1));
                
                // Last memorized surah
                $lastRecord = MemorizationRecord::where('student_id', $student->id)
                    ->with('surah')
                    ->latest('recorded_at')
                    ->latest('id')
                    ->first();

                return [
                    'id' => $student->id,
                    'nis' => $student->nis,
                    'name' => $student->name,
                    'gender' => $student->gender,
                    'class_name' => $student->class_name,
                    'categories' => $student->categories ?? [],
                    'share_token' => $student->share_token,
                    'total_verses' => $totalVerses,
                    'percentage' => $percentage,
                    'last_surah' => $lastRecord ? ($lastRecord->surah->name_latin . ' (Ayat ' . $lastRecord->verse_number . ')') : 'Belum ada setoran',
                    'last_grade' => $lastRecord ? $lastRecord->grade : '-',
                    'urls' => [
                        'record' => route('memorization.index', ['student_id' => $student->id]),
                        'detail' => route('students.show', $student->id),
                        'share_web' => route('share.view', $student->share_token),
                        'share_api' => route('share.api', $student->share_token),
                    ]
                ];
            });

        return response()->json([
            'status' => 'success',
            'count' => $results->count(),
            'data' => $results
        ]);
    }
}
