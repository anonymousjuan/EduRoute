@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Assign Subject</h2>

    <form action="{{ route('subjects.assign.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="subject_id" class="form-label">Select Subject</label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="faculty_id" class="form-label">Assign to Faculty</label>
            <select name="faculty_id" id="faculty_id" class="form-select" required>
                <option value="">-- Select Faculty --</option>
                @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Assign Subject</button>
    </form>
</div>
@endsection
