<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                ✏️ Edit Grades – <span class="text-indigo-600">{{ $student->lastName }}, {{ $student->firstName }}</span>
            </h2>

            <a href="{{ route('transcript.show', $student->studentID) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold transition">
                ⬅ Back to Transcript
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto">
        <div class="bg-white shadow-lg rounded-2xl p-6">

            @if($grades->first() && $grades->first()->is_locked)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    ⚠️ <strong>Locked:</strong> These grades are locked by your Program Head and cannot be edited.
                </div>
            @endif

            <!-- Student Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 text-center">
                <p class="text-gray-700"><strong>📌 Student ID:</strong> {{ $student->studentID }}</p>
                <p class="text-gray-700"><strong>🎓 Course:</strong> {{ $student->courseTitle ?? 'N/A' }}</p>
            </div>

            <!-- Grade Form & Table -->
            <form action="{{ route('grades.update', $student->studentID) }}" method="POST">
                @csrf
                <!-- Use PUT if route is put -->
                @method('POST') <!-- or 'PUT' if your route uses put -->

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 rounded-lg text-center">
                        <thead class="bg-indigo-600 text-white">
                            <tr>
                                <th class="px-4 py-3 border">Subject Code</th>
                                <th class="px-4 py-3 border">Subject Title</th>
                                <th class="px-4 py-3 border">Units</th>
                                <th class="px-4 py-3 border">Current Grade</th>
                                @if(!$grades->first()->is_locked)
                                    <th class="px-4 py-3 border">New Grade</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($grades as $grade)
                                <tr>
                                    <td class="px-4 py-3 border font-semibold text-gray-800">{{ $grade->subjectCode }}</td>
                                    <td class="px-4 py-3 border text-gray-700">{{ $grade->subjectTitle ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 border text-gray-700">{{ $grade->units ?? '—' }}</td>
                                    <td class="px-4 py-3 border text-gray-600">{{ $grade->Final_Rating ?? '—' }}</td>
                                    @if(!$grade->is_locked)
                                        <td class="px-4 py-3 border">
                                            <input type="text"
                                                name="grades[{{ $grade->subjectCode }}]"
                                                value="{{ old('grades.' . $grade->subjectCode, $grade->Final_Rating) }}"
                                                class="border border-gray-300 rounded-lg px-2 py-1 w-24 text-center focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Buttons -->
                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('grades.students', ['id' => $student->studentID]) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition">
                        ⬅ Cancel
                    </a>

                    @if(!$grades->first()->is_locked)
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-black font-semibold rounded-lg shadow transition">
                            💾 Save Changes
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
