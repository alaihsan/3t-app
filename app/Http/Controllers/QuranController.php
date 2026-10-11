<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Surah;
use App\Services\QuranProgressService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranController extends Controller
{
    /**
     * Daftar 114 surah (data lokal, tidak perlu panggil API eksternal).
     */
    public function index(): View
    {
        $surahs = Surah::orderBy('number')->get();

        return view('quran.index', compact('surahs'));
    }

    /**
     * Baca satu surah, dengan opsi highlight progres hafalan seorang murid.
     */
    public function show(int $number, Request $request, QuranProgressService $quranProgress): View
    {
        $surah = Surah::where('number', $number)->firstOrFail();

        $studentId = $request->integer('student_id') ?: null;
        $student = $studentId ? Student::find($studentId) : null;

        $students = Student::orderBy('name')->get();

        try {
            $data = $quranProgress->buildReadingData($surah, $student);
        } catch (RequestException|ConnectionException $e) {
            return view('quran.show', [
                'surah' => $surah,
                'verses' => [],
                'memorizedVerses' => [],
                'student' => $student,
                'students' => $students,
                'unavailable' => true,
            ]);
        }

        return view('quran.show', [...$data, 'student' => $student, 'students' => $students, 'unavailable' => false]);
    }
}
