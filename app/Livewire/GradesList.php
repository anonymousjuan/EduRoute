<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Grade;

class GradesList extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function render()
    {
        $grades = Grade::query()
            ->where('studentID', 'like', "%{$this->search}%")
            ->orWhere('firstName', 'like', "%{$this->search}%")
            ->orWhere('lastName', 'like', "%{$this->search}%")
            ->orWhere('courseTitle', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.grades-list', compact('grades'));
    }
}
