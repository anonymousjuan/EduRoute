<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-100">
            <th class="border p-2">Subject Code</th>
            <th class="border p-2">Subject Title</th>
            <th class="border p-2">Final Rating</th>
            <th class="border p-2">Year Level</th>
            <th class="border p-2">Semester</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grades as $grade)
            <tr>
                <td class="border p-2">{{ $grade->subjectCode }}</td>
                <td class="border p-2">{{ $grade->subjectTitle }}</td>
                <td class="border p-2">{{ $grade->Final_Rating }}</td>
                <td class="border p-2">{{ $grade->yearLevel }}</td>
                <td class="border p-2">{{ $grade->semester }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
