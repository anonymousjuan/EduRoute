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
        $schoolYear = $request->input('schoolYear');

        $latestIds = Student::selectRaw('MAX(id) as id')
            ->when($schoolYear, fn($q) => $q->where('schoolYearTitle', $schoolYear))
            ->groupBy('studentID');

        if ($search) {
            $query = Student::whereIn('id', $latestIds)
                ->where(function ($q) use ($search) {
                    $q->where('studentID', 'like', "%$search%")
                        ->orWhere('firstName', 'like', "%$search%")
                        ->orWhere('lastName', 'like', "%$search%");
                });

            return view('students.index', [
                'search'        => $search,
                'searchResults' => $query->orderBy('lastName')->paginate(25),
                'studentsByYear'=> null,
                'schoolYears'   => Student::select('schoolYearTitle')->distinct()->orderBy('schoolYearTitle', 'desc')->pluck('schoolYearTitle'),
                'activeSY'      => $schoolYear,
            ]);
        }

        $baseQuery = Student::whereIn('id', $latestIds)->orderBy('lastName');
        $studentsByYear = [
            '1st Year' => (clone $baseQuery)->where('yearLevel', '1st Year')->paginate(25, ['*'], 'first_page'),
            '2nd Year' => (clone $baseQuery)->where('yearLevel', '2nd Year')->paginate(25, ['*'], 'second_page'),
            '3rd Year' => (clone $baseQuery)->where('yearLevel', '3rd Year')->paginate(25, ['*'], 'third_page'),
            '4th Year' => (clone $baseQuery)->where('yearLevel', '4th Year')->paginate(25, ['*'], 'fourth_page'),
        ];

        return view('students.index', [
            'search'        => null,
            'searchResults' => null,
            'studentsByYear'=> $studentsByYear,
            'schoolYears'   => Student::select('schoolYearTitle')->distinct()->orderBy('schoolYearTitle', 'desc')->pluck('schoolYearTitle'),
            'activeSY'      => $schoolYear,
        ]);
    }

    /** ➕ Show Create Student Form */
    public function create()
    {
        $courses = DB::table('courses')->select('id', 'courseTitle')->get();
        $faculties = DB::table('faculties')->select('id', 'name')->get();

        return view('students.create', compact('courses', 'faculties'));
    }

    /** 💾 Store New Student */
    public function store(Request $request)
    {
        $request->validate([
            'studentID'       => 'required|unique:students,studentID',
            'firstName'       => 'required|string|max:100',
            'lastName'        => 'required|string|max:100',
            'gender'          => 'required|string|max:10',
            'schoolYearTitle' => 'required|string|max:50',
            'courseID'        => 'nullable|string|max:20',
            'courseTitle'     => 'nullable|string|max:150',
            'yearLevel'       => 'required|string|max:20',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', '✅ Student added successfully!');
    }

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
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv,txt']);
        Excel::import(new StudentsImport, $request->file('file'));
        return back()->with('success', '✅ Students imported successfully!');
    }

    /** 📥 Import CSV */
    public function importCsv(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:2048']);

        try {
            $csv = Reader::createFromPath($request->file('file')->getRealPath(), 'r');
            $csv->setHeaderOffset(0);
            $records = Statement::create()->process($csv);

            DB::beginTransaction();
            foreach ($records as $r) {
                Student::updateOrCreate(
                    ['studentID' => $r['studentID']],
                    [
                        'firstName'       => $r['firstName'] ?? null,
                        'lastName'        => $r['lastName'] ?? null,
                        'middleName'      => $r['middleName'] ?? null,
                        'gender'          => strtoupper($r['gender'] ?? ''),
                        'schoolYearTitle' => $r['schoolYearTitle'] ?? null,
                        'courseID'        => $r['courseID'] ?? null,
                        'courseTitle'     => $r['courseTitle'] ?? null,
                        'yearLevel'       => $r['yearLevel'] ?? null,
                    ]
                );
            }
            DB::commit();
            return back()->with('success', '✅ CSV Imported Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ CSV Import failed: ' . $e->getMessage());
        }
    }

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
                'schoolYearTitle' => $nextSY,
                'yearLevel'       => $nextYearLevel,
            ]);

            DB::commit();
            session()->forget('last_generated');

            return $this->responseMessage($request, true, "✅ Subjects for {$nextSY} generated successfully!");
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->forget('last_generated');
            return $this->responseMessage($request, false, '❌ ' . $e->getMessage());
        }
    }

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
        };
    }

    private function convertNumericToYear($num)
    {
        return match ($num) {
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
            default => '1st Year',
        };
    }
}
