<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Ana Cruz', 'email' => 'ana.cruz@student.test'],
            ['name' => 'Mark Reyes', 'email' => 'mark.reyes@student.test'],
            ['name' => 'Liza Santos', 'email' => 'liza.santos@student.test'],
        ];

        foreach ($students as $stud) {
            User::firstOrCreate(
                ['email' => $stud['email']],
                [
                    'name' => $stud['name'],
                    'password' => Hash::make('password123'),
                ]
            );
        }
    }
}

