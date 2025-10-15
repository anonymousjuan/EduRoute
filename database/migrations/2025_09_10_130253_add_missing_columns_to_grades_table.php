<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns only if they don't already exist
        if (!Schema::hasColumn('grades', 'studentID')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('studentID')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'lastName')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('lastName')->nullable();
            });
        }

        // repeat for other fields...
        if (!Schema::hasColumn('grades', 'firstName')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('firstName')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'middleName')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('middleName')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'suffix')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('suffix')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'gender')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('gender')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'schoolYearTitle')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('schoolYearTitle')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'courseID')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('courseID')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'courseTitle')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('courseTitle')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'yearLevel')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('yearLevel')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'subjectCode')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('subjectCode')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'subjectTitle')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('subjectTitle')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'units')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->integer('units')->nullable();
            });
        }

        // DB columns mapped to snake_case for the JSON keys with spaces
        if (!Schema::hasColumn('grades', 'final_rating')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('final_rating')->nullable();
            });
        }

        if (!Schema::hasColumn('grades', 'retake_grade')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('retake_grade')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Optionally remove the columns (be careful in production)
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'studentID')) $table->dropColumn('studentID');
            if (Schema::hasColumn('grades', 'lastName')) $table->dropColumn('lastName');
            if (Schema::hasColumn('grades', 'firstName')) $table->dropColumn('firstName');
            if (Schema::hasColumn('grades', 'middleName')) $table->dropColumn('middleName');
            if (Schema::hasColumn('grades', 'suffix')) $table->dropColumn('suffix');
            if (Schema::hasColumn('grades', 'gender')) $table->dropColumn('gender');
            if (Schema::hasColumn('grades', 'schoolYearTitle')) $table->dropColumn('schoolYearTitle');
            if (Schema::hasColumn('grades', 'courseID')) $table->dropColumn('courseID');
            if (Schema::hasColumn('grades', 'courseTitle')) $table->dropColumn('courseTitle');
            if (Schema::hasColumn('grades', 'yearLevel')) $table->dropColumn('yearLevel');
            if (Schema::hasColumn('grades', 'subjectCode')) $table->dropColumn('subjectCode');
            if (Schema::hasColumn('grades', 'subjectTitle')) $table->dropColumn('subjectTitle');
            if (Schema::hasColumn('grades', 'units')) $table->dropColumn('units');
            if (Schema::hasColumn('grades', 'final_rating')) $table->dropColumn('final_rating');
            if (Schema::hasColumn('grades', 'retake_grade')) $table->dropColumn('retake_grade');
        });
    }
};
