<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->string('year_level')->after('prerequisite')->nullable();
            $table->string('semester')->after('year_level')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->dropColumn(['year_level', 'semester']);
        });
    
    }
};
Schema::create('curriculums', function (Blueprint $table) {
    $table->id();
    $table->string('course_no');
    $table->string('descriptive_title');
    $table->integer('units');
    $table->integer('lec')->nullable();
    $table->integer('lab')->nullable();
    $table->string('prerequisite')->nullable();
    $table->string('year_level')->nullable();
    $table->string('semester')->nullable();
    $table->timestamps();
});
