<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            🎓 Transcript of Records 
            <span class="text-indigo-600">({{ $student->lastName }}, {{ $student->firstName }})</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-6">

                <!-- 🧾 Student Info -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 text-center">
                    <p class="text-gray-700"><strong>📌 Student ID:</strong> {{ $student->studentID }}</p>
                    <p class="text-gray-700"><strong>🎓 Course:</strong> {{ $student->courseTitle }}</p>
                </div>

                @php
                    $groupedByYear = $grades->groupBy('yearLevel');
                    $yearMap = [
                        '1' => 'FIRST YEAR',
                        '2' => 'SECOND YEAR',
                        '3' => 'THIRD YEAR',
                        '4' => 'FOURTH YEAR',
                        '5' => 'FIFTH YEAR',
                    ];
                @endphp

                @foreach($groupedByYear as $yearLevel => $yearGrades)
                    @php
                        $yearLevelStr = (string) $yearLevel;
                        $semesters = $yearGrades->groupBy('schoolYearTitle');
                    @endphp

                    <!-- Year Header and Edit Link -->
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $yearMap[$yearLevelStr] ?? 'YEAR ' . $yearLevelStr }}
                        </h3>

                        @if($yearLevelStr)
                            <a href="{{ route('grades.editByYear', ['studentID' => $student->studentID, 'yearLevel' => $yearLevelStr]) }}"
                               class="text-indigo-600 font-semibold hover:underline">
                               ✏️ Edit Grades
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        @foreach($semesters as $semesterTitle => $semesterGrades)
                            @php
                                $semesterUnits = $semesterGrades->sum('units');
                            @endphp

                            <div class="border rounded-lg shadow overflow-hidden">
                                <h3 class="text-md font-bold text-white bg-indigo-600 px-4 py-2 text-center">
                                    {{ $semesterTitle }}
                                </h3>
                                <!-- Semester Table Component -->
                                <x-semester-table :grades="$semesterGrades" :totalUnits="$semesterUnits"/>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <!-- 🔙 Back Button -->
                <div class="mt-6 flex justify-start">
                    <a href="{{ route('grades.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-semibold rounded-lg shadow hover:bg-gray-300 transition">
                       ← Back to Grades
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
