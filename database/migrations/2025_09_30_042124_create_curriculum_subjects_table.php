<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('curriculum_subjects')) {
            Schema::create('curriculum_subjects', function (Blueprint $table) {
                $table->id();
                $table->string('courseID');        // e.g., BAP
                $table->string('subjectCode');     // e.g., PSYCH101
                $table->string('subjectTitle');    // e.g., General Psychology
                $table->integer('units');          // number of units
                $table->string('semester')->nullable();  // 1st / 2nd Semester
                $table->string('yearLevel')->nullable(); // 1 / 2 / 3 / 4
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_subjects');
    }
};
