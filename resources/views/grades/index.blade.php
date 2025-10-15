<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📑 Grades Management
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Show Lock/Unlock buttons for Program Head --}}
        @if(Auth::user()->role === 'programhead')
            <div class="flex space-x-4 mb-4">
                <form method="POST" action="{{ route('grades.lockAll') }}">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-200">
                        🔒 Lock All Grades
                    </button>
                </form>
                <form method="POST" action="{{ route('grades.unlockAll') }}">
                    @csrf
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-200">
                        🔓 Unlock All Grades
                    </button>
                </form>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Grades List --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="font-semibold text-lg mb-4">📋 Grades List</h3>

            {{-- 🔍 Search --}}
            <form method="GET" action="{{ route('grades.index') }}" class="mb-4 flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="🔎 Search by ID, Name, or Course..." 
                       class="flex-grow px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-400 text-sm">
            </form>

            {{-- 🎓 Year Level Filter Buttons --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach ([1, 2, 3, 4] as $level)
                    <a href="{{ route('grades.index', array_merge(request()->query(), ['yearLevel' => $level])) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold
                              {{ request('yearLevel') == $level 
                                    ? 'bg-indigo-600 text-white shadow-md' 
                                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}
                              transition duration-200">
                        Year {{ $level }}
                    </a>
                @endforeach

                {{-- Show All Button --}}
                <a href="{{ route('grades.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold 
                          {{ request('yearLevel') ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-indigo-600 text-white shadow-md' }}
                          transition duration-200">
                    All
                </a>
            </div>

            {{-- 📊 Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">Student ID</th>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Course</th>
                            <th class="px-4 py-2 border">Year Level</th>
                            <th class="px-4 py-2 border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $student)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border">{{ $student->studentID }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}
                                </td>
                                <td class="px-4 py-2 border">{{ $student->courseTitle }}</td>
                                <td class="px-4 py-2 border">{{ $student->yearLevel }}</td>
                                <td class="px-4 py-2 border text-center">

                                    <a href="{{ route('grades.students', $student->studentID) }}" 
                                       class="inline-block bg-gradient-to-r from-indigo-500 to-purple-600 
                                              hover:from-indigo-600 hover:to-purple-700 
                                              text-white px-4 py-1.5 rounded-lg shadow-md 
                                              transition duration-200 transform hover:scale-105">
                                        🎓 View Transcript
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $grades->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
