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
        $yearLevel = $request->input('yearLevel'); // 🎓 Capture year level filter

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

        // 🔍 Search filter
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

        // 🎓 Year level filter
        if (!empty($yearLevel)) {
            $query->where('yearLevel', $yearLevel);
        }

        $grades = $query->paginate(25);

        return view('grades.index', [
            'grades' => $grades ?? collect()
        ]);
    }

    /**
     * 🎓 Show student transcript by ID (JSON or DB)
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
     * 👨‍🎓 Show student details and grades (for transcript view)
     */
    public function showStudents($id)
    {
        $student = Student::where('studentID', $id)->firstOrFail();
        $grades = StudentGrade::where('studentID', $id)
                    ->orderByRaw("CAST(yearLevel AS UNSIGNED) ASC")
                    ->orderBy('subjectCode', 'ASC')
                    ->get();

        // Group by year level
        $groupedByYear = $grades->groupBy('yearLevel');

        return view('grades.students', compact('student', 'grades', 'groupedByYear'));
    }

    /**
     * ✏️ Edit grades by specific year level
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
    $gradesInput = $request->input('grades', []);

    foreach ($gradesInput as $subjectCode => $value) {
        $grade = StudentGrade::where('studentID', $studentID)
            ->where('subjectCode', str_replace('_', ' ', $subjectCode))
            ->first();

        if ($grade && $grade->is_locked) {
            return redirect()->back()->withErrors([
                'locked' => '❌ These grades are locked by your Program Head and cannot be edited.'
            ]);
        }

        if ($grade) {
            $grade->Final_Rating = $value;
            $grade->save();
        }
    }

    return redirect()->route('grades.students', ['studentID' => $studentID])
                     ->with('success', '✅ Grades updated successfully!');
}

    public function lockAll()
{
    // Update all grades to be locked
    StudentGrade::query()->update(['is_locked' => true]);

    return redirect()->back()->with('success', '✅ All grades have been locked by the Program Head.');
}
/**
 * 🔓 Unlock all grades (Program Head action)
 */
public function unlockAll()
{
    StudentGrade::query()->update(['is_locked' => false]);

    return redirect()->back()->with('success', '✅ All grades have been unlocked.');
}

}
