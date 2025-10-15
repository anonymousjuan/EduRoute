<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\StudentGrade;

class FacultyFilter extends Component
{
    public $searchFaculty = '';
    public $searchSubject = '';
    public $expandedFaculty = null; // Track expanded faculty

    // Toggle expand/collapse for each faculty
    public function toggleFaculty($facultyName)
    {
        $this->expandedFaculty = ($this->expandedFaculty === $facultyName) ? null : $facultyName;
    }

    public function render()
    {
        // Filter faculties by search term
        $faculties = Faculty::when($this->searchFaculty, function ($query) {
            $query->where('name', 'like', '%' . $this->searchFaculty . '%');
        })
        ->orderBy('name', 'asc')
        ->get();

        $data = [];

        foreach ($faculties as $faculty) {
            // Get subjects handled by this faculty
            $subjects = StudentGrade::where('Faculty', $faculty->name)
                ->select('subjectCode', 'subjectTitle', 'courseTitle', 'yearLevel', 'units')
                ->groupBy('subjectCode', 'subjectTitle', 'courseTitle', 'yearLevel', 'units')
                ->get();

            // Get all students handled by this faculty
            $students = Student::whereIn('studentID', function ($query) use ($faculty) {
                $query->select('studentID')
                    ->from('student_grades')
                    ->where('Faculty', $faculty->name);
            })->get();

            $data[$faculty->name] = [
                'faculty' => $faculty,
                'subjects' => $subjects,
                'students' => $students,
            ];
        }

        return view('livewire.faculty-filter', [
            'data' => $data,
        ]);
    }
}
