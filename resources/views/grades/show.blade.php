<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight text-center tracking-wide">
            🎓 Transcript of Records 
            <span class="text-indigo-600">({{ $student->lastName }}, {{ $student->firstName }})</span>
        </h2>
    </x-slot>

    {{-- Tailwind CSS CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">

    <style>
      /* Add subtle shadow and rounded corners to tables */
      table {
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
        border-radius: 0.5rem;
        border-collapse: separate !important;
        border-spacing: 0;
      }

      /* Rounded corners for thead */
      thead tr th:first-child {
        border-top-left-radius: 0.5rem;
      }
      thead tr th:last-child {
        border-top-right-radius: 0.5rem;
      }

      /* Rounded corners for tfoot */
      tfoot tr td:first-child {
        border-bottom-left-radius: 0.5rem;
      }
      tfoot tr td:last-child {
        border-bottom-right-radius: 0.5rem;
      }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Student Info Card --}}
            <div class="bg-white/80 backdrop-blur-md border border-gray-200 rounded-2xl p-6 shadow-lg text-center">
                <p class="text-gray-700 text-lg font-medium mb-2">📌 <strong>Student ID:</strong> {{ $student->studentID }}</p>
                <p class="text-gray-700 text-lg font-medium">🎓 <strong>Course:</strong> {{ $student->courseTitle }}</p>
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
                {{-- Year Level Header --}}
                <h2 class="text-2xl font-extrabold text-center text-gray-900 mt-8 mb-6 tracking-wide">
                    {{ $yearMap[$yearLevel] ?? strtoupper($yearLevel) }}
                </h2>

                @php
                    $firstSem = $yearGrades->filter(fn($g) => str_contains(strtolower($g->schoolYearTitle), '1st semester'))->unique('subjectCode');
                    $secondSem = $yearGrades->filter(fn($g) => str_contains(strtolower($g->schoolYearTitle), '2nd semester'))->unique('subjectCode');

                    $firstSemUnits = $firstSem->sum('units');
                    $secondSemUnits = $secondSem->sum('units');
                @endphp

                {{-- Semester Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- First Semester --}}
                    <div class="bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl shadow-lg overflow-hidden transition hover:scale-[1.02] duration-300">
                        <h3 class="text-lg font-bold text-white bg-gradient-to-r from-indigo-500 to-purple-500 px-4 py-2 text-center">
                            First Semester
                        </h3>
                        <x-semester-table :grades="$firstSem" :totalUnits="$firstSemUnits" />
                    </div>

                    {{-- Second Semester --}}
                    <div class="bg-white/70 backdrop-blur-md border border-gray-200 rounded-2xl shadow-lg overflow-hidden transition hover:scale-[1.02] duration-300">
                        <h3 class="text-lg font-bold text-white bg-gradient-to-r from-indigo-500 to-purple-500 px-4 py-2 text-center">
                            Second Semester
                        </h3>
                        <x-semester-table :grades="$secondSem" :totalUnits="$secondSemUnits" />
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
