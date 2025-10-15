<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('studentID')->index();   // e.g., E25-00222
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('schoolYearTitle')->nullable();
            $table->unsignedBigInteger('courseID')->nullable();
            $table->string('courseTitle')->nullable();
            $table->string('yearLevel')->nullable();
            $table->string('subjectCode')->nullable();
            $table->string('subjectTitle')->nullable();
            $table->integer('units')->nullable();
            $table->string('Faculty')->nullable();
            $table->decimal('Final_Rating', 5, 2)->nullable();
            $table->decimal('Retake_Grade', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_subjects');
    }
};
