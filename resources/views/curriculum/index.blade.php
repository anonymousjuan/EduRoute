@extends('layouts.app')

@section('content')
<!-- Tailwind + Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<style>
    @media print {
        #addBtn, #importBtn, #printBtn, #deleteBtn, .year-selector {
            display: none !important;
        }
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-scale {
            zoom: 85%;
        }
    }
</style>

<div class="p-6 space-y-6 print-scale">

    <!-- ✅ Success Message -->
    @if(session('success'))
        <div class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- 📅 Year Selection -->
    <div class="year-selector text-center space-x-2">
        @foreach($availableYears as $availableYear)
            <a href="{{ route('curriculum.index', ['year' => $availableYear]) }}"
               class="inline-block px-4 py-2 rounded-lg font-semibold text-white transition 
                      {{ $availableYear == $year ? 'bg-[#800000]' : 'bg-gray-400 hover:bg-gray-500' }}">
                {{ $availableYear }}
            </a>
        @endforeach
    </div>

    <!-- 🎛️ Action Buttons -->
    <div class="flex justify-end flex-wrap gap-3">
        @if(Auth::user()->role !== 'dean')
            <a id="addBtn" href="{{ route('curriculum.create') }}"
               class="inline-flex items-center gap-2 bg-[#800000] text-white font-semibold px-4 py-2 rounded-md hover:bg-[#a33] transition">
               <i class="fa-solid fa-plus"></i> Add New Curriculum
            </a>

            <a id="importBtn" href="{{ route('curriculum.import.view') }}"
               class="inline-flex items-center gap-2 bg-[#800000] text-white font-semibold px-4 py-2 rounded-md hover:bg-[#a33] transition">
               <i class="fa-solid fa-file-import"></i> Import Curriculum
            </a>
        @endif

        <button id="printBtn" onclick="window.print()"
            class="inline-flex items-center gap-2 bg-gray-700 text-white font-semibold px-4 py-2 rounded-md hover:bg-gray-800 transition">
            <i class="fa-solid fa-print"></i> Print
        </button>

        @if(Auth::user()->role !== 'dean')
            <form action="{{ route('curriculum.deleteYear', ['year' => $year]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the entire curriculum for year {{ $year }}?')" class="m-0">
                @csrf
                @method('DELETE')
                <button id="deleteBtn" type="submit"
                    class="inline-flex items-center gap-2 bg-red-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-red-700 transition">
                    <i class="fa-solid fa-trash"></i> Delete Curriculum Year
                </button>
            </form>
        @endif
    </div>

    <!-- 🗓️ Year Title -->
    <div class="text-center text-2xl font-bold uppercase text-[#800000]">
        Year of Implementation: {{ $year }}
    </div>

    @php
        $yearLabels = [1 => 'First Year', 2 => 'Second Year', 3 => 'Third Year', 4 => 'Fourth Year'];
    @endphp

    <!-- 📘 Yearly Curriculum Tables -->
    @foreach($groupedCourses as $yearLevel => $semesters)
        <div class="border border-gray-300 rounded-lg shadow-md bg-white p-4 mt-6">
            <h3 class="text-xl font-bold text-center text-gray-800 mb-4">{{ $yearLabels[$yearLevel] ?? $yearLevel . ' Year' }}</h3>

            <div class="grid md:grid-cols-2 gap-6">

                {{-- 🔹 FIRST SEMESTER --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-800 uppercase text-xs">
                            <tr>
                                <th colspan="6" class="py-2 text-center font-semibold bg-[#800000] text-white">First Semester</th>
                            </tr>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-3 text-left border">Course No.</th>
                                <th class="py-2 px-3 text-left border">Descriptive Title</th>
                                <th class="py-2 px-3 text-center border">Units</th>
                                <th class="py-2 px-3 text-center border">Lec</th>
                                <th class="py-2 px-3 text-center border">Lab</th>
                                <th class="py-2 px-3 text-left border">Pre-requisite</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $firstTotalUnits = $firstTotalLec = $firstTotalLab = 0; @endphp
                            @foreach($semesters[1] ?? [] as $course)
                                @php
                                    $firstTotalUnits += $course->units ?? 0;
                                    $firstTotalLec += $course->lec ?? 0;
                                    $firstTotalLab += $course->lab ?? 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="border px-3 py-2">
                                        @if(Auth::user()->role !== 'dean')
                                            <a href="{{ route('curriculum.edit', ['curriculum' => $course->id, 'semester' => 1]) }}" class="text-[#800000] font-semibold hover:underline">
                                                {{ $course->course_no ?? 'N/A' }}
                                            </a>
                                        @else
                                            {{ $course->course_no ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="border px-3 py-2">
                                        {{ $course->descriptive_title ?? 'Untitled' }}
                                    </td>
                                    <td class="border px-3 py-2 text-center">{{ $course->units ?: '' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $course->lec ?: '' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $course->lab ?: '' }}</td>
                                    <td class="border px-3 py-2">{{ $course->prerequisite ?? '' }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="2" class="text-right py-2 px-3 border">Total:</td>
                                <td class="border text-center">{{ $firstTotalUnits ?: '' }}</td>
                                <td class="border text-center">{{ $firstTotalLec ?: '' }}</td>
                                <td class="border text-center">{{ $firstTotalLab ?: '' }}</td>
                                <td class="border"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- 🔹 SECOND SEMESTER --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-800 uppercase text-xs">
                            <tr>
                                <th colspan="6" class="py-2 text-center font-semibold bg-[#800000] text-white">Second Semester</th>
                            </tr>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-3 text-left border">Course No.</th>
                                <th class="py-2 px-3 text-left border">Descriptive Title</th>
                                <th class="py-2 px-3 text-center border">Units</th>
                                <th class="py-2 px-3 text-center border">Lec</th>
                                <th class="py-2 px-3 text-center border">Lab</th>
                                <th class="py-2 px-3 text-left border">Pre-requisite</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $secondTotalUnits = $secondTotalLec = $secondTotalLab = 0; @endphp
                            @foreach($semesters[2] ?? [] as $course)
                                @php
                                    $secondTotalUnits += $course->units ?? 0;
                                    $secondTotalLec += $course->lec ?? 0;
                                    $secondTotalLab += $course->lab ?? 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="border px-3 py-2">
                                        @if(Auth::user()->role !== 'dean')
                                            <a href="{{ route('curriculum.edit', ['curriculum' => $course->id, 'semester' => 2]) }}" class="text-[#800000] font-semibold hover:underline">
                                                {{ $course->course_no ?? 'N/A' }}
                                            </a>
                                        @else
                                            {{ $course->course_no ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="border px-3 py-2">
                                        {{ $course->descriptive_title ?? 'Untitled' }}
                                    </td>
                                    <td class="border px-3 py-2 text-center">{{ $course->units ?: '' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $course->lec ?: '' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $course->lab ?: '' }}</td>
                                    <td class="border px-3 py-2">{{ $course->prerequisite ?? '' }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="2" class="text-right py-2 px-3 border">Total:</td>
                                <td class="border text-center">{{ $secondTotalUnits ?: '' }}</td>
                                <td class="border text-center">{{ $secondTotalLec ?: '' }}</td>
                                <td class="border text-center">{{ $secondTotalLab ?: '' }}</td>
                                <td class="border"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endforeach
</div>
@endsection
