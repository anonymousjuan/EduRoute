<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-dark black bg-maroon-700 px-4 py-2 rounded shadow">
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

        @foreach($students as $schoolYear => $studentsBySY)
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-3 bg-gray-200 px-3 py-1 rounded">
                    {{ $schoolYear }}
                </h3>

                <div class="table-card rounded-lg shadow-lg overflow-hidden border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-maroon-700 text-dark black">
                            <tr>
                                <th class="px-4 py-3 text-left">Student ID</th>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-center">Final Grade</th>
                                <th class="px-4 py-3 text-center">Retake Grade</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($studentsBySY as $student)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-2 font-medium text-gray-800 align-middle">{{ $student->studentID }}</td>
                                    <td class="px-4 py-2 text-gray-700 align-middle">{{ $student->lastName }}, {{ $student->firstName }}</td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <form id="grade-form-{{ $student->id }}" action="{{ route('faculty.subject.update', $subject->subjectCode) }}" method="POST" class="flex justify-center items-center h-full">
                                            @csrf
                                            <input type="text" name="grades[{{ $student->id }}]" value="{{ $student->Final_Rating }}" class="grade-input">
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        {{ $student->Retake_Grade ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <button type="submit" form="grade-form-{{ $student->id }}" class="save-button">
                                            Save
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>

    <style>
        /* Table card */
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(128,0,0,0.2);
            padding: 1rem;
        }

        /* Table header */
        thead th {
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        /* Table rows */
        tbody tr {
            transition: all 0.2s ease;
        }
        tbody tr:hover {
            background-color: #f9f5f5;
        }

        /* Align inputs vertically */
        .grade-input {
            width: 60px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 6px 8px;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .grade-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.3);
        }

        /* Save button */
        .save-button {
            background-color: #800000;
            color: #fff;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .save-button:hover {
            background-color: #a83232;
            transform: translateY(-2px);
        }

        /* Vertical alignment */
        td, th {
            vertical-align: middle;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-card {
                overflow-x: auto;
            }
            table {
                min-width: 600px;
            }
        }
    </style>
</x-app-layout>
