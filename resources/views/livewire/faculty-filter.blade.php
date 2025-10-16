<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- ✅ Alerts --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- ✅ Header --}}
    <h1 class="text-3xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        📘 Faculty Subjects
    </h1>

    {{-- ✅ Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <input type="text" placeholder="Search faculty..." wire:model.debounce.300ms="searchFaculty"
               class="flex-1 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
        <input type="text" placeholder="Search subject..." wire:model.debounce.300ms="searchSubject"
               class="flex-1 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
    </div>

    {{-- ✅ Faculties List --}}
    @if(count($filteredFaculties) > 0)
        <div class="space-y-5">
            @foreach($filteredFaculties as $facultyName => $data)
                <div class="border rounded-xl shadow-md overflow-hidden bg-white">

                    {{-- Faculty Header --}}
                    <div class="px-6 py-4 bg-blue-600 text-black font-semibold text-lg flex justify-between items-center cursor-pointer"
                         wire:click="toggleFaculty('{{ $facultyName }}')">

                        <span>👨‍🏫 {{ $facultyName }}</span>

                        <span class="flex gap-2" wire:click.stop>
                            <a href="{{ route('subjects.create') }}" 
                               class="bg-green-600 text-black px-2 py-1 rounded hover:bg-green-700 text-sm">
                                🎓 Add Student
                            </a>

                            <a href="{{ route('subjects.create') }}" 
                               class="bg-purple-600 text-black px-2 py-1 rounded hover:bg-purple-700 text-sm">
                                📚 Add Subject
                            </a>

                            <button wire:click.stop="deleteFaculty('{{ $facultyName }}')"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">
                                🗑️ Delete
                            </button>
                        </span>
                    </div>

                    {{-- Expanded Faculty Content --}}
                    @if($expandedFaculty === $facultyName)
                        <div class="p-6 bg-gray-50 space-y-6">

                            {{-- ✅ Subjects Table --}}
                            <div>
                                <h2 class="font-semibold mb-2 text-gray-700">Subjects Handled</h2>
                                @if(isset($data['subjects']) && count($data['subjects']) > 0)
                                    <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                                        <thead class="bg-gray-100 text-gray-700 text-sm">
                                            <tr>
                                                <th class="px-4 py-2 text-left">Code</th>
                                                <th class="px-4 py-2 text-left">Title</th>
                                                <th class="px-4 py-2 text-center">Units</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($data['subjects'] ?? [] as $subject)
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
                                    <div class="text-gray-400 text-center py-4">No subjects assigned.</div>
                                @endif
                            </div>

                            {{-- ✅ Students Table --}}
                            <div>
                                <h2 class="font-semibold mb-2 text-gray-700">Students Handled</h2>
                                @if(isset($data['students']) && count($data['students']) > 0)
                                    <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                                        <thead class="bg-gray-100 text-gray-700 text-sm">
                                            <tr>
                                                <th class="px-4 py-2 text-left">Student ID</th>
                                                <th class="px-4 py-2 text-left">Name</th>
                                                <th class="px-4 py-2 text-center">Year Level</th>
                                                <th class="px-4 py-2 text-left">Course</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                        @foreach($data['students'] ?? [] as $student)
                                            <tr class="hover:bg-green-50 transition transform active:scale-95">
                                                <td class="px-4 py-2">
                                                    {{ is_array($student) ? ($student['studentID'] ?? '-') : ($student->studentID ?? '-') }}
                                                </td>
                                                <td class="px-4 py-2">
    {{ is_array($student) ? ($student['last_name'] ?? '-') : ($student->last_name ?? '-') }},
    {{ is_array($student) ? ($student['first_name'] ?? '-') : ($student->first_name ?? '-') }}
</td>

                                                <td class="px-4 py-2 text-center">
                                                    {{ is_array($student) ? ($student['yearLevel'] ?? '-') : ($student->yearLevel ?? '-') }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ is_array($student) ? ($student['courseTitle'] ?? '-') : ($student->courseTitle ?? '-') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                @else
                                    <div class="text-gray-400 text-center py-4">No students found for this faculty.</div>
                                @endif
                            </div>

                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 text-gray-400">
            <p class="text-lg">No faculties or subjects found.</p>
        </div>
    @endif

</div>
