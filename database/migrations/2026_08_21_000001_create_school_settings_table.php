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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMP / SMA Tahfizh Islamic Boarding School');
            $table->string('school_address')->nullable()->default('Jl. Raya Pendidikan Karakter No. 99, Indonesia');
            $table->string('academic_year')->default('2025/2026');
            $table->string('semester')->default('Ganjil');
            $table->string('teacher_name')->default('Ust. Muhammad Zulkarnain, S.Pd.I');
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
