<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLockedToStudentGradesTable extends Migration
{
    public function up()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('Final_Rating'); // adjust position as needed
        });
    }

    public function down()
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropColumn('is_locked');
        });
    }
}
