<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemorizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'surah_id',
        'verse_number',
        'category',
        'grade',
        'recorded_at',
        'teacher_notes',
    ];

    protected function casts(): array
    {
        return [
            'verse_number' => 'integer',
            'recorded_at' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }
}
