<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Grade;

class GradesManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 25;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Fetch grades with search
        $gradesQuery = Grade::select(
                'studentID',
                'lastName',
                'firstName',
                'middleName',
                'suffix',
                'gender',
                'courseTitle',
                'yearLevel',
                'schoolYearTitle'
            )
            ->groupBy(
                'studentID',
                'lastName',
                'firstName',
                'middleName',
                'suffix',
                'gender',
                'courseTitle',
                'yearLevel',
                'schoolYearTitle'
            )
            ->orderByRaw("CAST(yearLevel AS UNSIGNED) ASC")
            ->orderBy('schoolYearTitle', 'ASC')
            ->orderBy('lastName', 'ASC');

        if ($this->search) {
            $gradesQuery->where(function($q) {
                $q->where('studentID', 'like', '%' . $this->search . '%')
                  ->orWhere('lastName', 'like', '%' . $this->search . '%')
                  ->orWhere('firstName', 'like', '%' . $this->search . '%');
            });
        }

        $grades = $gradesQuery->paginate($this->perPage);

        // Group by year level for tabs
        $studentsByYear = $grades->groupBy('yearLevel');

        return view('livewire.grades-management', compact('grades', 'studentsByYear'));
    }
}
