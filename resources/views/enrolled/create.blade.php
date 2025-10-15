@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Enroll New Student</h1>

    <form method="POST" action="{{ route('enrolled.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="student_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Year Level</label>
            <input type="text" name="year_level" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Enroll</button>
    </form>
</div>
@endsection
