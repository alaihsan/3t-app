<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_address',
        'academic_year',
        'semester',
        'teacher_name',
        'teacher_takhassus',
        'teacher_tahsin',
        'teacher_tahfizh',
        'logo_path',
    ];

    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'school_name' => 'SMP ISLAM AL AZHAR 22 SENTRA PRIMER',
            'school_address' => 'Sentra Primer Baru Timur, Pulo Gebang, Jakarta Timur',
            'academic_year' => '2025/2026',
            'semester' => '1',
            'teacher_name' => 'Ust. Ichsan',
            'teacher_takhassus' => 'Ust. Ichsan',
            'teacher_tahsin' => 'Ust. Ahmad Fauzi, S.Pd.I',
            'teacher_tahfizh' => 'Ust. Ichsan',
            'logo_path' => null,
        ]);
    }
}
