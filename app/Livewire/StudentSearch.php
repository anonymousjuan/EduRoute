<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = '1st Year';
    public $schoolYear;

    protected $updatesQueryString = ['search', 'tab', 'schoolYear'];

    public function mount()
    {
        // Default to latest/current school year
        $this->schoolYear = Student::max('schoolYearTitle');
    }

    public function updatingSearch($value)
    {
        $this->resetPage();

        if (!$value) {
            return;
        }

        $currentYear = (int) date('y');
        $prefixMap = [
            '1st Year' => 'E' . $currentYear,
            '2nd Year' => 'E' . ($currentYear - 1),
            '3rd Year' => 'E' . ($currentYear - 2),
            '4th Year' => 'E' . ($currentYear - 3),
        ];

        // Search within the selected school year
        $student = Student::where('schoolYearTitle', $this->schoolYear)
            ->where(function ($q) use ($value) {
                $q->where('lastName', 'like', "%{$value}%")
                  ->orWhere('firstName', 'like', "%{$value}%")
                  ->orWhere('studentID', 'like', "%{$value}%");
            })
            ->orderBy('id', 'desc')
            ->first();

        if ($student) {
            if ($student->overrideYearLevel) {
                $this->tab = $student->overrideYearLevel;
            } else {
                foreach ($prefixMap as $year => $prefix) {
                    if (str_starts_with($student->studentID, $prefix)) {
                        $this->tab = $year;
                        break;
                    }
                }
            }
        } else {
            $this->tab = '1st Year';
        }
    }

    public function updatingSchoolYear()
    {
        $this->resetPage();
        $this->tab = '1st Year';
        $this->search = '';
    }

    public function setTab($year)
    {
        $this->tab = $year;
        $this->resetPage();
        $this->search = '';
    }

    /** 🎓 Redirect to Transcript page */
    public function viewTranscript($studentID)
    {
        // ✅ This will look for resources/views/transcript.blade.php
        return redirect()->route('transcript.view', ['studentID' => $studentID]);
    }

    /** 🔧 Guess Year Level by Student ID */
    private function guessYearLevel($studentID)
    {
        $currentYear = (int) date('y');
        $prefixMap = [
            $currentYear     => '1st Year',
            $currentYear - 1 => '2nd Year',
            $currentYear - 2 => '3rd Year',
            $currentYear - 3 => '4th Year',
        ];

        foreach ($prefixMap as $yearPrefix => $label) {
            if (str_starts_with($studentID, 'E' . $yearPrefix)) {
                return $label;
            }
        }

        return 'Graduate';
    }

    /** ⚙️ Generate Next Semester Subjects */
    public function generateSubjects($studentID)
    {
        $student = Student::where('studentID', $studentID)->firstOrFail();

        // Detect current semester
        $currentSem = '1st';
        if (stripos($student->semester ?? '', '2nd') !== false) {
            $currentSem = '2nd';
        }

        // Determine next semester
        $nextSem = $currentSem === '1st' ? '2nd' : '1st';

        // Determine next year level
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $currentIndex = array_search($student->yearLevel, $yearLevels);
        $nextYearLevel = $student->yearLevel;

        if ($currentSem === '2nd' && $currentIndex !== false && $currentIndex < count($yearLevels) - 1) {
            $nextYearLevel = $yearLevels[$currentIndex + 1];
        }

        // Curriculum mapping
        $curriculumYear = in_array($nextYearLevel, ['1st Year', '2nd Year'])
            ? '2024-2025'
            : '2022-2023';

        // Get curriculum subjects for next sem
        $curriculumSubjects = DB::table('curriculums')
            ->where('year_of_implementation', $curriculumYear)
            ->where('year_level', $nextYearLevel)
            ->where('semester', $nextSem)
            ->get();

        if ($curriculumSubjects->isEmpty()) {
            session()->flash('error', "❌ No subjects found for {$nextYearLevel} - {$nextSem} Semester in {$curriculumYear}");
            return;
        }

        // Get taken subjects
        $takenSubjects = DB::table('students')->where('studentID', $studentID)->get();
        $eligibleSubjects = [];

        foreach ($curriculumSubjects as $subject) {
            $taken = $takenSubjects->firstWhere('subjectCode', $subject->courseNo);

            // Skip passed subjects
            if ($taken && is_numeric($taken->Final_Rating) && $taken->Final_Rating >= 75) {
                continue;
            }

            // Check prerequisites
            $canTake = true;
            if (!empty($subject->prerequisite)) {
                $prereqs = array_map('trim', explode(',', $subject->prerequisite));
                foreach ($prereqs as $req) {
                    $reqGrade = $takenSubjects->firstWhere('subjectCode', $req);
                    if (!$reqGrade || (!is_numeric($reqGrade->Final_Rating) || $reqGrade->Final_Rating < 75)) {
                        $canTake = false;
                        break;
                    }
                }
            }

            if ($canTake) {
                $eligibleSubjects[] = [
                    'studentID'       => $studentID,
                    'subjectCode'     => $subject->courseNo,
                    'subjectTitle'    => $subject->descriptive_title,
                    'units'           => $subject->units,
                    'yearLevel'       => $nextYearLevel,
                    'semester'        => $nextSem,
                    'schoolYearTitle' => 'AY 2025-2026',
                    'Final_Rating'    => null,
                    'Retake_Grade'    => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }

        // Insert or update subjects
        foreach ($eligibleSubjects as $subj) {
            DB::table('students')->updateOrInsert(
                ['studentID' => $subj['studentID'], 'subjectCode' => $subj['subjectCode']],
                $subj
            );
        }

        // Update student level and semester
        $student->update([
            'yearLevel' => $nextYearLevel,
            'semester'  => $nextSem,
        ]);

        session()->flash('success', "✅ Subjects for {$nextYearLevel} ({$nextSem} Semester) generated successfully!");
    }

    public function render()
    {
        $latestIdsAll = Student::selectRaw('MAX(id) as id')->groupBy('studentID');
        $latestIdsThisYear = Student::selectRaw('MAX(id) as id')
            ->where('schoolYearTitle', $this->schoolYear)
            ->groupBy('studentID');

        $yearLabels = ['1st Year', '2nd Year', '3rd Year', '4th Year']; //'Graduate'];
        $currentYear = (int) date('y');
        $prefixMap = [
            '1st Year' => 'E' . $currentYear,
            '2nd Year' => 'E' . ($currentYear - 1),
            '3rd Year' => 'E' . ($currentYear - 2),
            '4th Year' => 'E' . ($currentYear - 3),
        ];

        $studentsByYear = [];

        foreach ($yearLabels as $year) {
            if ($year === 'Graduate') {
                $q = Student::whereIn('id', $latestIdsAll)
                    ->whereNotIn('studentID', function ($sub) {
                        $sub->select('studentID')
                            ->from('students')
                            ->where('schoolYearTitle', $this->schoolYear);
                    })
                    ->orderBy('lastName');
            } else {
                $prefix = $prefixMap[$year] ?? null;

                $q = Student::whereIn('id', $latestIdsThisYear)
                    ->where('schoolYearTitle', $this->schoolYear)
                    ->where(function ($sub) use ($year, $prefix) {
                        $sub->where(function ($inner) use ($year, $prefix) {
                            $inner->where('overrideYearLevel', $year)
                                  ->orWhere('yearLevel', intval($year[0]))
                                  ->orWhere(function ($innermost) use ($prefix) {
                                      if ($prefix) {
                                          $innermost->where('studentID', 'like', $prefix . '%');
                                      }
                                  });
                        });
                    });

                if ($this->search) {
                    $q->where(function ($s) {
                        $s->where('lastName', 'like', '%' . $this->search . '%')
                          ->orWhere('firstName', 'like', '%' . $this->search . '%')
                          ->orWhere('studentID', 'like', '%' . $this->search . '%');
                    });
                }

                $q = $q->orderBy('lastName');
            }

            $studentsByYear[$year] = $q->paginate(50, ['*'], 'page_' . $year);
        }

        $schoolYears = Student::select('schoolYearTitle')
            ->distinct()
            ->orderBy('schoolYearTitle', 'desc')
            ->pluck('schoolYearTitle');

        return view('livewire.student-search', [
            'studentsByYear' => $studentsByYear,
            'searchMode' => $this->search !== '',
            'schoolYears' => $schoolYears,
            'tab' => $this->tab,
        ]);
    }
}
