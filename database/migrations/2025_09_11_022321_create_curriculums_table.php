<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();

            // 📌 Curriculum metadata
            $table->string('year_of_implementation'); // e.g. "S.Y. 2024-2025"
            $table->unsignedTinyInteger('year_level'); // 1, 2, 3, 4
            $table->enum('semester', ['1st', '2nd']); // walang summer

            // 📌 Subject info
            $table->string('course_no'); // subject code
            $table->string('descriptive_title'); // subject title
            $table->integer('units')->default(0);
            $table->integer('lec')->default(0);
            $table->integer('lab')->default(0);
            $table->string('prerequisite')->nullable();

            // 📌 Simple takes (text para flexible, pwede AY/Grade/School)
            $table->text('take1')->nullable();
            $table->text('take2')->nullable();
            $table->text('take3')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};
