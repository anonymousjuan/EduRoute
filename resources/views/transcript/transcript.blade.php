<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl md:text-3xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="text-xl">📘</span>
            Transcript - {{ $student->lastName ?? $student->lastname ?? '' }},
            {{ $student->firstName ?? $student->firstname ?? '' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Glass Card --}}
            <div class="bg-white/50 backdrop-blur-md shadow-2xl rounded-2xl p-6 transition-transform hover:scale-[1.01] duration-300">

                {{-- Student Info --}}
                <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <p class="text-gray-700"><span class="font-semibold">Student ID:</span> {{ $student->studentID ?? $student->studentId ?? '' }}</p>
                        <p class="text-gray-700"><span class="font-semibold">Name:</span> 
                            {{ $student->lastName ?? $student->lastname ?? '' }},
                            {{ $student->firstName ?? $student->firstname ?? '' }}
                            {{ $student->middleName ?? $student->middlename ?? '' }}
                            {{ $student->suffix ?? '' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-gray-700"><span class="font-semibold">Course:</span> {{ $student->courseTitle ?? $student->course ?? '' }}</p>
                        <p class="text-gray-700"><span class="font-semibold">Year Level:</span> {{ $student->yearLevel ?? $student->yearlevel ?? '' }}</p>
                        <p class="text-gray-700"><span class="font-semibold">School Year:</span> {{ $student->schoolYearTitle ?? $student->schoolYear ?? '' }}</p>
                    </div>
                </div>

                {{-- Grades Table --}}
                <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gradient-to-r from-[#A45D83] to-[#F7CDE9] text-white text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2 border">Year Level</th>
                                <th class="px-4 py-2 border">Semester</th>
                                <th class="px-4 py-2 border">Subject Code</th>
                                <th class="px-4 py-2 border">Descriptive Title</th>
                                <th class="px-4 py-2 border">Units</th>
                                <th class="px-4 py-2 border">Faculty</th>
                                <th class="px-4 py-2 border">Final Grade</th>
                                <th class="px-4 py-2 border">Retake Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($grades as $grade)
                                @php $gradeObj = (object) $grade; @endphp
                                <tr class="hover:bg-[#F7CDE9]/30 transition duration-200">
                                    <td class="px-4 py-2 border">{{ $gradeObj->yearLevel ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->schoolYearTitle ?? $gradeObj->semester ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->subjectCode ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->subjectTitle ?? $gradeObj->descriptiveTitle ?? $gradeObj->descriptive_title ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->units ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->Faculty ?? $gradeObj->faculty ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->FinalRating ?? $gradeObj->final_Rating ?? '' }}</td>
                                    <td class="px-4 py-2 border">{{ $gradeObj->RetakeGrade ?? $gradeObj->retakeGrade ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Optional: Footer Note --}}
                <p class="mt-4 text-xs text-gray-500 text-center">
                    End of transcript
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
