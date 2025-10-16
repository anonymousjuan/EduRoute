<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentGrade;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GradesImport;

class GradeController extends Controller
{
    /**
     * 📂 Grades main page (List view tabs)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $yearLevel = $request->input('yearLevel');

        $query = Grade::select(
                'studentID',
                'lastName',
                'firstName',
                'middleName',
                'suffix',
                'gender',
                'schoolYearTitle',
                'courseTitle',
                'yearLevel'
            )
            ->groupBy(
                'studentID',
                'lastName',
                'firstName',
                'middleName',
                'suffix',
                'gender',
                'schoolYearTitle',
                'courseTitle',
                'yearLevel'
            )
            ->orderByRaw("
                CASE
                    WHEN studentID LIKE 'E25%' THEN 0
                    WHEN studentID LIKE 'E24%' THEN 1
                    WHEN studentID LIKE 'E23%' THEN 2
                    WHEN studentID LIKE 'E22%' THEN 3
                    WHEN studentID LIKE 'E21%' THEN 4
                    WHEN studentID LIKE 'E20%' THEN 5
                    WHEN studentID LIKE 'E19%' THEN 6
                    ELSE 7
                END ASC
            ")
            ->orderBy('lastName', 'ASC');

        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(studentID) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(lastName) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(firstName) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(courseTitle) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(schoolYearTitle) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('CAST(yearLevel AS CHAR) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        if (!empty($yearLevel)) {
            $query->where('yearLevel', $yearLevel);
        }

        $grades = $query->paginate(25);

        return view('grades.index', [
            'grades' => $grades ?? collect()
        ]);
    }

    /**
     * 🆕 Create new grade form
     */
    public function create()
    {
        $students = Student::orderBy('lastName')->get();
        return view('grades.students.create', compact('students'));
    }

    /**
     * 💾 Store new grade record
     */
    public function store(Request $request)
    {
        $request->validate([
            'studentID' => 'required|exists:students,studentID',
            'subjectCode' => 'required|string|max:50',
            'subjectTitle' => 'required|string|max:255',
            'Final_Rating' => 'nullable|string|max:10',
            'yearLevel' => 'required|integer',
            'courseTitle' => 'nullable|string|max:255',
            'schoolYearTitle' => 'nullable|string|max:255',
            'Faculty' => 'nullable|string|max:255',
        ]);

        StudentGrade::create([
            'studentID' => $request->studentID,
            'subjectCode' => $request->subjectCode,
            'subjectTitle' => $request->subjectTitle,
            'Final_Rating' => $request->Final_Rating,
            'yearLevel' => $request->yearLevel,
            'courseTitle' => $request->courseTitle,
            'schoolYearTitle' => $request->schoolYearTitle,
            'Faculty' => $request->Faculty,
            'is_locked' => false,
        ]);

        return redirect()->route('grades.index')->with('success', '✅ Grade record added successfully!');
    }

    /**
     * 🎓 Show student transcript by ID
     */
    public function show($studentId)
    {
        if (Storage::exists('BAPgrade-1.json')) {
            $json = Storage::get('BAPgrade-1.json');
            $allGrades = collect(json_decode($json, true));

            $grades = $allGrades->map(function ($row) {
                $newRow = [];
                foreach ($row as $key => $value) {
                    $cleanKey = str_replace(' ', '_', $key);
                    $cleanKey = lcfirst($cleanKey);
                    $newRow[$cleanKey] = $value;
                }
                return (object) $newRow;
            })->where('studentID', $studentId);

            if ($grades->isEmpty()) {
                return redirect()->route('grades.index')
                    ->withErrors(['not_found' => 'No grades found in JSON for this student.']);
            }

            $student = $grades->first();
        } else {
            $grades = Grade::where('studentID', $studentId)
                ->orderByRaw("CAST(yearLevel AS UNSIGNED) ASC")
                ->orderBy('subjectCode', 'ASC')
                ->get();

            if ($grades->isEmpty()) {
                return redirect()->route('grades.index')
                    ->withErrors(['not_found' => 'No grades found in database for this student.']);
            }

            $student = $grades->first();
        }

        return view('transcript', [
            'student' => $student,
            'grades'  => $grades
        ]);
    }

    /**
     * 📥 Filter Dr. Oyando, James subjects from JSON
     */
    public function oyando()
    {
        if (!Storage::exists('BAPgrade-1.json')) {
            return redirect()->back()->withErrors(['error' => '❌ File "BAPgrade-1.json" not found in storage/app.']);
        }

        $json = Storage::get('BAPgrade-1.json');
        $data = json_decode($json, true);

        if (is_null($data)) {
            return redirect()->back()->withErrors(['error' => '❌ Invalid JSON format in BAPgrade-1.json.']);
        }

        $filtered = array_values(array_filter($data, function ($entry) {
            return isset($entry['Faculty']) && $entry['Faculty'] === 'DR.OYANDO, JAMES';
        }));

        Storage::put('Dr_Oyando_Subjects.json', json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return view('grades.oyando', ['subjects' => $filtered]);
    }

    /**
     * ✏️ Edit student grades (all grades)
     */
    public function edit($studentID)
    {
        $student = Student::where('studentID', $studentID)->first();

        if (!$student) {
            return redirect()->route('grades.index')->withErrors(['not_found' => 'Student not found.']);
        }

        $grades = StudentGrade::where('studentID', $studentID)
            ->orderByRaw("CAST(yearLevel AS UNSIGNED) ASC")
            ->orderBy('subjectCode', 'ASC')
            ->get();

        return view('grades.edit', compact('student', 'grades'));
    }

    /**
     * 👨‍🎓 Show student details and grades
     */
    public function showStudents($id)
    {
        $student = Student::where('studentID', $id)->firstOrFail();
        $grades = StudentGrade::where('studentID', $id)
                    ->orderByRaw("CAST(yearLevel AS UNSIGNED) ASC")
                    ->orderBy('subjectCode', 'ASC')
                    ->get();

        $groupedByYear = $grades->groupBy('yearLevel');

        return view('grades.students', compact('student', 'grades', 'groupedByYear'));
    }

    /**
     * ✏️ Edit grades by year level
     */
    public function editByYear($studentID, $yearLevel)
    {
        $student = Student::where('studentID', $studentID)->firstOrFail();

        $grades = StudentGrade::where('studentID', $studentID)
                    ->where('yearLevel', $yearLevel)
                    ->orderBy('subjectCode', 'ASC')
                    ->get();

        return view('grades.edit', compact('student', 'grades', 'yearLevel'));
    }

    /**
     * 📤 Import grades from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new GradesImport, $request->file('file'));

        return redirect()->back()->with('success', '✅ Grades imported successfully!');
    }

    /**
     * 💾 Update student grades
     */
    public function update(Request $request, $studentID)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|string|max:10', // adjust to your Final_Rating type
        ]);

        $gradesInput = $request->input('grades', []);

        foreach ($gradesInput as $subjectCode => $finalRating) {
            $grade = StudentGrade::where('studentID', $studentID)
                ->where('subjectCode', $subjectCode)
                ->first();

            if (!$grade) {
                continue;
            }

            if ($grade->is_locked) {
                return redirect()->back()->withErrors([
                    'locked' => '❌ These grades are locked by your Program Head and cannot be edited.'
                ]);
            }

            $grade->Final_Rating = $finalRating;
            $grade->save();
        }

        return redirect()->route('grades.students', ['id' => $studentID])
                         ->with('success', '✅ Grades updated successfully!');
    }

    /**
     * 🔒 Lock all grades
     */
    public function lockAll()
    {
        StudentGrade::query()->update(['is_locked' => true]);
        return redirect()->back()->with('success', '✅ All grades have been locked by the Program Head.');
    }

    /**
     * 🔓 Unlock all grades
     */
    public function unlockAll()
    {
        StudentGrade::query()->update(['is_locked' => false]);
        return redirect()->back()->with('success', '✅ All grades have been unlocked.');
    }
}
