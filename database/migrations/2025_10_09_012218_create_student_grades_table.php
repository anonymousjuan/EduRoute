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

            // Match phpMyAdmin
            $table->string('studentID'); // varchar(255)
            $table->string('lastName')->nullable();
            $table->string('firstName')->nullable();
            $table->string('middleName')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('schoolYearTitle')->nullable();
            $table->string('courseID', 100)->nullable();
            $table->string('courseTitle')->nullable();
            $table->string('yearLevel', 50)->nullable();
            $table->string('subjectCode', 100)->nullable();
            $table->string('subjectTitle')->nullable();
            $table->integer('units')->nullable();
            $table->string('Faculty')->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable();

            // ✅ Match your existing columns
            $table->string('Final_Rating', 50)->nullable();
            $table->string('Retake_Grade', 50)->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('faculty_id')
                  ->references('id')
                  ->on('faculties')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
