<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    /** 📋 Display all students */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $schoolYearFilter = $request->input('school_year');

        $query = Student::query();

        // 🔍 Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('studentID', 'like', "%$search%")
                    ->orWhere('studentName', 'like', "%$search%")
                    ->orWhere('program', 'like', "%$search%");
            });
        }

        // 🎓 Filter by School Year
        if ($schoolYearFilter) {
            $query->where('schoolYearTitle', $schoolYearFilter);
        }

        // 📘 Group by studentID (only latest per student)
        $students = $query
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('studentID')
            ->pluck('id');

        $latestStudents = Student::whereIn('id', $students)
            ->orderBy('yearLevel', 'asc')
            ->paginate(10);

        $studentsByYear = [
            'First Year' => Student::where('yearLevel', 'First Year')->paginate(10),
            'Second Year' => Student::where('yearLevel', 'Second Year')->paginate(10),
            'Third Year' => Student::where('yearLevel', 'Third Year')->paginate(10),
            'Fourth Year' => Student::where('yearLevel', 'Fourth Year')->paginate(10),
        ];

        $schoolYears = Student::select('schoolYearTitle')->distinct()->pluck('schoolYearTitle');

        return view('students.index', compact(
            'latestStudents',
            'studentsByYear',
            'search',
            'schoolYears',
            'schoolYearFilter'
        ));
    }

    /** ➕ Show form to create a student */
    public function create()
    {
        return view('students.create');
    }

    /** 💾 Store a new student */
    public function store(Request $request)
    {
        $request->validate([
            'studentID' => 'required|unique:students',
            'studentName' => 'required',
            'program' => 'required',
            'yearLevel' => 'required',
            'schoolYearTitle' => 'required',
        ]);

        try {
            Student::create($request->all());
            return redirect()->route('students.index')->with('success', '✅ Student added successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', '❌ Failed to save student: ' . $e->getMessage());
        }
    }

    /** ✏️ Show form to edit a student */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /** 🔁 Update student info */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'studentID' => 'required|unique:students,studentID,' . $id,
            'studentName' => 'required',
            'program' => 'required',
            'yearLevel' => 'required',
            'schoolYearTitle' => 'required',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', '✅ Student updated successfully!');
    }

    /** 🗑️ Delete a student */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        try {
            $student->delete();
            return redirect()->route('students.index')->with('success', '🗑️ Student deleted successfully!');
        } catch (\Throwable $e) {
            return redirect()->route('students.index')->with('error', '❌ Failed to delete student: ' . $e->getMessage());
        }
    }

    /** 📤 Import students from Excel/CSV */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,txt',
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            DB::transaction(function () use ($file, $extension) {
                if ($extension === 'csv' || $extension === 'txt') {
                    $csv = Reader::createFromPath($file->getRealPath(), 'r');
                    $csv->setHeaderOffset(0);
                    $records = Statement::create()->process($csv);

                    foreach ($records as $record) {
                        Student::updateOrCreate(
                            ['studentID' => $record['studentID']],
                            [
                                'studentName' => $record['studentName'],
                                'program' => $record['program'],
                                'yearLevel' => $record['yearLevel'],
                                'schoolYearTitle' => $record['schoolYearTitle'],
                            ]
                        );
                    }
                } else {
                    Excel::import(new StudentsImport, $file);
                }
            });

            return back()->with('success', '✅ Students imported successfully!');
        } catch (Exception $e) {
            return back()->with('error', '❌ Import failed: ' . $e->getMessage());
        }
    }

    /** 📄 View transcript of student */
    public function transcript($studentID)
    {
        $student = Student::where('studentID', $studentID)->latest()->firstOrFail();

        $grades = DB::table('student_grades')
            ->join('subjects', 'student_grades.subjectCode', '=', 'subjects.subjectCode')
            ->where('student_grades.studentID', $studentID)
            ->select('student_grades.*', 'subjects.subjectTitle', 'subjects.units')
            ->orderBy('student_grades.schoolYearTitle', 'asc')
            ->orderBy('student_grades.semester', 'asc')
            ->get();

        return view('students.transcript', compact('student', 'grades'));
    }

    /** 🧮 Generate next semester subjects */
    public function generateSubjects($studentID)
    {
        $student = Student::where('studentID', $studentID)->latest()->firstOrFail();

        if (session('last_generated') === $studentID) {
            return back()->with('error', '⚠️ Subjects already generated recently.');
        }

        DB::beginTransaction();

        try {
            $numericYear = $this->getNumericYearLevel($student->yearLevel);
            $nextYearLevel = $this->convertNumericToYear($numericYear + 1);

            $semester = $student->semester === '1st' ? '2nd' : '1st';
            $schoolYear = explode('-', $student->schoolYearTitle);
            $nextSY = $semester === '1st'
                ? ($schoolYear[0] + 1) . '-' . ($schoolYear[1] + 1)
                : $student->schoolYearTitle;

            $subjects = DB::table('subjects')
                ->where('yearLevel', $nextYearLevel)
                ->where('semester', $semester)
                ->get();

            foreach ($subjects as $sub) {
                DB::table('student_grades')->updateOrInsert(
                    ['studentID' => $studentID, 'subjectCode' => $sub->subjectCode],
                    [
                        'subjectTitle' => $sub->subjectTitle,
                        'units' => $sub->units,
                        'grade' => null,
                        'remarks' => 'Not Yet Taken',
                        'semester' => $semester,
                        'schoolYearTitle' => $nextSY,
                    ]
                );
            }

            Student::create([
                'studentID' => $student->studentID,
                'studentName' => $student->studentName,
                'program' => $student->program,
                'yearLevel' => $nextYearLevel,
                'semester' => $semester,
                'schoolYearTitle' => $nextSY,
            ]);

            DB::commit();
            session(['last_generated' => $studentID]);

            return back()->with('success', '✅ Subjects for next semester generated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ Generation failed: ' . $e->getMessage());
        }
    }

    /** 🔢 Convert Year Level to Numeric */
    private function getNumericYearLevel($level)
    {
        return match ($level) {
            'First Year' => 1,
            'Second Year' => 2,
            'Third Year' => 3,
            'Fourth Year' => 4,
            default => 0,
        };
    }

    /** 🔤 Convert Numeric to Year Level */
    private function convertNumericToYear($num)
    {
        return match ($num) {
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
            default => 'Unknown',
        };
    }
}
