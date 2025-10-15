<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    // Show all faculties
    public function index()
    {
        $faculties = Faculty::all();
        return view('faculties.index', compact('faculties'));
    }

    // Show create form
    public function create()
    {
        $curriculums = DB::table('curriculums')->get();
        return view('faculties.create', compact('curriculums'));
    }

    // Store new faculty + assign subjects + insert to student_grades
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:faculties,email',
            'department' => 'nullable|string|max:255',
            'year_level' => 'required|string',
            'subjects' => 'required|array',
        ]);

        // 1️⃣ Create faculty
        $faculty = Faculty::create([
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
        ]);

        // 2️⃣ Loop through selected subjects
        foreach ($request->subjects as $subjectId) {
            $curriculum = DB::table('curriculums')->where('id', $subjectId)->first();
            if (!$curriculum) continue;

            $curriculumArray = (array) $curriculum;
            $subjectCode = $curriculumArray['subjectCode'] ?? $curriculumArray['subject_code'] ?? null;
            $courseNo = $curriculumArray['course_no'] ?? null;
            $title = $curriculumArray['descriptive_title'] ?? null;
            $units = $curriculumArray['units'] ?? null;

            if (!$subjectCode) continue;

            $exists = DB::table('student_grades')
                ->where('subjectCode', $subjectCode)
                ->where('Faculty', $faculty->name)
                ->exists();

            if (!$exists) {
                DB::table('student_grades')->insert([
                    'studentID' => null,
                    'lastName' => null,
                    'firstName' => null,
                    'middleName' => null,
                    'suffix' => null,
                    'gender' => null,
                    'schoolYearTitle' => null,
                    'courseID' => $courseNo,
                    'courseTitle' => $title,
                    'yearLevel' => $request->year_level,
                    'subjectCode' => $subjectCode,
                    'subjectTitle' => $title,
                    'units' => $units,
                    'Faculty' => $faculty->name,
                    'Final_Rating' => null,
                    'Retake_Grade' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty and subjects saved successfully!');
    }
}
