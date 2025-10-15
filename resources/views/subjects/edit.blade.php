@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Subject</h1>

    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ $subject->code }}" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
        </div>
        <div class="mb-3">
            <label>Units</label>
            <input type="number" name="units" class="form-control" value="{{ $subject->units }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
