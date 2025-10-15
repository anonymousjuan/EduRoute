<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;
use App\Models\StudentGrade;

class FacultySubjects extends Component
{
    public $searchFaculty = '';
    public $searchSubject = '';
    public $expandedFaculty = null;

    public function toggleFaculty($facultyName)
    {
        $this->expandedFaculty = $this->expandedFaculty === $facultyName ? null : $facultyName;
    }

    // Redirect to subject dashboard
    public function goToSubject($subjectCode)
    {
        return redirect()->route('subject.dashboard', ['subjectCode' => $subjectCode]);
    }

    public function getFilteredFacultiesProperty()
    {
        $query = StudentGrade::query();

        if ($this->searchFaculty) {
            $query->where('Faculty', 'like', "%{$this->searchFaculty}%");
        }

        if ($this->searchSubject) {
            $search = $this->searchSubject;
            $query->where(function ($q) use ($search) {
                $q->where('subjectTitle', 'like', "%{$search}%")
                  ->orWhere('subjectCode', 'like', "%{$search}%");
            });
        }

        $grades = $query->orderBy('Faculty')->get();

        // Group all data by faculty name
        $faculties = $grades->groupBy('Faculty')->map(function ($items, $facultyName) {
            $uniqueSubjects = $items->unique('subjectCode')->values();

            return [
                'subjects' => $uniqueSubjects->map(function ($subject) {
                    return [
                        'subjectCode'  => $subject->subjectCode ?? 'N/A',
                        'subjectTitle' => $subject->subjectTitle ?? 'Untitled Subject',
                        'units'        => $subject->units ?? 0,
                    ];
                })->values(),

                'students' => $items->unique('studentID')->map(function ($student) {
                    return [
                        'studentID'   => $student->studentID,
                        'lastName'    => $student->lastName,
                        'firstName'   => $student->firstName,
                        'middleName'  => $student->middleName,
                        'suffix'      => $student->suffix,
                        'courseTitle' => $student->courseTitle,
                        'yearLevel'   => $student->yearLevel,
                    ];
                })->values()
            ];
        });

        // Include faculties with no records yet
        $allFaculties = Faculty::pluck('name')->toArray();
        foreach ($allFaculties as $facultyName) {
            if (!isset($faculties[$facultyName])) {
                $faculties[$facultyName] = ['subjects' => collect(), 'students' => collect()];
            }
        }

        // Optional search filter
        if ($this->searchFaculty) {
            $faculties = collect($faculties)->filter(fn($_, $name) => stripos($name, $this->searchFaculty) !== false);
        }

        return $faculties->toArray();
    }

    public function render()
    {
        return view('livewire.faculty-filter', [
            'filteredFaculties' => $this->filteredFaculties,
        ])->layout('layouts.app');
    }
}
