<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a list of instructors.
     */
    public function index()
    {
        $instructors = Account::where('role', 'instructor')->get();
        return view('instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new instructor.
     */
    public function create()
    {
        return view('instructors.create');
    }

    /**
     * Store a newly created instructor in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'instructor'; // explicitly assign role

        Account::create($validated);

        return redirect()->route('instructors.index')
                         ->with('success', 'Instructor created successfully.');
    }

    /**
     * Display the specified instructor.
     */
    public function show(Account $instructor)
    {
        return view('instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified instructor.
     */
    public function edit(Account $instructor)
    {
        return view('instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified instructor in the database.
     */
    public function update(Request $request, Account $instructor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,' . $instructor->id,
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $instructor->update($validated);

        return redirect()->route('instructors.index')
                         ->with('success', 'Instructor updated successfully.');
    }

    /**
     * Remove the specified instructor from the database.
     */
    public function destroy(Account $instructor)
    {
        $instructor->delete();

        return redirect()->route('instructors.index')
                         ->with('success', 'Instructor deleted successfully.');
    }
}
