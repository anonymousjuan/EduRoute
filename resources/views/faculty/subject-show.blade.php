<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-dark bg-maroon-700 px-4 py-2 rounded shadow">
            📘 {{ $subject->subjectTitle }} ({{ $subject->subjectCode }})
        </h2>
        <a href="{{ url()->previous() }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded shadow hover:bg-gray-300 transition">
            ← Back
        </a>
    </div>
</x-slot>

<div class="py-6 max-w-6xl mx-auto px-4">

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
    {{ session('success') }}
</div>
@endif

<div class="table-card rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <table class="w-full border-collapse">
        <thead class="bg-maroon-700 text-white">
            <tr>
                <th class="px-4 py-3 text-left">Student ID</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-center">Final Grade</th>
                <th class="px-4 py-3 text-center">Retake Grade</th>
                <th class="px-4 py-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($students as $student)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-2 font-medium text-gray-800">{{ $student->studentID }}</td>
                <td class="px-4 py-2 text-gray-700">
                    {{ $student->lastName ?? '-' }}, {{ $student->firstName ?? '-' }}
                </td>
                <td class="px-4 py-2 text-center">
                    <form id="grade-form-{{ $student->id }}" action="{{ route('faculty.subject.update', $subject->subjectCode) }}" method="POST">
                        @csrf
                        <input type="text" name="grades[{{ $student->id }}]" value="{{ $student->Final_Rating }}" class="grade-input">
                    </form>
                </td>
                <td class="px-4 py-2 text-center">{{ $student->Retake_Grade ?? '-' }}</td>
                <td class="px-4 py-2 text-center">
                    <button type="submit" form="grade-form-{{ $student->id }}" class="save-button">Save</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

<style>
.table-card { background:#fff; border-radius:12px; padding:1rem; box-shadow:0 6px 20px rgba(128,0,0,0.2); }
.grade-input { width:60px; text-align:center; padding:6px 8px; border-radius:8px; border:1px solid #ccc; }
.save-button { background:#800000; color:#fff; padding:6px 12px; border-radius:8px; cursor:pointer; }
.save-button:hover { background:#a83232; transform:translateY(-2px); }
td, th { vertical-align: middle; }
</style>
</x-app-layout>
