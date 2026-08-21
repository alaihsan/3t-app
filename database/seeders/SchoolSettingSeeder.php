<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolSetting::updateOrCreate(
            ['id' => 1],
            [
                'school_name' => 'SMP ISLAM AL AZHAR 22 SENTRA PRIMER',
                'school_address' => 'Sentra Primer Baru Timur, Pulo Gebang, Jakarta Timur',
                'academic_year' => '2025/2026',
                'semester' => '1',
                'teacher_name' => 'Ust. Ichsan',
                'logo_path' => null,
            ]
        );
    }
}
