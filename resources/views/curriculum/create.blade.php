@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }
    input, select {
        padding: 5px;
        margin: 5px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
    }
    th, td {
        border: 1px solid black;
        padding: 6px;
        text-align: left;
    }
    th {
        background-color: #f8f9fa;
    }
    button.add-course {
        margin-top: 5px;
        background-color: #800000;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
    }
    button.remove-course {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
    }
    .semester-block {
        margin-bottom: 30px;
    }
</style>

<div class="container">
    <h2>Add New Curriculum</h2>

    <form action="{{ route('curriculum.store') }}" method="POST">
        @csrf

        <!-- Year of Implementation -->
        <div>
            <label for="year_of_implementation">Year of Implementation:</label>
            <input type="text" name="year_of_implementation" id="year_of_implementation" required placeholder="e.g., 2024-2025">
        </div>

        @php
            $yearLabels = [
                1 => 'First Year',
                2 => 'Second Year',
                3 => 'Third Year',
                4 => 'Fourth Year'
            ];
        @endphp

        @foreach($yearLabels as $yearLevel => $label)
        <div class="year-block">
            <h3>{{ $label }}</h3>

            @foreach([1 => 'First Semester', 2 => 'Second Semester'] as $semNumber => $semLabel)
            <div class="semester-block">
                <h4>{{ $semLabel }}</h4>
                <table id="table-year-{{ $yearLevel }}-sem-{{ $semNumber }}">
                    <thead>
                        <tr>
                            <th>Course No.</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Pre-requisite</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Initial empty row -->
                        <tr>
                            <td><input type="text" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][course_no]" required></td>
                            <td><input type="text" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][descriptive_title]" required></td>
                            <td><input type="number" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][units]" required></td>
                            <td><input type="number" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][lec]" required></td>
                            <td><input type="number" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][lab]" required></td>
                            <td><input type="text" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][prerequisite]"></td>
                            <td><button type="button" class="remove-course" onclick="removeRow(this)">Remove</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="add-course" onclick="addRow({{ $yearLevel }}, {{ $semNumber }})">➕ Add Course</button>
            </div>
            @endforeach
        </div>
        @endforeach

        <button type="submit" style="background-color: #800000; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">💾 Save Curriculum</button>
    </form>
</div>

<script>
function addRow(yearLevel, semester) {
    const tableBody = document.querySelector(`#table-year-${yearLevel}-sem-${semester} tbody`);
    const rowCount = tableBody.rows.length;
    const row = document.createElement('tr');

    row.innerHTML = `
        <td><input type="text" name="courses[${yearLevel}][${semester}][${rowCount}][course_no]" required></td>
        <td><input type="text" name="courses[${yearLevel}][${semester}][${rowCount}][descriptive_title]" required></td>
        <td><input type="number" name="courses[${yearLevel}][${semester}][${rowCount}][units]" required></td>
        <td><input type="number" name="courses[${yearLevel}][${semester}][${rowCount}][lec]" required></td>
        <td><input type="number" name="courses[${yearLevel}][${semester}][${rowCount}][lab]" required></td>
        <td><input type="text" name="courses[${yearLevel}][${semester}][${rowCount}][prerequisite]"></td>
        <td><button type="button" class="remove-course" onclick="removeRow(this)">Remove</button></td>
    `;
    tableBody.appendChild(row);
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
}
</script>
@endsection
