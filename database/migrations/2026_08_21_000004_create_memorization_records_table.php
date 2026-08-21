<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('memorization_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('surah_id')->constrained('surahs')->cascadeOnDelete();
            $table->unsignedSmallInteger('verse_number');
            $table->enum('category', ['Takhassus', 'Tahsin', 'Tahfizh'])->default('Tahfizh');
            $table->enum('grade', ['A', 'B', 'C', 'D'])->default('A');
            $table->date('recorded_at')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'surah_id', 'verse_number', 'category'], 'student_surah_verse_cat_unique');
            $table->index(['student_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorization_records');
    }
};
