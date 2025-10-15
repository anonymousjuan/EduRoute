<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use PhpOffice\PhpWord\IOFactory;

class CurriculumController extends Controller
{
    // ==========================
    // Display grouped curriculums by year (Admin)
    // ==========================
    public function index(Request $request)
    {
        $year = $request->get('year') ?? Curriculum::max('year_of_implementation');

        $courses = Curriculum::where('year_of_implementation', $year)
            ->orderBy('year_level')
            ->orderByRaw("FIELD(semester, '1st','2nd','1','2')")
            ->get();

        $groupedCourses = [];

        foreach ($courses as $course) {
            $yearLevel = $course->year_level;
            $sem = in_array($course->semester, ['1st', 1]) ? 1 : 2;
            $groupedCourses[$yearLevel][$sem][] = $course;
        }

        $availableYears = Curriculum::select('year_of_implementation')
            ->distinct()
            ->orderBy('year_of_implementation', 'desc')
            ->pluck('year_of_implementation');

        return view('curriculum.index', compact('groupedCourses', 'year', 'availableYears'));
    }

    // ==========================
    // Dean's readonly view
    // ==========================
    public function view(Request $request, $year = null)
    {
        $year = $year ?? Curriculum::max('year_of_implementation');

        $courses = Curriculum::where('year_of_implementation', $year)
            ->orderBy('year_level')
            ->orderByRaw("FIELD(semester, '1st','2nd','1','2')")
            ->get();

        $groupedCourses = [];

        foreach ($courses as $course) {
            $yearLevel = $course->year_level;
            $sem = in_array($course->semester, ['1st', 1]) ? 1 : 2;
            $groupedCourses[$yearLevel][$sem][] = $course;
        }

        $availableYears = Curriculum::select('year_of_implementation')
            ->distinct()
            ->orderBy('year_of_implementation', 'desc')
            ->pluck('year_of_implementation');

        return view('curriculum.view', compact('groupedCourses', 'year', 'availableYears'));
    }

    // ==========================
    // Show create form
    // ==========================
    public function create()
    {
        return view('curriculum.create');
    }

    // ==========================
    // Store curriculum
    // ==========================
    public function store(Request $request)
    {
        $request->validate([
            'year_of_implementation' => 'required|string',
            'courses' => 'required|array'
        ]);

        $insertData = [];

        foreach ($request->courses as $yearLevel => $semesters) {
            foreach ($semesters as $semester => $courses) {
                foreach ($courses as $course) {
                    if (!empty($course['course_no']) && !empty($course['descriptive_title'])) {
                        $insertData[] = [
                            'year_of_implementation' => $request->year_of_implementation,
                            'year_level' => $yearLevel,
                            'semester' => $semester,
                            'course_no' => $course['course_no'],
                            'descriptive_title' => $course['descriptive_title'],
                            'units' => $course['units'] ?? 0,
                            'lec' => $course['lec'] ?? 0,
                            'lab' => $course['lab'] ?? 0,
                            'prerequisite' => $course['prerequisite'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        if (!empty($insertData)) {
            Curriculum::insert($insertData);
        }

        return redirect()->route('curriculum.index')->with('success', 'Curriculum saved successfully!');
    }

    // ==========================
    // Edit a single course
    // ==========================
    public function edit($id, $semester)
    {
        $curriculum = Curriculum::findOrFail($id);
        return view('curriculum.edit', compact('curriculum', 'semester'));
    }

    // ==========================
    // Update a single course
    // ==========================
    public function update(Request $request, $id)
    {
        $curriculum = Curriculum::findOrFail($id);

        $request->validate([
            'course_no' => 'required|string',
            'descriptive_title' => 'required|string',
            'units' => 'nullable|integer',
            'lec' => 'nullable|integer',
            'lab' => 'nullable|integer',
            'prerequisite' => 'nullable|string',
        ]);

        $curriculum->update([
            'course_no' => $request->course_no,
            'descriptive_title' => $request->descriptive_title,
            'units' => $request->units,
            'lec' => $request->lec,
            'lab' => $request->lab,
            'prerequisite' => $request->prerequisite,
        ]);

        return redirect()->route('curriculum.index')->with('success', 'Course updated successfully!');
    }

    // ==========================
    // DOCX Import view
    // ==========================
    public function importView()
    {
        return view('curriculum.import');
    }

    // ==========================
    // Helper: get text from PhpWord cell
    // ==========================
    private function getCellText($cell)
    {
        $text = '';
        foreach ($cell->getElements() as $element) {
            if (method_exists($element, 'getText')) {
                $text .= $element->getText() . ' ';
            }
        }
        return trim($text);
    }

    // ==========================
    // Helper: extract number (for Year/Semester)
    // ==========================
    private function extractNumber($text)
    {
        preg_match('/\d+/', $text, $matches);
        return $matches ? (int) $matches[0] : null;
    }

    // ==========================
    // Import DOCX (parse and store curriculum)
    // ==========================
    public function importDocx(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:doc,docx',
            'year_of_implementation' => 'required|string'
        ]);

        $file = $request->file('file');
        $yearOfImplementation = $request->year_of_implementation;

        $phpWord = IOFactory::load($file->getPathName());

        $currentYear = null;
        $currentSemester = null;

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {

                // Detect year level and semester
                if (method_exists($element, 'getText')) {
                    $text = strtoupper(trim($element->getText()));
                    if (str_contains($text, 'YEAR')) {
                        $currentYear = $this->extractNumber($text);
                    }
                    if (str_contains($text, 'SEMESTER')) {
                        $currentSemester = $this->extractNumber($text);
                    }
                }

                // Process tables
                if (method_exists($element, 'getRows')) {
                    foreach ($element->getRows() as $rowIndex => $row) {
                        if ($rowIndex === 0) continue;

                        $cells = $row->getCells();
                        if (count($cells) >= 6) {
                            $courseNo = $this->getCellText($cells[0]);
                            $desc = $this->getCellText($cells[1]);

                            // Skip totals or blank rows
                            if (stripos($courseNo, 'TOTAL') !== false || stripos($desc, 'TOTAL') !== false) continue;

                            Curriculum::create([
                                'course_no' => $courseNo ?: 'N/A',
                                'descriptive_title' => $desc ?: 'Untitled',
                                'units' => (int) $this->getCellText($cells[2]),
                                'lec' => (int) $this->getCellText($cells[3]),
                                'lab' => (int) $this->getCellText($cells[4]),
                                'prerequisite' => $this->getCellText($cells[5]) ?: null,
                                'year_level' => $currentYear,
                                'semester' => $currentSemester,
                                'year_of_implementation' => $yearOfImplementation,
                            ]);
                        }
                    }
                }
            }
        }

        return back()->with('success', "Curriculum for {$yearOfImplementation} imported successfully!");
    }
    public function deleteYear($year)
{
    // Optional: Add role check
    if (auth()->user()->role === 'dean') {
        abort(403, 'Unauthorized action.');
    }

    // Delete all curriculum records for the specified year
    \App\Models\Curriculum::where('year_of_implementation', $year)->delete();

    return redirect()->route('curriculum.index')->with('success', "Curriculum for year $year deleted successfully.");
}
}
