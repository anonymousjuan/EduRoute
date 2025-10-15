<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📘 Transcript - {{ $student->lastName ?? $student->lastname ?? '' }}, 
            {{ $student->firstName ?? $student->firstname ?? '' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- ✅ Student Info --}}
                <div class="mb-6">
                    <p><strong>Student ID:</strong> {{ $student->studentID ?? $student->studentId ?? '' }}</p>
                    <p><strong>Name:</strong> 
                        {{ $student->lastName ?? $student->lastname ?? '' }},
                        {{ $student->firstName ?? $student->firstname ?? '' }}
                        {{ $student->middleName ?? $student->middlename ?? '' }}
                        {{ $student->suffix ?? '' }}
                    </p>
                    <p><strong>Course:</strong> {{ $student->courseTitle ?? $student->course ?? '' }}</p>
                    <p><strong>Year Level:</strong> {{ $student->yearLevel ?? $student->yearlevel ?? '' }}</p>
                    <p><strong>School Year:</strong> {{ $student->schoolYearTitle ?? $student->schoolYear ?? '' }}</p>
                </div>

                {{-- ✅ Grades Table --}}
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Year Level</th>
                            <th class="border border-gray-300 px-4 py-2">Semester</th>
                            <th class="border border-gray-300 px-4 py-2">Subject Code</th>
                            <th class="border border-gray-300 px-4 py-2">Descriptive Title</th>
                            <th class="border border-gray-300 px-4 py-2">Units</th>
                            <th class="border border-gray-300 px-4 py-2">Faculty</th>
                            <th class="border border-gray-300 px-4 py-2">Final Grade</th>
                            <th class="border border-gray-300 px-4 py-2">Retake Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            @php
                                // Normalize keys in case it's from JSON
                                $gradeObj = (object) $grade;
                            @endphp
                            <tr>
                                <td class="border px-4 py-2">{{ $gradeObj->yearLevel ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->schoolYearTitle ?? $gradeObj->semester ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->subjectCode ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->subjectTitle ?? $gradeObj->descriptiveTitle ?? $gradeObj->descriptive_title ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->units ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->Faculty ?? $gradeObj->faculty ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->FinalRating ?? $gradeObj->final_Rating ?? '' }}</td>
                                <td class="border px-4 py-2">{{ $gradeObj->RetakeGrade ?? $gradeObj->retakeGrade ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
