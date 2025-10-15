<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update any superadmin -> admin
        DB::table('users')
            ->where('role', 'superadmin')
            ->update(['role' => 'admin']);

        // 2. Modify ENUM to remove superadmin (if you want stricter schema)
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['instructor', 'dean', 'programhead', 'admin'])
                  ->default('instructor')
                  ->change();
        });
    }

    public function down(): void
    {
        // Rollback ENUM to include superadmin again
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['instructor', 'dean', 'programhead', 'admin', 'superadmin'])
                  ->default('instructor')
                  ->change();
        });

        // Rollback: turn admin back to superadmin
        DB::table('users')
            ->where('role', 'admin')
            ->update(['role' => 'superadmin']);
    }
};
