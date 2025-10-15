<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📘 Prospectus - {{ $student->lastName }}, {{ $student->firstName }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Student Info --}}
            <div class="bg-white shadow rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-2">👤 Student Information</h3>
                <p><strong>ID:</strong> {{ $student->studentID }}</p>
                <p><strong>Name:</strong> {{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}</p>
                <p><strong>Course:</strong> {{ $student->courseTitle }}</p>
                <p><strong>Year Level:</strong> {{ $student->yearLevel }}</p>
                <p><strong>School Year:</strong> {{ $student->schoolYearTitle }}</p>
            </div>

            {{-- Prospectus --}}
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold">📑 Prospectus</h3>
                <p class="text-sm text-gray-600 mb-3">
                    {{-- Static version --}}
                    1st Semester, AY 2025-2026
                    {{-- Or if dynamic --}}
                    {{-- {{ $semester->name }} Semester, AY {{ $student->schoolYearTitle }} --}}
                </p>

                <table class="w-full text-sm border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1">Subject Code</th>
                            <th class="border px-2 py-1">Description</th>
                            <th class="border px-2 py-1">Units</th>
                            <th class="border px-2 py-1">Grade</th>
                            <th class="border px-2 py-1">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Later: loop real prospectus data --}}
                        <tr>
                            <td class="border px-2 py-1">ENG101</td>
                            <td class="border px-2 py-1">English Communication 1</td>
                            <td class="border px-2 py-1">3</td>
                            <td class="border px-2 py-1">-</td>
                            <td class="border px-2 py-1">Pending</td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">PSY201</td>
                            <td class="border px-2 py-1">General Psychology</td>
                            <td class="border px-2 py-1">3</td>
                            <td class="border px-2 py-1">-</td>
                            <td class="border px-2 py-1">Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
