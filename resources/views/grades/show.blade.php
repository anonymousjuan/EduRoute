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
                    // Group by year level
                    $groupedByYear = $grades->groupBy('yearLevel');

                    // Year map
                    $yearMap = [
                        '1' => 'FIRST YEAR',
                        '2' => 'SECOND YEAR',
                        '3' => 'THIRD YEAR',
                        '4' => 'FOURTH YEAR',
                        '5' => 'FIFTH YEAR',
                    ];
                @endphp

                @foreach($groupedByYear as $yearLevel => $yearGrades)
                    <!-- Year Level Header -->
                    <h2 class="text-2xl font-bold text-center text-black mt-10 mb-6">
                        {{ $yearMap[$yearLevel] ?? strtoupper($yearLevel) }}
                    </h2>

                    @php
                        // Group per semester and remove duplicates
                        $firstSem = $yearGrades->filter(fn($g) => str_contains(strtolower($g->schoolYearTitle), '1st semester'))->unique('subjectCode');
                        $secondSem = $yearGrades->filter(fn($g) => str_contains(strtolower($g->schoolYearTitle), '2nd semester'))->unique('subjectCode');

                        // Units computation
                        $firstSemUnits = $firstSem->sum('units');
                        $secondSemUnits = $secondSem->sum('units');
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Semester -->
                        <div class="border rounded-lg shadow overflow-hidden">
                            <h3 class="text-md font-bold text-white bg-indigo-600 px-4 py-2 text-center">
                                First Semester
                            </h3>
                            <x-semester-table :grades="$firstSem" :totalUnits="$firstSemUnits" />
                        </div>

                        <!-- Second Semester -->
                        <div class="border rounded-lg shadow overflow-hidden">
                            <h3 class="text-md font-bold text-white bg-indigo-600 px-4 py-2 text-center">
                                Second Semester
                            </h3>
                            <x-semester-table :grades="$secondSem" :totalUnits="$secondSemUnits" />
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
