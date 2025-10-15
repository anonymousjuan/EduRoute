@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Instructor</h1>

    <form action="{{ route('instructors.update', $instructor->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $instructor->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $instructor->email }}" required>
        </div>

        <div class="mb-3">
            <label>Password (Leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('instructors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
