<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Subject;
use App\Models\InstructorSubject;
use Illuminate\Support\Facades\Hash;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $instructorRole = Role::where('name', 'instructor')->first();
        $subjects = Subject::all();

        $instructors = [
            ['name' => 'Dr. Maria Santos', 'email' => 'maria.santos@eduroute.test'],
            ['name' => 'Prof. Juan Dela Cruz', 'email' => 'juan.delacruz@eduroute.test'],
            ['name' => 'Dr. Josefa Reyes', 'email' => 'josefa.reyes@eduroute.test'],
        ];

        foreach ($instructors as $index => $inst) {
            $user = User::firstOrCreate(
                ['email' => $inst['email']],
                [
                    'name' => $inst['name'],
                    'password' => Hash::make('password123'),
                ]
            );

            $user->roles()->syncWithoutDetaching([$instructorRole->id]);

            // assign to random subject with active/inactive status
            $subject = $subjects->random();
            InstructorSubject::firstOrCreate([
                'instructor_id' => $user->id,
                'subject_id' => $subject->id,
                'status' => $index % 2 == 0 ? 'active' : 'inactive',
                'term' => '1st Semester',
                'school_year' => '2024-2025',
            ]);
        }
    }
}
