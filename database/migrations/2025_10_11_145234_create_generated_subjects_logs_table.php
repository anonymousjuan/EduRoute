<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('generated_subjects_logs', function (Blueprint $table) {
        $table->id();
        $table->string('studentID');
        $table->string('generated_by')->nullable();
        $table->string('from_school_year')->nullable();
        $table->string('to_school_year')->nullable();
        $table->timestamp('generated_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_subjects_logs');
    }
};
