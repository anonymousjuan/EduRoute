@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Subject Details</h1>
    <p><strong>Code:</strong> {{ $subject->code }}</p>
    <p><strong>Name:</strong> {{ $subject->name }}</p>
    <p><strong>Units:</strong> {{ $subject->units }}</p>

    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
