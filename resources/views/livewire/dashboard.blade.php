<tr class="hover:bg-blue-50 cursor-pointer transition transform active:scale-95"
    onclick="window.location='{{ route('faculty.subjects.show', $subject['subjectCode']) }}'">
    <td class="px-4 py-2 font-medium">{{ $subject['subjectCode'] }}</td>
    <td class="px-4 py-2">{{ $subject['subjectTitle'] }}</td>
    <td class="px-4 py-2 text-center">{{ $subject['units'] }}</td>
</tr>
