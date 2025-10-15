<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed roles first
        $this->call(RoleSeeder::class);

        // 2. Fetch roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $deanRole       = Role::where('name', 'dean')->first();
        $phdRole        = Role::where('name', 'program_head')->first();
        $instructorRole = Role::where('name', 'instructor')->first();

        // 3. Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('spradmn123'),
            ]
        );
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        // 4. Dean
        $dean = User::firstOrCreate(
            ['email' => 'dean@gmail.com'],
            [
                'name' => 'Dean User',
                'password' => Hash::make('dean123'),
            ]
        );
        if ($deanRole) {
            $dean->roles()->syncWithoutDetaching([$deanRole->id]);
        }

        // 5. Program Head
        $phd = User::firstOrCreate(
            ['email' => 'programhead@gmail.com'],
            [
                'name' => 'Program Head User',
                'password' => Hash::make('phd123'),
            ]
        );
        if ($phdRole) {
            $phd->roles()->syncWithoutDetaching([$phdRole->id]);
        }

        // 6. Instructor
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@gmail.com'],
            [
                'name' => 'Instructor User',
                'password' => Hash::make('inst123'),
            ]
        );
        if ($instructorRole) {
            $instructor->roles()->syncWithoutDetaching([$instructorRole->id]);
        }

        // 7. Call other seeders (make sure they exist before running)
        $this->call([
            ProgramSeeder::class,
            CurriculumSeeder::class,
            SubjectSeeder::class,
            InstructorSeeder::class,
            StudentSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
