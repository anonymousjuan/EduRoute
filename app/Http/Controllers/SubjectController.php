<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Faculty;

class SubjectController extends Controller
{
    // Display all subjects with their assigned faculties
    public function index()
    {
        $subjects = Subject::with('faculties')->get();
        $faculties = Faculty::all();
        return view('subjects.index', compact('subjects', 'faculties'));
    }

    // Show form to create a new subject
    public function create()
    {
        $faculties = Faculty::all();
        return view('subjects.create', compact('faculties'));
    }

    // Store a new subject
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'units' => 'required|integer|min:1',
            'faculty_id' => 'nullable|array', // optional array of faculty IDs
        ]);

        $subject = Subject::create([
            'code' => $request->code,
            'name' => $request->name,
            'title' => $request->title,
            'units' => $request->units,
        ]);

        // Assign selected faculties
        if ($request->faculty_id) {
            $subject->faculties()->sync($request->faculty_id);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully!');
    }

    // Show a single subject
    public function show(Subject $subject)
    {
        return view('subjects.show', compact('subject'));
    }

    // Show edit form
    public function edit(Subject $subject)
    {
        $faculties = Faculty::all();
        return view('subjects.edit', compact('subject', 'faculties'));
    }

    // Update a subject
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'units' => 'required|integer|min:1',
            'faculty_id' => 'nullable|array',
        ]);

        $subject->update([
            'code' => $request->code,
            'name' => $request->name,
            'title' => $request->title,
            'units' => $request->units,
        ]);

        // Update faculty assignments
        if ($request->faculty_id) {
            $subject->faculties()->sync($request->faculty_id);
        } else {
            $subject->faculties()->detach(); // remove all if none selected
        }

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully!');
    }

    // Delete a subject
    public function destroy(Subject $subject)
    {
        $subject->faculties()->detach(); // remove pivot relationships first
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully!');
    }

    // Optional: show assignment form separately
    public function assignForm()
    {
        $subjects = Subject::all();
        $faculties = Faculty::all();
        return view('subjects.assign', compact('subjects', 'faculties'));
    }

    // Handle manual assignment of subject to faculty
    public function assignStore(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $subject = Subject::find($request->subject_id);
        $faculty = Faculty::find($request->faculty_id);

        $subject->faculties()->syncWithoutDetaching([$faculty->id]);

        return redirect()->route('subjects.index')->with('success', 'Subject assigned successfully!');
    }
}
