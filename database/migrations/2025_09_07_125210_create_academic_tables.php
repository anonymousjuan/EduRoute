<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Programs (only BAP for now, but flexible for future)
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. BAP
            $table->string('name');           // e.g. Bachelor of Arts in Psychology
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Curriculums
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('name'); // e.g. BAP Curriculum 2024
            $table->year('effective_year')->nullable();
            $table->timestamps();
        });

        // Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained('curriculums')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('title');
            $table->unsignedTinyInteger('units')->default(3);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Subject prerequisites (self-referencing many-to-many)
        Schema::create('subject_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('prerequisite_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
        });

        // Instructor assignments per subject
        Schema::create('instructor_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active'); // ✅ active/inactive per instructor
            $table->string('term')->nullable();         // e.g. 1st Semester
            $table->string('school_year')->nullable();  // e.g. 2024-2025
            $table->timestamps();
        });

        // Enrollments (students = default users with no role)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // student
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('term')->nullable();
            $table->string('school_year')->nullable();
            $table->enum('status', ['enrolled', 'completed', 'dropped'])->default('enrolled');
            $table->timestamps();
        });

        // Grades
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('grade_value', 4, 2)->nullable();
            $table->string('remark')->nullable(); // e.g. Passed, Failed, Incomplete
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('instructor_subject');
        Schema::dropIfExists('subject_prerequisites');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('curriculums');
        Schema::dropIfExists('programs');
    }
};
