<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentGrade;
use App\Models\Faculty;
use App\Models\Course;

class StudentGradeController extends Controller
{
    /** Show form to create a new student grade */
    public function create()
    {
        $courses = Course::all();      // Make sure you have courses table & model
        $faculties = Faculty::all();   // Fetch all faculties

        return view('students.create', compact('courses', 'faculties'));
    }

    /** Store new student grade */
    public function store(Request $request)
    {
        // Validate request fields
        $validated = $request->validate([
            'studentID'       => 'required|string|max:255',
            'firstName'       => 'required|string|max:255',
            'lastName'        => 'required|string|max:255',
            'middleName'      => 'nullable|string|max:255',
            'suffix'          => 'nullable|string|max:255',
            'gender'          => 'required|string|max:10',
            'schoolYearTitle' => 'required|string|max:255',
            'courseID'        => 'required|string|max:100',
            'courseTitle'     => 'required|string|max:255',
            'yearLevel'       => 'required|string|max:50',
            'faculty_id'      => 'required|exists:faculties,id',
            'subjectCode'     => 'required|string|max:100',
            'subjectTitle'    => 'required|string|max:255',
            'units'           => 'nullable|integer',
        ]);

        // Get faculty name from faculties table
        $faculty = Faculty::find($validated['faculty_id']);

        // Create student grade record
        StudentGrade::create([
            'studentID'       => $validated['studentID'],
            'firstName'       => $validated['firstName'],
            'lastName'        => $validated['lastName'],
            'middleName'      => $validated['middleName'],
            'suffix'          => $validated['suffix'],
            'gender'          => $validated['gender'],
            'schoolYearTitle' => $validated['schoolYearTitle'],
            'courseID'        => $validated['courseID'],
            'courseTitle'     => $validated['courseTitle'],
            'yearLevel'       => $validated['yearLevel'],
            'subjectCode'     => $validated['subjectCode'],
            'subjectTitle'    => $validated['subjectTitle'],
            'units'           => $validated['units'] ?? null,
            'Faculty'         => $faculty ? $faculty->name : null,
            'faculty_id'      => $validated['faculty_id'],
        ]);

        return redirect()->back()->with('success', '✅ Student successfully added!');
    }
}
