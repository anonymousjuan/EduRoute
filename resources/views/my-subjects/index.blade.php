@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Subjects</h2>

    {{-- Add Subject Code Button --}}
    <a href="{{ route('subjects.create') }}"
       style="background-color: #007BFF; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 1rem; margin-bottom: 15px; display: inline-block;">
        ➕ Add Subject Code
    </a>

    @if($subjects->isEmpty())
        <p>You have no assigned subjects.</p>

        {{-- Show Add Student button even if there are no subjects --}}
        <a href="{{ route('students.create', 'dummy') }}"
           style="background-color: #4CAF50; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">
            ➕ Add Student
        </a>
    @else
        <ul>
            @foreach($subjects as $subject)
                <li>
                    {{ $subject->subjectCode }} - {{ $subject->subjectTitle }}

                    <a href="{{ route('my-subjects.students.create', $subject->subjectCode) }}"
                       style="margin-left: 10px; background-color: #4CAF50; color: black; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">
                        ➕ Add Student
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
