<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        📘 Faculty Subjects
    </h1>

    {{-- Search Inputs --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <input type="text" placeholder="Search faculty..." wire:model.debounce.300ms="searchFaculty"
               class="flex-1 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
        <input type="text" placeholder="Search subject..." wire:model.debounce.300ms="searchSubject"
               class="flex-1 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
    </div>

    @if(count($filteredFaculties) > 0)
        <div class="space-y-5">
            @foreach($filteredFaculties as $facultyName => $subjects)
                <div class="border rounded-xl shadow-md overflow-hidden bg-white">
                    {{-- Faculty Header --}}
                    <div class="px-6 py-4 bg-blue-600 text-black font-semibold text-lg">
                        👨‍🏫 {{ $facultyName }}
                    </div>

                    {{-- Subjects List --}}
                    <div class="p-6 bg-gray-50">
                        @if(count($subjects) > 0)
                            <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-gray-100 text-gray-700 text-sm">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Code</th>
                                        <th class="px-4 py-2 text-left">Title</th>
                                        <th class="px-4 py-2 text-center">Units</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($subjects as $subject)
                                        <tr class="hover:bg-blue-50 cursor-pointer transition transform active:scale-95"
                                            onclick="window.location='{{ route('faculty.subject.show', $subject['subjectCode']) }}'">
                                            <td class="px-4 py-2 font-medium">{{ $subject['subjectCode'] }}</td>
                                            <td class="px-4 py-2">{{ $subject['subjectTitle'] }}</td>
                                            <td class="px-4 py-2 text-center">{{ $subject['units'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-4 text-gray-400">
                                No subjects found for this faculty.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 text-gray-400">
            <p class="text-lg">No faculties or subjects found.</p>
        </div>
    @endif
</div>
