<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('grades')) {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('studentID');
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender');
            $table->string('schoolYearTitle');
            $table->string('courseID');
            $table->string('courseTitle');
            $table->string('yearLevel');
            $table->string('subjectCode');
            $table->string('subjectTitle');
            $table->integer('units');
            $table->string('final_rating')->nullable();
            $table->string('retake_grade')->nullable();
            $table->timestamps();
        });
    }
}


    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
