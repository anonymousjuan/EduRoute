<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students List</title>

    <!-- ✅ Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: Tailwind custom config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: '#800000',
                        dusty: '#A45D83',
                        rose: '#F7CDE9',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 p-6">

    {{-- 🔽 Your Existing Code Starts Here --}}
    <div class="space-y-6">

        {{-- 🔎 Search & Filter --}}
        <div class="flex flex-col sm:flex-row items-center gap-4 mb-6 bg-white/30 backdrop-blur-lg rounded-3xl p-5 shadow-lg border border-white/20">
            <input type="text" wire:model.live="search"
                   placeholder="Search by ID, First, Last name..."
                   class="flex-1 px-5 py-3 border border-gray-300 rounded-2xl shadow-inner focus:ring-2 focus:ring-[#A45D83] focus:border-transparent text-sm placeholder-gray-400 transition duration-300" />

            <select wire:model.live="schoolYear"
                    class="px-5 py-3 border border-gray-300 rounded-2xl shadow-inner focus:ring-2 focus:ring-[#A45D83] focus:border-transparent text-sm transition duration-300">
                <option value="">-- Select School Year --</option>
                @foreach($schoolYears as $sy)
                    <option value="{{ $sy }}">{{ $sy }}</option>
                @endforeach
            </select>

            @if($search || $schoolYear)
                <button wire:click="$set('search',''); $set('schoolYear','')"
                        class="px-5 py-3 bg-gray-100 text-gray-800 rounded-2xl hover:bg-gray-200 transition duration-300 text-sm shadow">
                    Reset
                </button>
            @endif
        </div>

        {{-- 📑 Year Tabs --}}
        <div class="flex flex-wrap gap-3 mb-6">
            @foreach ($studentsByYear as $yearLabel => $students)
                <button wire:click="setTab('{{ $yearLabel }}')"
                        class="px-6 py-2 rounded-full font-semibold transition-all duration-300
                            {{ $tab === $yearLabel 
                                ? 'bg-gradient-to-r from-[#A45D83] to-[#800000] text-white shadow-lg transform scale-105' 
                                : 'bg-gray-200 text-gray-700 hover:bg-[#F7CDE9]/50 hover:text-[#800000] hover:scale-105' }}">
                    {{ $yearLabel }}
                    <span class="text-xs text-gray-400 ml-1">({{ $students->total() }})</span>
                </button>
            @endforeach
        </div>

        {{-- 📋 Students Table --}}
        <div class="overflow-x-auto rounded-3xl shadow-2xl border border-gray-200 bg-white/40 backdrop-blur-md">
            <table class="min-w-full text-sm text-gray-700">
                <!-- ✅ MAROON HEADER -->
                <thead class="bg-[#800000] text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 border">ID</th>
                        <th class="px-4 py-3 border">Last</th>
                        <th class="px-4 py-3 border">First</th>
                        <th class="px-4 py-3 border">Middle</th>
                        <th class="px-4 py-3 border">Suffix</th>
                        <th class="px-4 py-3 border">Gender</th>
                        <th class="px-4 py-3 border">SY</th>
                        <th class="px-4 py-3 border">Course</th>
                        <th class="px-4 py-3 border">Year</th>
                        @if(Auth::user()->role !== 'dean')
<<<<<<< HEAD
                            <td class="px-2 py-1 border text-center">
                                <a href="{{ route('students.edit', $student->id) }}" 
                                   class="px-2 py-0.5 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs shadow">
                                    ✏️ Edit
                                </a>
                            </td>
=======
                            <th class="px-4 py-3 border">Actions</th>
>>>>>>> experiment
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-300">
                    @forelse($studentsByYear[$tab] as $student)
                        <tr class="hover:bg-[#F7CDE9]/30 hover:shadow-md transition-all duration-300 cursor-pointer">
                            {{-- Clickable fields --}}
                            <td class="px-4 py-2 border text-[#800000] font-medium hover:underline"
                                wire:click="viewTranscript('{{ $student->studentID }}')">{{ $student->studentID }}</td>
                            <td class="px-4 py-2 border text-[#800000] hover:underline"
                                wire:click="viewTranscript('{{ $student->studentID }}')">{{ $student->lastName }}</td>
                            <td class="px-4 py-2 border text-[#800000] hover:underline"
                                wire:click="viewTranscript('{{ $student->studentID }}')">{{ $student->firstName }}</td>

                            {{-- Other fields --}}
                            <td class="px-4 py-2 border">{{ $student->middleName }}</td>
                            <td class="px-4 py-2 border">{{ $student->suffix }}</td>
                            <td class="px-4 py-2 border">{{ $student->gender }}</td>
                            <td class="px-4 py-2 border">{{ $student->schoolYearTitle }}</td>
                            <td class="px-4 py-2 border">{{ $student->courseTitle }}</td>
                            <td class="px-4 py-2 border">{{ $tab }}</td>

                            @if(Auth::user()->role !== 'dean')
                                <td class="px-4 py-2 border">
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <a href="{{ route('students.edit', $student->id) }}"
                                           class="px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-medium shadow hover:bg-yellow-600 transition-all duration-300">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-full text-xs font-medium shadow hover:bg-red-700 transition-all duration-300">
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
                                class="px-4 py-4 text-center text-gray-400 italic">
                                No students found for {{ $tab }} in {{ $schoolYear ?: 'all school years' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $studentsByYear[$tab]->links() }}
        </div>
    </div>
    {{-- 🔼 Your Existing Code Ends Here --}}

</body>
</html>
