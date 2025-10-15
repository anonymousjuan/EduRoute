@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subject: {{ $subject->subjectCode }}</h2>

    @if(session('success'))
        <div style="color:green">{{ session('success') }}</div>
    @endif
    <form action="{{ route('my-subjects.update', $subject->subjectCode) }}" method="POST" style="margin-top: 1rem;">
        @csrf
        @method('PUT')
        <table border="1" cellpadding="5" cellspacing="0" style="width:100%; max-width: 800px;">
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Student Name</th>
                    <th>Final Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $schoolYear => $group)
                    @foreach($group as $student)
                        <tr>
                            <td>{{ $schoolYear }}</td>
                            <td>{{ $student->lastName }}, {{ $student->firstName }}</td>
                            <td>
                                <input type="number" step="0.01" name="grades[{{ $student->id }}]" value="{{ $student->Final_Rating }}">
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <br>
        <button type="submit">Update Grades</button>
    </form>

    <br>
    <a href="{{ route('my-subjects.index') }}">← Back to My Subjects</a>
</div>
@endsection
