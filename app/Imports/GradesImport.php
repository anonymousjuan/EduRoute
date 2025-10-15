<?php

namespace App\Imports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\ToModel;

class GradesImport implements ToModel
{
    public function model(array $row)
    {
        // Adjust columns according to your Excel file
        return new Grade([
            'studentID'     => $row[0],
            'lastName'      => $row[1],
            'firstName'     => $row[2],
            'middleName'    => $row[3],
            'subjectCode'   => $row[4],
            'subjectTitle'  => $row[5],
            'grade'         => $row[6],
            'yearLevel'     => $row[7],
            'courseTitle'   => $row[8],
            'schoolYearTitle' => $row[9],
        ]);
    }
}
