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
        Schema::create('surahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('number')->unique();
            $table->string('name_latin');
            $table->string('name_arabic');
            $table->string('translation_id');
            $table->unsignedSmallInteger('total_verses');
            $table->unsignedTinyInteger('juz_start');
            $table->unsignedTinyInteger('juz_end');
            $table->string('revelation_type')->default('Makkiyah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};
