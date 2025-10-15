<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    public function model(array $row)
    {
        return new Student([
            'studentID'       => $row[0] ?? null,
            'firstName'       => $row[1] ?? null,
            'lastName'        => $row[2] ?? null,
            'gender'          => $row[3] ?? null,
            'courseTitle'     => $row[4] ?? null,
            'schoolYearTitle' => $row[5] ?? null,
            'yearLevel'       => $row[6] ?? null,
        ]);
    }
}
