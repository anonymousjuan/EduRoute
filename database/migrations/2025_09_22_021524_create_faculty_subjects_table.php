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
       Schema::create('faculty_subjects', function (Blueprint $table) {
    $table->id();
    $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
    $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
    $table->string('school_year_title'); // ex: "2nd Semester AY 2020-2021"
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_subjects');
    }
};
