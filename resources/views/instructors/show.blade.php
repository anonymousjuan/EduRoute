@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Instructor Details</h1>

    <p><strong>Name:</strong> {{ $instructor->name }}</p>
    <p><strong>Email:</strong> {{ $instructor->email }}</p>

    <a href="{{ route('instructors.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
