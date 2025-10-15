<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrolled Students') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-2xl font-bold mb-4">Enrolled Students List</h1>

                    <!-- Success message -->
                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Import Form -->
                    <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <input type="file" name="file" required class="border px-2 py-1 rounded">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Import Students
                            </button>
                        </div>
                    </form>

                    <!-- Students Table -->
                    <table class="table-auto w-full border border-gray-300">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">#</th>
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrolled as $student)
                                <tr>
                                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="border px-4 py-2">{{ $student->name }}</td>
                                    <td class="border px-4 py-2">{{ $student->email }}</td>
                                    <td class="border px-4 py-2">{{ $student->course ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border px-4 py-2 text-center text-gray-500">
                                        No enrolled students found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
