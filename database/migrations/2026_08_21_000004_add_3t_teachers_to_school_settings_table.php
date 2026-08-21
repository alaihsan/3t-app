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
        Schema::table('school_settings', function (Blueprint $table) {
            $table->string('teacher_takhassus')->nullable()->default('Ust. Ichsan');
            $table->string('teacher_tahsin')->nullable()->default('Ust. Ahmad Fauzi, S.Pd.I');
            $table->string('teacher_tahfizh')->nullable()->default('Ust. Ichsan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn(['teacher_takhassus', 'teacher_tahsin', 'teacher_tahfizh']);
        });
    }
};
