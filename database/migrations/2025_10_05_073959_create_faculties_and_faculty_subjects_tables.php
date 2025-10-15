<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('department')->nullable();
            $table->timestamps();
        });

        // 🧠 Faculty-Subject Assignment
        Schema::create('faculty_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
            $table->foreignId('curriculum_id')->constrained('curriculums')->onDelete('cascade');
            $table->tinyInteger('year_level'); // from students_grades yearlevel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_subjects');
        Schema::dropIfExists('faculties');
    }
};
