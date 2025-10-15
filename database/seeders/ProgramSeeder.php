<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        Program::firstOrCreate([
            'code' => 'BAP',
            'name' => 'Bachelor of Arts in Psychology',
            'description' => 'Program for students pursuing BA Psychology',
        ]);
    }
}
