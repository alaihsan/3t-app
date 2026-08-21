<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name_latin',
        'name_arabic',
        'translation_id',
        'total_verses',
        'juz_start',
        'juz_end',
        'revelation_type',
    ];

    public function memorizationRecords(): HasMany
    {
        return $this->hasMany(MemorizationRecord::class, 'surah_id');
    }
}
