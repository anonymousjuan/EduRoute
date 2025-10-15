@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">📘 Curriculum {{ $year ?? '' }}</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Year Level</th>
                <th>Subject Code</th>
                <th>Description</th>
                <th>Units</th>
                <th>Year of Implementation</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($curriculums as $curr)
                <tr>
                    <td>{{ $curr->year_level }}</td>
                    <td>{{ $curr->subject_code }}</td>
                    <td>{{ $curr->description }}</td>
                    <td>{{ $curr->units }}</td>
                    <td>{{ $curr->year_of_implementation }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No curriculum found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
