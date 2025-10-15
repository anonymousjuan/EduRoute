<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        Curriculum::firstOrCreate([
            'name' => 'BAP Curriculum 2024',
            'effective_year' => 2024,
        ]);
    }
}
