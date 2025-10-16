@extends('layouts.app')
@if(session('success'))
    <div style="color: green; font-weight: bold; margin-bottom: 10px;">
        {{ session('success') }}
    </div>
@endif
@section('content')
<style>
    table {
        font-family: Arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }
    td, th {
        border: 1px solid black;
        text-align: left;
        padding: 6px;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    th {
        background-color: #f8f9fa;
    }
    .semester-wrapper {
        display: flex;
        gap: 10px;
    }
    .semester-table {
        width: 50%;
    }
    .year-title {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 15px 0;
        text-transform: uppercase;
    }
    .year-block {
        border: 1px solid black;
        padding: 10px;
        margin-bottom: 15px;
    }
    .year-header {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .year-selector {
        margin-bottom: 15px;
        text-align: center;
    }
    .year-selector a {
        display: inline-block;
        padding: 8px 15px;
        margin: 5px;
        border-radius: 5px;
        background-color: #C0C0C0;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }
    .year-selector a.active {
        background-color: #800000;
    }
    .total-row {
        font-weight: bold;
        background-color: #f8f9fa;
        font-size: 11px;
    }
    /* Print settings */
    @page {
        size: A4;
        margin: 10mm;
    }
    @media print {
        #addBtn, #importBtn, .year-selector {
            display: none;
        }
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .container {
            zoom: 80%;
        }
    }
</style>

<div class="container">

    <!-- Year Selection Buttons -->
    <div class="year-selector">
        @foreach($availableYears as $availableYear)
            <a href="{{ route('curriculum.index', ['year' => $availableYear]) }}"
               class="{{ $availableYear == $year ? 'active' : '' }}">
                {{ $availableYear }}
            </a>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 15px;">
        {{-- Hide Add & Import for Dean --}}
        @if(Auth::user()->role !== 'dean')
            <a id="addBtn" href="{{ route('curriculum.create') }}" 
               style="background-color: #800000; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                ➕ Add New Curriculum
            </a>

            <a id="importBtn" href="{{ route('curriculum.import.view') }}" 
               style="background-color: #800000; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                📂 Import Curriculum
            </a>
        @endif

        {{-- Always show Print button --}}
        <button id="printBtn" onclick="window.print()" 
            style="background-color: #800000; color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold;">
            🖨️ Print
        </button>

        {{-- Delete Curriculum Year Button --}}
        @if(Auth::user()->role !== 'dean')
            <form action="{{ route('curriculum.deleteYear', ['year' => $year]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the entire curriculum for year {{ $year }}? This action cannot be undone.')" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    style="background-color: #ff4d4d; color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold;">
                    🗑️ Delete Curriculum Year
                </button>
            </form>
        @endif
    </div>

    <div class="year-title">
        Year of Implementation: {{ $year }}
    </div>

    @php
        $yearLabels = [
            1 => 'FIRST YEAR',
            2 => 'SECOND YEAR',
            3 => 'THIRD YEAR',
            4 => 'FOURTH YEAR'
        ];
    @endphp

    @foreach($groupedCourses as $yearLevel => $semesters)
        <div class="year-block">
            <h3 class="year-header">{{ $yearLabels[$yearLevel] ?? $yearLevel . ' YEAR' }}</h3>

            <div class="semester-wrapper">

                {{-- FIRST SEMESTER --}}
                <table class="semester-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="text-align: center">First Semester</th>
                        </tr>
                        <tr>
                            <th>Course No.</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Pre-requisite</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $firstTotalUnits = 0;
                            $firstTotalLec = 0;
                            $firstTotalLab = 0;
                        @endphp

                        @foreach($semesters[1] ?? [] as $course)
                            @php
                                $firstTotalUnits += $course->units ?? 0;
                                $firstTotalLec += $course->lec ?? 0;
                                $firstTotalLab += $course->lab ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    @if(Auth::user()->role !== 'dean' && $course && !empty($course->id))
                                        <a href="{{ route('curriculum.edit', ['curriculum' => $course->id, 'semester' => 1]) }}" class="text-blue-600 hover:underline">
                                            {{ $course->course_no ?? 'N/A' }}
                                        </a>
                                    @else
                                        {{ $course->course_no ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    @if(Auth::user()->role !== 'dean' && $course && !empty($course->id))
                                        <a href="{{ route('curriculum.edit', ['curriculum' => $course->id, 'semester' => 1]) }}" class="text-blue-600 hover:underline">
                                            {{ $course->descriptive_title ?? 'Untitled' }}
                                        </a>
                                    @else
                                        {{ $course->descriptive_title ?? 'Untitled' }}
                                    @endif
                                </td>
                                <td>{{ ($course->units ?? '') !== '' && $course->units != 0 ? $course->units : '' }}</td>
                                <td>{{ ($course->lec ?? '') !== '' && $course->lec != 0 ? $course->lec : '' }}</td>
                                <td>{{ ($course->lab ?? '') !== '' && $course->lab != 0 ? $course->lab : '' }}</td>
                                <td>{{ $course->prerequisite ?? '' }}</td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td colspan="2" style="text-align: right;">Total:</td>
                            <td>{{ $firstTotalUnits > 0 ? $firstTotalUnits : '' }}</td>
                            <td>{{ $firstTotalLec > 0 ? $firstTotalLec : '' }}</td>
                            <td>{{ $firstTotalLab > 0 ? $firstTotalLab : '' }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                {{-- SECOND SEMESTER --}}
                <table class="semester-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="text-align: center">Second Semester</th>
                        </tr>
                        <tr>
                            <th>Course No.</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Pre-requisite</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $secondTotalUnits = 0;
                            $secondTotalLec = 0;
                            $secondTotalLab = 0;
                        @endphp

                        @foreach($semesters[2] ?? [] as $course)
                            @php
                                $secondTotalUnits += $course->units ?? 0;
                                $secondTotalLec += $course->lec ?? 0;
                                $secondTotalLab += $course->lab ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    @if(Auth::user()->role !== 'dean' && $course && !empty($course->id))
                                        <a href="{{ route('curriculum.edit', ['curriculum' => $course->id, 'semester' => 2]) }}" class="text-blue-600 hover:underline">
                                            {{ $course->course_no ?? 'N/A' }}
                                        </a>
                                    @else
                                        {{ $course->course_no ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    @if(Auth::user()->role !== 'dean' && $course && !empty($course->id))
                                        <a href="{{ route('curriculum.edit', ['curriculum' => $course->id, 'semester' => 2]) }}" class="text-blue-600 hover:underline">
                                            {{ $course->descriptive_title ?? 'Untitled' }}
                                        </a>
                                    @else
                                        {{ $course->descriptive_title ?? 'Untitled' }}
                                    @endif
                                </td>
                                <td>{{ ($course->units ?? '') !== '' && $course->units != 0 ? $course->units : '' }}</td>
                                <td>{{ ($course->lec ?? '') !== '' && $course->lec != 0 ? $course->lec : '' }}</td>
                                <td>{{ ($course->lab ?? '') !== '' && $course->lab != 0 ? $course->lab : '' }}</td>
                                <td>{{ $course->prerequisite ?? '' }}</td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td colspan="2" style="text-align: right;">Total:</td>
                            <td>{{ $secondTotalUnits > 0 ? $secondTotalUnits : '' }}</td>
                            <td>{{ $secondTotalLec > 0 ? $secondTotalLec : '' }}</td>
                            <td>{{ $secondTotalLab > 0 ? $secondTotalLab : '' }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    @endforeach
</div>
@endsection
