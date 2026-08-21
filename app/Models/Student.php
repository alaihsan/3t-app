<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    public const CLASSES = [
        '7A', '7B', '7C', '7D',
        '8A', '8B', '8C', '8D',
        '9A', '9B', '9C', '9D',
    ];

    public const CATEGORIES = [
        'Takhassus',
        'Tahsin',
        'Tahfizh',
    ];

    protected $fillable = [
        'nis',
        'name',
        'gender',
        'class_name',
        'categories',
        'share_token',
        'access_pin',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->share_token)) {
                $student->share_token = Str::random(32);
            }
        });
    }

    public function memorizationRecords(): HasMany
    {
        return $this->hasMany(MemorizationRecord::class, 'student_id');
    }

    /**
     * Get memorization stats summary for the student
     */
    public function getStatsAttribute(): array
    {
        $totalVersesMemorized = $this->memorizationRecords()->count();
        $totalSurahsTouched = $this->memorizationRecords()->distinct('surah_id')->count('surah_id');
        
        $gradeCounts = $this->memorizationRecords()
            ->selectRaw('grade, count(*) as count')
            ->groupBy('grade')
            ->pluck('count', 'grade')
            ->toArray();

        // Total Al-Quran verses is 6236
        $percentageTotal = round(($totalVersesMemorized / 6236) * 100, 2);

        // Average grade calculation
        $dominantGrade = 'A';
        if (!empty($gradeCounts)) {
            arsort($gradeCounts);
            $dominantGrade = array_key_first($gradeCounts);
        }

        return [
            'total_verses' => $totalVersesMemorized,
            'total_surahs' => $totalSurahsTouched,
            'percentage_total' => $percentageTotal,
            'grade_counts' => $gradeCounts,
            'dominant_grade' => $dominantGrade,
        ];
    }
}
