<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class Dashboard extends Component
{
    public $searchFaculty = '';
    public $searchSubject = '';
    public $filteredFaculties = [];

    public function render()
    {
        // Example: fetch faculties and subjects
        $faculties = Faculty::with('subjects')->get();

        // Optional: apply search filters
        $this->filteredFaculties = $faculties->mapWithKeys(function($faculty) {
            return [$faculty->name => $faculty->subjects->toArray()];
        })->toArray();

        return view('livewire.dashboard', [
            'filteredFaculties' => $this->filteredFaculties
        ]);
    }
}
