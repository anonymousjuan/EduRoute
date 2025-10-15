@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Add Subject</h2>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('subjects.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Code:</label>
            <input type="text" name="code" value="{{ old('code') }}" class="w-full border p-2 rounded" required>
            @error('code')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 rounded" required>
            @error('name')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Title:</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border p-2 rounded">
            @error('title')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Units:</label>
            <input type="number" name="units" value="{{ old('units') }}" class="w-full border p-2 rounded" required>
            @error('units')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Assign to Faculty:</label>
            @foreach($faculties as $faculty)
                <label class="inline-flex items-center mt-2">
                    <input type="checkbox" name="faculty_id[]" value="{{ $faculty->id }}" 
                        {{ (is_array(old('faculty_id')) && in_array($faculty->id, old('faculty_id'))) ? 'checked' : '' }} 
                        class="form-checkbox">
                    <span class="ml-2">{{ $faculty->name }}</span>
                </label>
            @endforeach
            @error('faculty_id')
                <span class="text-red-600 text-sm block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Subject</button>
    </form>
</div>
@endsection
