<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender')->nullable();
            $table->string('school_year_title')->nullable();
            $table->string('course_id')->nullable();
            $table->string('course_title')->nullable();
            $table->string('year_level')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('subject_title')->nullable();
            $table->integer('units')->nullable();
            $table->string('faculty')->nullable();
            $table->string('final_rating')->nullable();
            $table->string('retake_grade')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
