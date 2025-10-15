<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentGrade;

class MySubjectsController extends Controller
{
    public function index()
    {
        $facultyId = Auth::id();

        $subjects = StudentGrade::where('faculty_id', $facultyId)
            ->select('subjectCode', 'subjectTitle')
            ->groupBy('subjectCode', 'subjectTitle')
            ->get();

        return view('my-subjects.index', compact('subjects'));
    }

    public function show($subjectCode)
    {
        $facultyId = Auth::id();

        $subject = StudentGrade::where('faculty_id', $facultyId)
            ->where('subjectCode', $subjectCode)
            ->select('subjectCode', 'subjectTitle')
            ->firstOrFail();

        $students = StudentGrade::where('faculty_id', $facultyId)
            ->where('subjectCode', $subjectCode)
            ->orderByRaw("CASE WHEN Final_Rating IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('lastName')
            ->get()
            ->groupBy('schoolYearTitle');

        $students = $students->sortKeysUsing(function($a, $b) {
            if ($a === '1st Semester AY 2025-2026') return -1;
            if ($b === '1st Semester AY 2025-2026') return 1;
            return strcmp($b, $a);
        });

        return view('my-subjects.show', compact('subject', 'students'));
    }

    public function update(Request $request, $subjectCode)
    {
        $facultyId = Auth::id();
        $grades = $request->input('grades', []);

        foreach ($grades as $studentId => $grade) {
            StudentGrade::where('id', $studentId)
                ->where('faculty_id', $facultyId)
                ->where('subjectCode', $subjectCode)
                ->update(['Final_Rating' => $grade]);
        }

        return redirect()->back()->with('success', 'Grades updated successfully!');
    }

    public function createStudent($subjectCode)
    {
        $facultyId = Auth::id();

        $subject = StudentGrade::where('faculty_id', $facultyId)
            ->where('subjectCode', $subjectCode)
            ->select('subjectCode', 'subjectTitle')
            ->firstOrFail();

        return view('my-subjects.students.create', compact('subject'));
    }
}
