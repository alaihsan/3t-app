<?php

namespace App\Services;

use App\Models\MemorizationRecord;
use App\Models\Student;
use App\Models\Surah;

/**
 * Combines Qur'an text/translation from the Quran Foundation API with
 * this app's own memorization data, so the reading view can highlight
 * verses a given student has already memorized.
 */
class QuranProgressService
{
    public function __construct(private readonly QuranFoundationClient $client)
    {
    }

    /**
     * @return array{surah: Surah, verses: array, memorizedVerses: array<int, string>}
     */
    public function buildReadingData(Surah $surah, ?Student $student = null): array
    {
        $translationId = config('services.quran_foundation.translation_resource_id')
            ?? $this->client->resolveIndonesianTranslationId();

        $verses = $this->client->getVersesByChapter($surah->number, (int) $translationId);

        $memorizedVerses = [];
        if ($student !== null) {
            foreach (MemorizationRecord::where('student_id', $student->id)
                ->where('surah_id', $surah->id)
                ->get() as $record) {
                $memorizedVerses[$record->verse_number] = $record->grade;
            }
        }

        return [
            'surah' => $surah,
            'verses' => $verses,
            'memorizedVerses' => $memorizedVerses,
        ];
    }
}
