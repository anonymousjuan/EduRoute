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

<<<<<<< HEAD
    /** ✏️ Show form to edit a student */
    public function edit($id)
=======
    /** ✏️ Show Edit Form */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $courses = DB::table('courses')->select('id', 'courseTitle')->get();
        $faculties = DB::table('faculties')->select('id', 'name')->get();

        return view('students.edit', compact('student', 'courses', 'faculties'));
    }

    /** 💾 Update Student Info */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'studentID'       => 'required|unique:students,studentID,' . $id,
            'firstName'       => 'required|string|max:100',
            'lastName'        => 'required|string|max:100',
            'gender'          => 'required|string|max:10',
            'schoolYearTitle' => 'required|string|max:50',
            'courseID'        => 'nullable|string|max:20',
            'courseTitle'     => 'nullable|string|max:150',
            'yearLevel'       => 'required|string|max:20',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', '✅ Student updated successfully!');
    }

    /** 🗑️ Delete Student */
    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);

            // Delete related grades first to avoid foreign key constraint issues
            DB::table('student_grades')->where('studentID', $student->studentID)->delete();

            // Delete the student record
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', '🗑️ Student deleted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('students.index')
                ->with('error', '❌ Database error while deleting student: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('students.index')
                ->with('error', '❌ Failed to delete student: ' . $e->getMessage());
        }
    }

    /** 📥 Import Excel */
    public function import(Request $request)
>>>>>>> experiment
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

<<<<<<< HEAD
            Student::create([
                'studentID' => $student->studentID,
                'studentName' => $student->studentName,
                'program' => $student->program,
                'yearLevel' => $nextYearLevel,
                'semester' => $semester,
=======
    /** 🧾 Transcript View */
    public function transcript($studentID)
    {
        $student = Student::where('studentID', $studentID)->firstOrFail();
        $grades = DB::table('student_grades')
            ->where('studentID', $studentID)
            ->orderBy('yearLevel')
            ->orderBy('schoolYearTitle')
            ->get();

        return view('transcript', compact('student', 'grades'));
    }

    /** 🎓 Generate Next Semester Subjects */
    public function generateSubjects($studentID, Request $request)
    {
        try {
            $unitLimit = (int) $request->input('unitLimit', 0);
            $student = Student::where('studentID', $studentID)->latest()->firstOrFail();

            if (session('last_generated') === $studentID) {
                return $this->responseMessage($request, false, "⚠️ Generation already in progress. Try again later.");
            }
            session(['last_generated' => $studentID]);

            $currentSY = $student->schoolYearTitle;
            preg_match('/(\d{4})-(\d{4})/', $currentSY, $match);
            $startYear = (int)($match[1] ?? now()->year);
            $endYear = (int)($match[2] ?? ($startYear + 1));
            $isFirst = Str::contains(strtolower($currentSY), '1st');
            $currentSemester = $isFirst ? '1st' : '2nd';

            if ($currentSemester === '1st') {
                $nextSemester = '2nd';
                $nextSY = "2nd Semester AY {$startYear}-{$endYear}";
            } else {
                $nextSemester = '1st';
                $nextSY = "1st Semester AY " . ($startYear + 1) . "-" . ($endYear + 1);
            }

            DB::beginTransaction();

            $alreadyGenerated = DB::table('student_grades')
                ->where('studentID', $studentID)
                ->where('schoolYearTitle', $nextSY)
                ->lockForUpdate()
                ->exists();

            if ($alreadyGenerated) {
                DB::rollBack();
                return $this->responseMessage($request, false, "⚠️ {$nextSY} already generated.");
            }

            $currentYearNumeric = $this->getNumericYearLevel($student->yearLevel);
            $nextYearNumeric = $currentSemester === '2nd'
                ? min($currentYearNumeric + 1, 4)
                : $currentYearNumeric;
            $nextYearLevel = $this->convertNumericToYear($nextYearNumeric);

            $grades = DB::table('student_grades')
                ->where('studentID', $studentID)
                ->get(['subjectCode', 'Final_Rating', 'Retake_Grade']);

            $passedSubjects = [];
            foreach ($grades as $g) {
                $grade = $g->Retake_Grade ?: $g->Final_Rating;
                if ($grade === null || $grade === '' || in_array(strtoupper($grade), ['INC', 'IP', 'D', 'F'])) continue;
                if (is_numeric($grade) && $grade <= 3.0) $passedSubjects[] = $g->subjectCode;
            }

            $curriculumYear = in_array($nextYearNumeric, [1, 2]) ? '2024-2025' : '2022-2023';

            $nextSubjects = DB::table('curriculums')
                ->where('year_of_implementation', $curriculumYear)
                ->where('year_level', $nextYearNumeric)
                ->where('semester', $nextSemester)
                ->get();

            $subjectsToInsert = [];
            $totalUnits = 0;

            foreach ($nextSubjects as $subj) {
                $prereqs = array_filter(array_map('trim', explode(',', (string)$subj->prerequisite)));
                $allPassed = true;
                foreach ($prereqs as $p) {
                    if ($p && !in_array($p, $passedSubjects)) {
                        $allPassed = false;
                        break;
                    }
                }

                if (!$allPassed || in_array($subj->course_no, $passedSubjects)) continue;
                if ($unitLimit > 0 && ($totalUnits + $subj->units) > $unitLimit) break;

                $subjectsToInsert[] = [
                    'studentID'       => $student->studentID,
                    'lastName'        => $student->lastName,
                    'firstName'       => $student->firstName,
                    'middleName'      => $student->middleName,
                    'suffix'          => $student->suffix,
                    'gender'          => $student->gender,
                    'schoolYearTitle' => $nextSY,
                    'courseID'        => $student->courseID,
                    'courseTitle'     => $student->courseTitle,
                    'yearLevel'       => $nextYearLevel,
                    'subjectCode'     => $subj->course_no,
                    'subjectTitle'    => $subj->descriptive_title,
                    'units'           => $subj->units,
                    'Final_Rating'    => null,
                    'Retake_Grade'    => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                $totalUnits += $subj->units;
            }

            if (empty($subjectsToInsert)) {
                DB::rollBack();
                return $this->responseMessage($request, false, "⚠️ No eligible subjects found for {$nextSY}.");
            }

            DB::table('student_grades')->insert($subjectsToInsert);
            $student->update([
>>>>>>> experiment
                'schoolYearTitle' => $nextSY,
            ]);

            DB::commit();
<<<<<<< HEAD
            session(['last_generated' => $studentID]);

            return back()->with('success', '✅ Subjects for next semester generated successfully!');
=======
            session()->forget('last_generated');

            return $this->responseMessage($request, true, "✅ Subjects for {$nextSY} generated successfully!");
>>>>>>> experiment
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ Generation failed: ' . $e->getMessage());
        }
    }

<<<<<<< HEAD
    /** 🔢 Convert Year Level to Numeric */
    private function getNumericYearLevel($level)
    {
        return match ($level) {
            'First Year' => 1,
            'Second Year' => 2,
            'Third Year' => 3,
            'Fourth Year' => 4,
            default => 0,
=======
    /** 🔧 Helper: Response */
    private function responseMessage($request, $success, $msg)
    {
        return $request->ajax()
            ? response()->json(['success' => $success, 'message' => $msg])
            : back()->with($success ? 'success' : 'error', $msg);
    }

    /** 🔧 Helper: Year Conversion */
    private function getNumericYearLevel($yearLevel)
    {
        return match (true) {
            str_contains($yearLevel, '1') => 1,
            str_contains($yearLevel, '2') => 2,
            str_contains($yearLevel, '3') => 3,
            str_contains($yearLevel, '4') => 4,
            default => 1,
>>>>>>> experiment
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
