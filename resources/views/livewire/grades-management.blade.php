<div>
    {{-- 🔎 Search --}}
    <div class="flex items-center gap-2 mb-4">
        <input type="text" wire:model.debounce.300ms="search" 
               placeholder="Search by ID, First, Last name..."
               class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-indigo-200 text-sm" />
        <button wire:click="$set('search','')" 
                class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">Clear</button>
    </div>

    @if($grades->count())
        <div x-data="{ tab: '{{ $studentsByYear->keys()->first() }}' }">
            {{-- Tabs --}}
            <div class="flex border-b mb-4">
                @foreach ($studentsByYear as $year => $students)
                    <button @click="tab = '{{ $year }}'"
                            :class="tab === '{{ $year }}' ? 'border-b-4 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600'"
                            class="px-4 py-2 focus:outline-none transition">
                        {{ $year }}
                    </button>
                @endforeach
            </div>

            {{-- Students Table per Year --}}
            @foreach ($studentsByYear as $year => $students)
                <div x-show="tab === '{{ $year }}'" x-transition>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-1 border">ID</th>
                                    <th class="px-2 py-1 border">Name</th>
                                    <th class="px-2 py-1 border">Course</th>
                                    <th class="px-2 py-1 border">Year Level</th>
                                    <th class="px-2 py-1 border">SY</th>
                                    <th class="px-2 py-1 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr class="hover:bg-gray-50 text-gray-700">
                                        <td class="px-2 py-1 border">{{ $student->studentID }}</td>
                                        <td class="px-2 py-1 border">{{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}</td>
                                        <td class="px-2 py-1 border">{{ $student->courseTitle }}</td>
                                        <td class="px-2 py-1 border">{{ $student->yearLevel }}</td>
                                        <td class="px-2 py-1 border">{{ $student->schoolYearTitle }}</td>
                                        <td class="px-2 py-1 border flex space-x-1">
                                            <a href="{{ route('grades.show', $student->studentID) }}"
                                               class="px-2 py-0.5 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs shadow">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-2 py-2 border text-center text-gray-400">
                                            No students found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $grades->links() }}
        </div>
    @else
        <p class="text-gray-500 text-center mt-4">No grades available.</p>
    @endif
</div>
