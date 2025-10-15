<table class="min-w-full border border-gray-200 rounded-lg shadow-sm mt-4">
    <thead class="bg-gray-100 text-gray-700 text-sm">
        <tr>
            <th class="px-4 py-2">Student ID</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Subject Code</th>
            <th class="px-4 py-2">Subject Title</th>
            <th class="px-4 py-2">Units</th>
            <th class="px-4 py-2">Faculty</th>
        </tr>
    </thead>
    <tbody>
        @foreach($studentGrades as $grade)
            <tr>
                <td class="px-4 py-2">{{ $grade->studentID }}</td>
                <td class="px-4 py-2">{{ $grade->lastName }}, {{ $grade->firstName }}</td>
                <td class="px-4 py-2">{{ $grade->subjectCode }}</td>
                <td class="px-4 py-2">{{ $grade->subjectTitle }}</td>
                <td class="px-4 py-2">{{ $grade->units }}</td>
                <td class="px-4 py-2">{{ $grade->Faculty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
