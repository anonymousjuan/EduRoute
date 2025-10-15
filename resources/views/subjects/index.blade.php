@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Subjects</h1>

    <a href="{{ route('subjects.create') }}" class="btn btn-primary">Add Subject</a>
    <a href="{{ route('subjects.assign') }}" class="btn btn-warning">Assign Subject</a>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Units</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->id }}</td>
                <td>{{ $subject->code }}</td>
                <td>{{ $subject->name }}</td>
                <td>{{ $subject->units }}</td>
                <td>
                    <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this subject?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
