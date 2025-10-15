@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Student to {{ $subject->subjectCode }} - {{ $subject->subjectTitle }}</h2>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <form action="{{ route('student_grades.store') }}" method="POST">
        @csrf

        {{-- Hidden subject details --}}
        <input type="hidden" name="subjectCode" value="{{ $subject->subjectCode }}">
        <input type="hidden" name="subjectTitle" value="{{ $subject->subjectTitle }}">

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="studentID">Student ID:</label>
            <input type="text" name="studentID" id="studentID" class="form-control" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" id="firstName" class="form-control" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" id="lastName" class="form-control" required>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="schoolYearTitle">School Year:</label>
            <input type="text" name="schoolYearTitle" id="schoolYearTitle" class="form-control" placeholder="e.g., 1st Semester AY 2025-2026" required>
        </div>

        <button type="submit" class="btn btn-success">➕ Add Student</button>
        <a href="{{ route('my-subjects.index') }}" class="btn btn-secondary">← Back</a>
    </form>
</div>
@endsection
