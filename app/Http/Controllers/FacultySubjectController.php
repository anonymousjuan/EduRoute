<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentGrade;

class FacultySubjectController extends Controller
{
    public function show($subjectCode)
    {
        $subject = StudentGrade::where('subjectCode', $subjectCode)->firstOrFail();

        // Get all students for this subject, ordered by enrollment and last name
        $students = StudentGrade::where('subjectCode', $subjectCode)
            ->orderByRaw("CASE WHEN Final_Rating IS NOT NULL THEN 0 ELSE 1 END") // enrolled first
            ->orderBy('lastName')
            ->get()
            ->groupBy('schoolYearTitle'); // group by school year

        // Custom sort: 1st Semester AY 2025-2026 first, then descending order
        $students = $students->sortKeysUsing(function($a, $b) {
            if ($a === '1st Semester AY 2025-2026') return -1;
            if ($b === '1st Semester AY 2025-2026') return 1;
            return strcmp($b, $a); // descending for others
        });

        return view('faculty-subjects.show', compact('subject', 'students'));
    }

    public function update(Request $request, $subjectCode)
    {
        $grades = $request->input('grades', []);

        foreach ($grades as $studentId => $grade) {
            StudentGrade::where('id', $studentId)->update(['Final_Rating' => $grade]);
        }

        return redirect()->back()->with('success', 'Grades updated successfully!');
    }
}
