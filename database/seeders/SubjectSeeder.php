<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Curriculum;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $curriculum = Curriculum::where('name', 'BAP Curriculum 2024')->first();

        $subjects = [
            ['code' => 'PSY101', 'title' => 'Introduction to Psychology', 'units' => 3],
            ['code' => 'PSY102', 'title' => 'Developmental Psychology', 'units' => 3],
            ['code' => 'PSY103', 'title' => 'Research Methods in Psychology', 'units' => 3],
            ['code' => 'PSY104', 'title' => 'Abnormal Psychology', 'units' => 3],
        ];

        foreach ($subjects as $subj) {
            Subject::firstOrCreate(array_merge($subj, [
                'curriculum_id' => $curriculum->id,
            ]));
        }
    }
}
