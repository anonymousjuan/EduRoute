<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            🎓 Students List
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white p-6 rounded-xl shadow-lg">

                {{-- ✅ Flash Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- 📥 Import --}}
                <a href="{{ route('students.import.form') }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg mb-6 inline-block hover:bg-indigo-700 transition text-sm shadow">
                   📥 Import CSV
                </a>

                {{-- ✅ If searching, show results --}}
                @if($searchResults)
                    <h3 class="font-semibold mb-2">🔍 Search Results</h3>
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
                                    <th class="px-2 py-1 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($searchResults as $student)
                                    <tr class="hover:bg-gray-50 text-gray-700">
                                        <td class="px-2 py-1 border">{{ $student->studentID }}</td>
                                        <td class="px-2 py-1 border">{{ $student->lastName }}</td>
                                        <td class="px-2 py-1 border">{{ $student->firstName }}</td>
                                        <td class="px-2 py-1 border">{{ $student->middleName }}</td>
                                        <td class="px-2 py-1 border">{{ $student->suffix }}</td>
                                        <td class="px-2 py-1 border">{{ $student->gender }}</td>
                                        <td class="px-2 py-1 border">{{ $student->schoolYearTitle }}</td>
                                        <td class="px-2 py-1 border">{{ $student->courseTitle }}</td>
                                        <td class="px-2 py-1 border">{{ $student->yearLevel }}</td>
                                        <td class="px-2 py-1 border flex space-x-1">
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-2 py-2 border text-center text-gray-400">
                                            No results found for "{{ $search }}".
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $searchResults->links() }}
                    </div>

                @else
                    {{-- 📑 Year Tabs --}}
                    <div x-data="{ tab: '1st Year' }">
                        <div class="flex border-b mb-4">
                            @foreach ($studentsByYear as $yearLabel => $students)
                                <button @click="tab = '{{ $yearLabel }}'"
                                        :class="tab === '{{ $yearLabel }}' 
                                            ? 'border-b-4 border-blue-600 text-blue-600 font-semibold' 
                                            : 'text-gray-600 hover:text-blue-600'"
                                        class="px-4 py-2 focus:outline-none transition">
                                    {{ $yearLabel }}
                                </button>
                            @endforeach
                        </div>

                        @foreach ($studentsByYear as $yearLabel => $students)
                            <div x-show="tab === '{{ $yearLabel }}'" x-transition>
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
                                                <th class="px-2 py-1 border">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($students as $student)
                                                <tr class="hover:bg-gray-50 text-gray-700">
                                                    <td class="px-2 py-1 border">{{ $student->studentID }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->lastName }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->firstName }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->middleName }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->suffix }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->gender }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->schoolYearTitle }}</td>
                                                    <td class="px-2 py-1 border">{{ $student->courseTitle }}</td>
                                                    <td class="px-2 py-1 border flex space-x-1">
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
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="px-2 py-2 border text-center text-gray-400">
                                                        No students found for {{ $yearLabel }}.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $students->links() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
