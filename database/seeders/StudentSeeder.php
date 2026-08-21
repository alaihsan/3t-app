<?php

namespace Database\Seeders;

use App\Models\MemorizationRecord;
use App\Models\Student;
use App\Models\Surah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentsData = [
            // Kelas 7
            ['202407001', 'Muhammad Al-Fatih', 'L', '7A', ['Tahfizh', 'Takhassus'], '123456'],
            ['202407002', 'Aisyah Humaira', 'P', '7A', ['Tahfizh'], '123456'],
            ['202407003', 'Zaid bin Tsabit', 'L', '7B', ['Takhassus'], '123456'],
            ['202407004', 'Khadijah Al-Kubra', 'P', '7B', ['Tahsin', 'Tahfizh'], '123456'],
            ['202407005', 'Usman bin Affan', 'L', '7C', ['Tahsin'], '123456'],
            ['202407006', 'Fatimah Az-Zahra', 'P', '7D', ['Tahfizh'], '123456'],

            // Kelas 8
            ['202308001', 'Ali bin Abi Thalib', 'L', '8A', ['Takhassus'], '123456'],
            ['202308002', 'Maryam binti Imran', 'P', '8A', ['Tahfizh', 'Takhassus'], '123456'],
            ['202308003', 'Bilal bin Rabah', 'L', '8B', ['Tahsin', 'Tahfizh'], '123456'],
            ['202308004', 'Hafsah binti Umar', 'P', '8B', ['Tahfizh'], '123456'],
            ['202308005', 'Salman Al-Farisi', 'L', '8C', ['Tahsin'], '123456'],
            ['202308006', 'Ruqayyah binti Rasulullah', 'P', '8D', ['Tahfizh'], '123456'],

            // Kelas 9
            ['202209001', 'Abu Bakar Ash-Shiddiq', 'L', '9A', ['Takhassus'], '123456'],
            ['202209002', 'Ummu Salamah', 'P', '9A', ['Tahfizh'], '123456'],
            ['202209003', 'Umar bin Khattab', 'L', '9B', ['Takhassus', 'Tahfizh'], '123456'],
            ['202209004', 'Zainab binti Jahsy', 'P', '9B', ['Tahsin'], '123456'],
            ['202209005', 'Saad bin Abi Waqqas', 'L', '9C', ['Tahfizh'], '123456'],
            ['202209006', 'Asma binti Abu Bakar', 'P', '9D', ['Tahfizh', 'Tahsin'], '123456'],
        ];

        $surahAnNas = Surah::where('number', 114)->first();
        $surahAlFalaq = Surah::where('number', 113)->first();
        $surahAlIkhlas = Surah::where('number', 112)->first();
        $surahAlFatihah = Surah::where('number', 1)->first();

        foreach ($studentsData as $s) {
            $student = Student::updateOrCreate(
                ['nis' => $s[0]],
                [
                    'name' => $s[1],
                    'gender' => $s[2],
                    'class_name' => $s[3],
                    'categories' => $s[4],
                    'access_pin' => null,
                    'share_token' => Str::random(32),
                    'notes' => 'Siswa aktif dan bersemangat dalam halaqah Al-Qur\'an.',
                ]
            );

            // Seed some sample records for demonstration
            if ($surahAnNas && $student->id <= 6) {
                for ($v = 1; $v <= $surahAnNas->total_verses; $v++) {
                    MemorizationRecord::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'surah_id' => $surahAnNas->id,
                            'verse_number' => $v,
                            'category' => $s[4][0] ?? 'Tahfizh',
                        ],
                        [
                            'grade' => ($v % 3 == 0) ? 'B' : 'A',
                            'recorded_at' => now()->subDays(rand(1, 10)),
                            'teacher_notes' => 'Makharijul huruf fasih.',
                        ]
                    );
                }
            }

            if ($surahAlFalaq && $student->id <= 4) {
                for ($v = 1; $v <= $surahAlFalaq->total_verses; $v++) {
                    MemorizationRecord::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'surah_id' => $surahAlFalaq->id,
                            'verse_number' => $v,
                            'category' => $s[4][0] ?? 'Tahfizh',
                        ],
                        [
                            'grade' => 'A',
                            'recorded_at' => now()->subDays(rand(1, 5)),
                            'teacher_notes' => 'Alhamdulillah mutqin.',
                        ]
                    );
                }
            }
        }
    }
}
