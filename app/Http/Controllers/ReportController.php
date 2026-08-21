<?php

namespace App\Http\Controllers;

use App\Models\MemorizationRecord;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Surah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Preview Laporan Monitoring Hafalan Al-Qur'an
     */
    public function index(Request $request): View
    {
        $setting = SchoolSetting::getSettings();
        $classes = Student::CLASSES;
        $allSurahs = Surah::orderBy('number')->get();

        $selectedClass = $request->input('class_name', '7A');
        if (!in_array($selectedClass, $classes)) {
            $selectedClass = '7A';
        }

        $selectedSurahId = $request->input('surah_id');
        if ($selectedSurahId) {
            $selectedSurah = Surah::find($selectedSurahId);
        } else {
            // Default to Surah Al-Mursalat (77) or first surah
            $selectedSurah = Surah::where('number', 77)->first() ?? $allSurahs->first();
        }

        $selectedSemester = $request->input('semester', $setting->semester ?? '1');

        // Signature Configuration (Kanan Saja)
        $signTitle = $request->input('sign_title', 'Guru Tahfizh');
        $signName = $request->input('sign_name', $setting->teacher_tahfizh ?? 'Ust. Ichsan');

        // All students in the class sorted alphabetically by name
        $students = Student::where('class_name', $selectedClass)
            ->orderBy('name')
            ->get();

        // Load memorization records for this class and surah
        $studentIds = $students->pluck('id')->toArray();
        $records = MemorizationRecord::whereIn('student_id', $studentIds)
            ->where('surah_id', $selectedSurah->id)
            ->get();

        // Matrix mapping: [student_id => [verse_number => true]]
        $matrix = [];
        foreach ($records as $rec) {
            $matrix[$rec->student_id][$rec->verse_number] = true;
        }

        // Student stats (% percentage completed)
        $totalVerses = $selectedSurah->total_verses;
        $studentsData = $students->map(function ($student, $index) use ($matrix, $totalVerses) {
            $memorizedCount = isset($matrix[$student->id]) ? count($matrix[$student->id]) : 0;
            $percentage = $totalVerses > 0 ? round(($memorizedCount / $totalVerses) * 100) : 0;

            return [
                'no' => $index + 1,
                'student' => $student,
                'memorized_verses' => $matrix[$student->id] ?? [],
                'memorized_count' => $memorizedCount,
                'percentage' => $percentage,
                'is_tuntas' => $memorizedCount >= $totalVerses,
            ];
        });

        return view('reports.index', compact(
            'setting',
            'classes',
            'allSurahs',
            'selectedClass',
            'selectedSurah',
            'selectedSemester',
            'students',
            'studentsData',
            'signTitle',
            'signName'
        ));
    }

    /**
     * Print View A4 Landscape (Exact Match to User Reference)
     */
    public function printA4Landscape(Request $request): View
    {
        $setting = SchoolSetting::getSettings();
        $classes = Student::CLASSES;
        $allSurahs = Surah::orderBy('number')->get();

        $selectedClass = $request->input('class_name', '7A');
        if (!in_array($selectedClass, $classes)) {
            $selectedClass = '7A';
        }

        $selectedSurahId = $request->input('surah_id');
        if ($selectedSurahId) {
            $selectedSurah = Surah::find($selectedSurahId);
        } else {
            $selectedSurah = Surah::where('number', 77)->first() ?? $allSurahs->first();
        }

        $selectedSemester = $request->input('semester', $setting->semester ?? '1');

        // Signature Configuration (Kanan Saja)
        $signTitle = $request->input('sign_title', 'Guru Tahfizh');
        $signName = $request->input('sign_name', $setting->teacher_tahfizh ?? 'Ust. Ichsan');

        // All students in this class sorted alphabetically by name
        $students = Student::where('class_name', $selectedClass)
            ->orderBy('name')
            ->get();

        // Load all records in this surah
        $studentIds = $students->pluck('id')->toArray();
        $records = MemorizationRecord::whereIn('student_id', $studentIds)
            ->where('surah_id', $selectedSurah->id)
            ->get();

        // Matrix mapping
        $matrix = [];
        foreach ($records as $rec) {
            $matrix[$rec->student_id][$rec->verse_number] = true;
        }

        $totalVerses = $selectedSurah->total_verses;
        $studentsData = $students->map(function ($student, $index) use ($matrix, $totalVerses) {
            $memorizedCount = isset($matrix[$student->id]) ? count($matrix[$student->id]) : 0;
            $percentage = $totalVerses > 0 ? round(($memorizedCount / $totalVerses) * 100) : 0;

            return [
                'no' => $index + 1,
                'student' => $student,
                'memorized_verses' => $matrix[$student->id] ?? [],
                'memorized_count' => $memorizedCount,
                'percentage' => $percentage,
                'is_tuntas' => $memorizedCount >= $totalVerses,
            ];
        });

        return view('reports.print-a4-landscape', compact(
            'setting',
            'selectedClass',
            'selectedSurah',
            'selectedSemester',
            'students',
            'studentsData',
            'signTitle',
            'signName'
        ));
    }
}
