<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentGrade;

class ImportBapGrades extends Command
{
    protected $signature = 'import:bapgrades';
    protected $description = 'Import BAP grades from JSON file into student_grades table';

    public function handle()
    {
        $file = storage_path('app/BAPgrade-1.json');

        if (!file_exists($file)) {
            $this->error("❌ File BAPgrade-1.json not found in storage/app/");
            return Command::FAILURE;
        }

        $json = file_get_contents($file);
        $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("❌ Failed to decode JSON: " . json_last_error_msg());
            return Command::FAILURE;
        }

        $normalized = collect($data)->map(function ($item) {
            $fixed = [];
            foreach ($item as $key => $value) {
                $newKey = lcfirst(str_replace(' ', '', ucwords($key)));
                $fixed[$newKey] = $value;
            }
            return $fixed;
        });

        $inserted = 0;
        $updated  = 0;

        foreach ($normalized as $row) {
            $record = StudentGrade::updateOrCreate(
                [
                    'studentID'   => $row['studentID'] ?? null,
                    'subjectCode' => $row['subjectCode'] ?? null,
                ],
                [
                    'lastName'        => $row['lastName'] ?? null,
                    'firstName'       => $row['firstName'] ?? null,
                    'middleName'      => $row['middleName'] ?? null,
                    'suffix'          => $row['suffix'] ?? null,
                    'gender'          => $row['gender'] ?? null,
                    'schoolYearTitle' => $row['schoolYearTitle'] ?? null,
                    'courseID'        => $row['courseID'] ?? null,
                    'courseTitle'     => $row['courseTitle'] ?? null,
                    'yearLevel'       => $row['yearLevel'] ?? null,
                    'subjectTitle'    => $row['subjectTitle'] ?? null,
                    'units'           => $row['units'] ?? null,
                    'Faculty'         => $row['Faculty'] ?? null,
                    'Final_Rating'    => $row['Final_Rating'] ?? null,
                    'Retake_Grade'    => $row['Retake_Grade'] ?? null,
                ]
            );

            if ($record->wasRecentlyCreated) $inserted++;
            else $updated++;
        }

        $this->info("✅ Imported/Updated " . count($normalized) . " records successfully!");
        $this->info("➕ New records added: $inserted");
        $this->info("♻️ Records updated: $updated");

        return Command::SUCCESS;
    }
}
