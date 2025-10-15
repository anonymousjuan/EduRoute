<?php

namespace App\Http\Controllers;

use App\Models\Enrolled;
use Illuminate\Http\Request;

class EnrolledController extends Controller
{
    public function index()
    {
        $enrolled = Enrolled::all();
        return view('enrolled.index', compact('enrolled'));
    }

    public function create()
    {
        return view('enrolled.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_id'   => 'required|string|unique:enrolled',
            'course'       => 'required|string',
            'year_level'   => 'required|string',
        ]);

        Enrolled::create($request->all());

        return redirect()->route('enrolled.index')->with('success', 'Student enrolled successfully.');
    }

    public function show(Enrolled $enrolled)
    {
        return view('enrolled.show', compact('enrolled'));
    }

    public function edit(Enrolled $enrolled)
    {
        return view('enrolled.edit', compact('enrolled'));
    }

    public function update(Request $request, Enrolled $enrolled)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_id'   => 'required|string|unique:enrolled,student_id,' . $enrolled->id,
            'course'       => 'required|string',
            'year_level'   => 'required|string',
        ]);

        $enrolled->update($request->all());

        return redirect()->route('enrolled.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Enrolled $enrolled)
    {
        $enrolled->delete();
        return redirect()->route('enrolled.index')->with('success', 'Student removed successfully.');
    }
}
