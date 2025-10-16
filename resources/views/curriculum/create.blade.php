@extends('layouts.app')

@section('content')
<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-[#800000] mb-6 text-center">
            📘 Add New Curriculum
        </h2>

        <form action="{{ route('curriculum.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Year of Implementation -->
            <div>
                <label for="year_of_implementation" class="block text-gray-700 font-semibold mb-1">
                    Year of Implementation
                </label>
                <input type="text" id="year_of_implementation" name="year_of_implementation"
                       placeholder="e.g., 2024-2025"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#800000] focus:border-[#800000] transition duration-150 p-3"
                       required>
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
                <div class="border border-gray-300 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-[#800000] mb-4 uppercase tracking-wide">{{ $label }}</h3>

                    @foreach([1 => 'First Semester', 2 => 'Second Semester'] as $semNumber => $semLabel)
                        <div class="semester-block mb-8">
                            <h4 class="text-md font-semibold text-gray-700 mb-3">{{ $semLabel }}</h4>
                            <div class="overflow-x-auto bg-gray-50 rounded-xl border border-gray-200 shadow-inner">
                                <table id="table-year-{{ $yearLevel }}-sem-{{ $semNumber }}"
                                       class="w-full text-sm text-left text-gray-700">
                                    <thead class="bg-[#800000] text-white">
                                        <tr>
                                            <th class="px-4 py-2">Course No.</th>
                                            <th class="px-4 py-2">Descriptive Title</th>
                                            <th class="px-4 py-2 text-center">Units</th>
                                            <th class="px-4 py-2 text-center">Lec</th>
                                            <th class="px-4 py-2 text-center">Lab</th>
                                            <th class="px-4 py-2">Pre-requisite</th>
                                            <th class="px-4 py-2 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd:bg-white even:bg-gray-50">
                                            <td class="px-3 py-2"><input type="text" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][course_no]" class="w-full border rounded-md px-2 py-1 focus:ring-[#800000] focus:border-[#800000]" required></td>
                                            <td class="px-3 py-2"><input type="text" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][descriptive_title]" class="w-full border rounded-md px-2 py-1 focus:ring-[#800000] focus:border-[#800000]" required></td>
                                            <td class="px-3 py-2 text-center"><input type="number" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][units]" class="w-16 border rounded-md text-center focus:ring-[#800000]" required></td>
                                            <td class="px-3 py-2 text-center"><input type="number" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][lec]" class="w-16 border rounded-md text-center focus:ring-[#800000]" required></td>
                                            <td class="px-3 py-2 text-center"><input type="number" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][lab]" class="w-16 border rounded-md text-center focus:ring-[#800000]" required></td>
                                            <td class="px-3 py-2"><input type="text" name="courses[{{ $yearLevel }}][{{ $semNumber }}][0][prerequisite]" class="w-full border rounded-md px-2 py-1 focus:ring-[#800000]"></td>
                                            <td class="px-3 py-2 text-center">
                                                <button type="button" class="remove-course bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md shadow transition" onclick="removeRow(this)">✖</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button"
                                    class="add-course mt-3 bg-[#800000] hover:bg-[#a52a2a] text-white font-semibold px-4 py-2 rounded-md shadow transition"
                                    onclick="addRow({{ $yearLevel }}, {{ $semNumber }})">
                                ➕ Add Course
                            </button>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="text-center">
                <button type="submit"
                        class="bg-[#800000] hover:bg-[#a52a2a] text-white font-bold px-6 py-3 rounded-lg shadow-md transition transform hover:scale-105">
                    💾 Save Curriculum
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addRow(yearLevel, semester) {
    const tableBody = document.querySelector(`#table-year-${yearLevel}-sem-${semester} tbody`);
    const rowCount = tableBody.rows.length;
    const row = document.createElement('tr');
    row.classList.add('odd:bg-white', 'even:bg-gray-50');
    row.innerHTML = `
        <td class="px-3 py-2"><input type="text" name="courses[${yearLevel}][${semester}][${rowCount}][course_no]" class="w-full border rounded-md px-2 py-1 focus:ring-[#800000] focus:border-[#800000]" required></td>
        <td class="px-3 py-2"><input type="text" name="courses[${yearLevel}][${semester}][${rowCount}][descriptive_title]" class="w-full border rounded-md px-2 py-1 focus:ring-[#800000] focus:border-[#800000]" required></td>
        <td class="px-3 py-2 text-center"><input type="number" name="courses[${yearLevel}][${semester}][${rowCount}][units]" class="w-16 border rounded-md text-center focus:ring-[#800000]" required></td>
        <td class="px-3 py-2 text-center"><input type="number" name="courses[${yearLevel}][${semester}][${rowCount}][lec]" class="w-16 border rounded-md text-center focus:ring-[#800000]" required></td>
        <td class="px-3 py-2 text-center"><input type="number" name="courses[${yearLevel}][${semester}][${rowCount}][lab]" class="w-16 border rounded-md text-center focus:ring-[#800000]" required></td>
        <td class="px-3 py-2"><input type="text" name="courses[${yearLevel}][${semester}][${rowCount}][prerequisite]" class="w-full border rounded-md px-2 py-1 focus:ring-[#800000]"></td>
        <td class="px-3 py-2 text-center">
            <button type="button" class="remove-course bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md shadow transition" onclick="removeRow(this)">✖</button>
        </td>
    `;
    tableBody.appendChild(row);
}

function removeRow(button) {
    button.closest('tr').remove();
}
</script>
@endsection
