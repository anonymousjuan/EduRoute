<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('student_grades')) {
            Schema::create('student_grades', function (Blueprint $table) {
                $table->id();
                $table->string('studentID'); 
                $table->string('subjectCode');
                $table->string('subjectTitle')->nullable();
                $table->string('units')->nullable();
                $table->string('yearLevel')->nullable();
                $table->string('schoolYearTitle')->nullable(); // semester or SY
                $table->string('Faculty')->nullable();
                $table->string('FinalRating')->nullable();
                $table->string('RetakeGrade')->nullable();
                $table->timestamps();

                $table->unique(['studentID', 'subjectCode']); // ❌ prevent duplicate
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
