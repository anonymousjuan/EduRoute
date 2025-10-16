<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 flex items-center gap-2">
            📘 Prospectus - <span class="text-[#A45D83]">{{ $student->lastName }}, {{ $student->firstName }}</span>
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Student Info Card --}}
            <div class="bg-white/60 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-6 hover:scale-[1.02] transform transition duration-300">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center gap-2">👤 Student Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <p><strong>ID:</strong> <span class="text-[#800000]">{{ $student->studentID }}</span></p>
                    <p><strong>Name:</strong> {{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}</p>
                    <p><strong>Course:</strong> {{ $student->courseTitle }}</p>
                    <p><strong>Year Level:</strong> {{ $student->yearLevel }}</p>
                    <p><strong>School Year:</strong> {{ $student->schoolYearTitle }}</p>
                </div>
            </div>

            {{-- Prospectus Table Card --}}
            <div class="bg-white/60 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-6 hover:scale-[1.02] transform transition duration-300">
                <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center gap-2">📑 Prospectus</h3>
                <p class="text-sm text-gray-500 mb-4">
                    1st Semester, AY 2025-2026
                </p>

                <div class="overflow-x-auto rounded-2xl shadow-inner border border-gray-200 bg-white/50 backdrop-blur-sm">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gradient-to-r from-[#A45D83]/70 to-[#F7CDE9]/70 text-white text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2 border">Subject Code</th>
                                <th class="px-4 py-2 border">Description</th>
                                <th class="px-4 py-2 border">Units</th>
                                <th class="px-4 py-2 border">Grade</th>
                                <th class="px-4 py-2 border">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-[#F7CDE9]/30 transition duration-200 cursor-pointer">
                                <td class="px-4 py-2 border font-medium text-[#800000]">ENG101</td>
                                <td class="px-4 py-2 border">English Communication 1</td>
                                <td class="px-4 py-2 border">3</td>
                                <td class="px-4 py-2 border">-</td>
                                <td class="px-4 py-2 border"><span class="text-yellow-600 font-semibold">Pending</span></td>
                            </tr>
                            <tr class="hover:bg-[#F7CDE9]/30 transition duration-200 cursor-pointer">
                                <td class="px-4 py-2 border font-medium text-[#800000]">PSY201</td>
                                <td class="px-4 py-2 border">General Psychology</td>
                                <td class="px-4 py-2 border">3</td>
                                <td class="px-4 py-2 border">-</td>
                                <td class="px-4 py-2 border"><span class="text-yellow-600 font-semibold">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Hover effects for rows */
        table tbody tr:hover td {
            background-color: rgba(247, 205, 233, 0.2);
            transition: background-color 0.3s ease;
        }

        /* Smooth card scaling */
        .backdrop-blur-xl:hover {
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
</x-app-layout>
