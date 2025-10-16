<div>
    {{-- 🔎 Search Box + School Year Filter --}}
    <div class="flex items-center gap-2 mb-4">
        <input type="text" wire:model.live="search"
               placeholder="Search by ID, First, Last name..."
               class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-indigo-200 text-sm" />

        <select wire:model.live="schoolYear"
                class="px-3 py-2 border rounded-lg focus:ring focus:ring-indigo-200 text-sm">
            <option value="">-- Select School Year --</option>
            @foreach($schoolYears as $sy)
                <option value="{{ $sy }}">{{ $sy }}</option>
            @endforeach
        </select>

        @if($search || $schoolYear)
            <button wire:click="$set('search',''); $set('schoolYear','')" 
                    class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                Reset
            </button>
        @endif
    </div>

    {{-- 📑 Year Tabs --}}
    <div class="flex border-b mb-4">
        @foreach ($studentsByYear as $yearLabel => $students)
            <button wire:click="setTab('{{ $yearLabel }}')"
                    class="px-4 py-2 focus:outline-none transition 
                        {{ $tab === $yearLabel ? 'border-b-4 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                {{ $yearLabel }}
                <span class="text-xs text-gray-500">({{ $students->total() }})</span>
            </button>
        @endforeach
    </div>

    {{-- 📋 Year-based Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-xs">
            <thead class="bg-gray-50">
                <tr class="text-gray-700">
                    <th class="px-2 py-1 border">ID</th>
                    <th class="px-2 py-1 border">Last</th>
                    <th class="px-2 py-1 border">First</th>
                    <th class="px-2 py-1 border">Middle</th>
                    <th class="px-2 py-1 border">Suffix</th>
                    <th class="px-2 py-1 border">Gender</th>
                    <th class="px-2 py-1 border">SY</th>
                    <th class="px-2 py-1 border">Course</th>
                    <th class="px-2 py-1 border">Year</th>
                    @if(Auth::user()->role !== 'dean')
                        <th class="px-2 py-1 border">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($studentsByYear[$tab] as $student)
                    <tr class="hover:bg-gray-50 text-gray-700 transition">

                        {{-- 🧾 Clickable fields that open transcript --}}
                        <td class="px-2 py-1 border text-blue-600 hover:underline cursor-pointer"
                            wire:click="viewTranscript('{{ $student->studentID }}')">
                            {{ $student->studentID }}
                        </td>
                        <td class="px-2 py-1 border text-blue-600 hover:underline cursor-pointer"
                            wire:click="viewTranscript('{{ $student->studentID }}')">
                            {{ $student->lastName }}
                        </td>
                        <td class="px-2 py-1 border text-blue-600 hover:underline cursor-pointer"
                            wire:click="viewTranscript('{{ $student->studentID }}')">
                            {{ $student->firstName }}
                        </td>

                        {{-- Other columns --}}
                        <td class="px-2 py-1 border">{{ $student->middleName }}</td>
                        <td class="px-2 py-1 border">{{ $student->suffix }}</td>
                        <td class="px-2 py-1 border">{{ $student->gender }}</td>
                        <td class="px-2 py-1 border">{{ $student->schoolYearTitle }}</td>
                        <td class="px-2 py-1 border">{{ $student->courseTitle }}</td>
                        <td class="px-2 py-1 border">{{ $tab }}</td>

                        @if(Auth::user()->role !== 'dean')
                            <td class="px-2 py-1 border">
                                <div class="flex space-x-1">
                                    <a href="{{ route('students.edit', $student->id) }}" 
                                       class="px-2 py-0.5 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs shadow">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('students.destroy', $student->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Delete this student?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2 py-0.5 bg-red-600 text-white rounded hover:bg-red-700 text-xs shadow">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role !== 'dean' ? 10 : 9 }}" 
                            class="px-2 py-2 border text-center text-gray-400">
                            No students found for {{ $tab }} in {{ $schoolYear ?: 'all school years' }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $studentsByYear[$tab]->links() }}
    </div>
</div>
