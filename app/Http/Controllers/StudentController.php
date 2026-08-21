<?php

namespace App\Http\Controllers;

use App\Models\MemorizationRecord;
use App\Models\Student;
use App\Models\Surah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_name')) {
            $query->where('class_name', $request->input('class_name'));
        }

        if ($request->filled('category')) {
            $query->whereJsonContains('categories', $request->input('category'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        $students = $query->withCount('memorizationRecords')
            ->orderBy('class_name')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $classes = Student::CLASSES;
        $categories = Student::CATEGORIES;

        return view('students.index', compact('students', 'classes', 'categories'));
    }

    public function create(): View
    {
        $classes = Student::CLASSES;
        $categories = Student::CATEGORIES;
        return view('students.create', compact('classes', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:students,nis'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'class_name' => ['required', 'in:' . implode(',', Student::CLASSES)],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['in:' . implode(',', Student::CATEGORIES)],
            'access_pin' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $student = Student::create([
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'class_name' => $validated['class_name'],
            'categories' => $validated['categories'],
            'share_token' => Str::random(32),
            'access_pin' => $validated['access_pin'] ?? '123456',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('students.index')->with('success', "Murid {$student->name} berhasil ditambahkan!");
    }

    public function show(Student $student): View
    {
        $student->load(['memorizationRecords.surah']);
        
        $records = $student->memorizationRecords()
            ->with('surah')
            ->orderBy('recorded_at', 'desc')
            ->orderBy('surah_id', 'desc')
            ->orderBy('verse_number', 'asc')
            ->get();

        // Group records by surah
        $surahGroups = $records->groupBy('surah_id');

        $surahs = Surah::orderBy('number')->get();

        return view('students.show', compact('student', 'records', 'surahGroups', 'surahs'));
    }

    public function edit(Student $student): View
    {
        $classes = Student::CLASSES;
        $categories = Student::CATEGORIES;
        return view('students.edit', compact('student', 'classes', 'categories'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:students,nis,' . $student->id],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'class_name' => ['required', 'in:' . implode(',', Student::CLASSES)],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['in:' . implode(',', Student::CATEGORIES)],
            'access_pin' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', "Data murid {$student->name} berhasil diperbarui!");
    }

    public function destroy(Student $student): RedirectResponse
    {
        $name = $student->name;
        $student->delete();

        return redirect()->route('students.index')->with('success', "Murid {$name} berhasil dihapus.");
    }

    /**
     * Import Excel Copy-Paste Form
     */
    public function importExcel(): View
    {
        $classes = Student::CLASSES;
        $categories = Student::CATEGORIES;
        return view('students.import-excel', compact('classes', 'categories'));
    }

    /**
     * Process Paste from Excel
     */
    public function processImportExcel(Request $request): RedirectResponse
    {
        $rawText = trim($request->input('paste_data', ''));
        if (empty($rawText)) {
            return back()->withErrors(['paste_data' => 'Data teks copas dari Excel tidak boleh kosong!']);
        }

        $lines = preg_split('/\r\n|\r|\n/', $rawText);
        $importedCount = 0;
        $updatedCount = 0;
        $errorRows = [];

        $validClasses = Student::CLASSES;
        $validCategories = ['Takhassus', 'Tahsin', 'Tahfizh'];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Detect delimiter (Tab or Comma or Semicolon)
            if (str_contains($line, "\t")) {
                $columns = explode("\t", $line);
            } elseif (str_contains($line, ";")) {
                $columns = explode(";", $line);
            } else {
                $columns = str_getcsv($line);
            }

            $columns = array_map('trim', $columns);

            // Skip header if detected
            if ($index === 0 && (stripos($columns[0] ?? '', 'nis') !== false || stripos($columns[1] ?? '', 'nama') !== false)) {
                continue;
            }

            if (count($columns) < 4) {
                $errorRows[] = "Baris " . ($index + 1) . ": Format kolom tidak lengkap (minimal: NIS, Nama, Gender, Kelas).";
                continue;
            }

            $nis = $columns[0];
            $name = $columns[1];
            $genderRaw = strtoupper($columns[2]);
            $gender = (str_starts_with($genderRaw, 'P') || str_starts_with($genderRaw, 'W')) ? 'P' : 'L';
            $className = strtoupper($columns[3]);
            
            // Format class if space exists e.g. "7 A" -> "7A"
            $className = str_replace(' ', '', $className);

            if (!in_array($className, $validClasses)) {
                $errorRows[] = "Baris " . ($index + 1) . " ({$name}): Kelas '{$className}' tidak valid (Gunakan 7A-9D).";
                continue;
            }

            // Categories (Column 5)
            $catRaw = $columns[4] ?? 'Tahfizh';
            $parsedCats = [];
            
            foreach ($validCategories as $validCat) {
                if (stripos($catRaw, $validCat) !== false) {
                    $parsedCats[] = $validCat;
                }
            }

            if (empty($parsedCats)) {
                $parsedCats = ['Tahfizh'];
            }

            $pin = $columns[5] ?? null;

            // Insert or update
            $existing = Student::where('nis', $nis)->first();
            if ($existing) {
                $existing->update([
                    'name' => $name,
                    'gender' => $gender,
                    'class_name' => $className,
                    'categories' => $parsedCats,
                    'access_pin' => $pin,
                ]);
                $updatedCount++;
            } else {
                Student::create([
                    'nis' => $nis,
                    'name' => $name,
                    'gender' => $gender,
                    'class_name' => $className,
                    'categories' => $parsedCats,
                    'share_token' => Str::random(32),
                    'access_pin' => $pin,
                ]);
                $importedCount++;
            }
        }

        $message = "Berhasil memproses data dari Excel: {$importedCount} murid baru ditambahkan, {$updatedCount} murid diperbarui.";
        
        if (!empty($errorRows)) {
            return redirect()->route('students.index')->with('warning', $message . " Ditemukan beberapa peringatan: " . implode(' | ', array_slice($errorRows, 0, 5)));
        }

        return redirect()->route('students.index')->with('success', $message);
    }
}
