<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Grades Management</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-bold text-2xl text-gray-800 tracking-wide">
                📑 Grades Management
            </h2>
        </x-slot>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 🔒 Lock/Unlock Buttons --}}
            @if(Auth::user()->role === 'programhead')
                <div class="flex flex-wrap gap-4 mb-4">
                    <form method="POST" action="{{ route('grades.lockAll') }}">
                        @csrf
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                            🔒 Lock All Grades
                        </button>
                    </form>
                    <form method="POST" action="{{ route('grades.unlockAll') }}">
                        @csrf
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                            🔓 Unlock All Grades
                        </button>
                    </form>
                </div>
            @endif

            {{-- ✅ Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md flex items-center gap-2">
                    <span class="text-xl">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- 📋 Grades Table Card --}}
            <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl p-6 border border-gray-200">

                {{-- 🔍 Search --}}
                <form method="GET" action="{{ route('grades.index') }}" class="mb-5 flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="🔎 Search by ID, Name, or Course..."
                           class="flex-1 px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-300 text-sm transition duration-200">
                </form>

                {{-- 🎓 Year Level Filter --}}
                <div class="flex flex-wrap gap-2 mb-5">
                    @foreach ([1, 2, 3, 4] as $level)
                        <a href="{{ route('grades.index', array_merge(request()->query(), ['yearLevel' => $level])) }}"
                           class="px-4 py-2 rounded-xl text-sm font-semibold transition duration-200
                                  {{ request('yearLevel') == $level
                                    ? 'bg-indigo-600 text-white shadow-lg'
                                    : 'bg-gray-100 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700' }}">
                            Year {{ $level }}
                        </a>
                    @endforeach
                    <a href="{{ route('grades.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition duration-200
                              {{ request('yearLevel') ? 'bg-gray-100 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700' 
                                                    : 'bg-indigo-600 text-white shadow-lg' }}">
                        All
                    </a>
                </div>

                {{-- 📊 Table --}}
                <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-[#800000] text-white uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-2 border">Student ID</th>
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Course</th>
                                <th class="px-4 py-2 border">Year Level</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($grades as $student)
                                <tr class="hover:bg-[#F7CDE9]/30 transition duration-200">
                                    <td class="px-4 py-2 border font-medium text-[#800000]">{{ $student->studentID }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}
                                    </td>
                                    <td class="px-4 py-2 border">{{ $student->courseTitle }}</td>
                                    <td class="px-4 py-2 border">{{ $student->yearLevel }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        <a href="{{ route('grades.students', $student->studentID) }}"
                                           class="inline-block bg-gradient-to-r from-indigo-500 to-purple-600 
                                                  hover:from-indigo-600 hover:to-purple-700
                                                  text-white px-4 py-2 rounded-xl shadow-md
                                                  transition duration-200 transform hover:scale-105">
                                            🎓 View Transcript
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-400 italic">No students found.</td>
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

</body>
</html>
