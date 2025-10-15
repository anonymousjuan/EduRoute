<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Subjects to {{ $student->firstName }} {{ $student->lastName }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('students.assign', $student->id) }}" method="POST">
                    @csrf
                    <div class="grid gap-4">

                        <h3 class="font-semibold mb-2">Available Subjects</h3>

                        @forelse($subjects as $subject)
                            <div class="flex items-center">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                    {{ in_array($subject->id, $assignedSubjects) ? 'checked' : '' }} 
                                    class="mr-2">
                                <span>{{ $subject->subjectTitle }} ({{ $subject->subjectCode }})</span>
                            </div>
                        @empty
                            <p>No subjects available.</p>
                        @endforelse

                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Assignments</button>
                            <a href="{{ route('students.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
