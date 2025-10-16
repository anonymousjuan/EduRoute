<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 flex items-center gap-2">
            🎯 Assign Subjects to <span class="text-[#A45D83]">{{ $student->firstName }} {{ $student->lastName }}</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Glass Card --}}
            <div class="bg-white/60 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 transition-transform transform hover:scale-105 duration-300">

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6 shadow-inner">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('students.assign', $student->id) }}" method="POST">
                    @csrf
                    <div class="space-y-6">

                        {{-- Title --}}
                        <h3 class="text-lg font-semibold text-gray-700">Available Subjects</h3>

                        {{-- Subjects List --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($subjects as $subject)
                                <label class="flex items-center gap-3 p-3 rounded-2xl bg-white/40 backdrop-blur-sm hover:bg-[#F7CDE9]/40 transition duration-300 cursor-pointer border border-transparent hover:border-[#A45D83]">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                        {{ in_array($subject->id, $assignedSubjects) ? 'checked' : '' }} 
                                        class="w-5 h-5 text-[#A45D83] border-gray-300 rounded focus:ring-2 focus:ring-[#800000] transition duration-200">
                                    <span class="text-gray-700 font-medium">
                                        {{ $subject->subjectTitle }} 
                                        <span class="text-xs text-gray-400">({{ $subject->subjectCode }})</span>
                                    </span>
                                </label>
                            @empty
                                <p class="text-gray-400 italic col-span-full">No subjects available.</p>
                            @endforelse
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-wrap gap-4 mt-4">
                            <button type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-[#A45D83] to-[#F7CDE9] text-white font-semibold rounded-2xl shadow-lg hover:scale-105 transform transition duration-300">
                                Save Assignments
                            </button>
                            <a href="{{ route('students.index') }}"
                               class="px-6 py-2 bg-gray-400 text-white font-semibold rounded-2xl shadow hover:bg-gray-500 transition duration-300">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <style>
        /* Custom hover animations */
        input[type="checkbox"]:checked {
            accent-color: #A45D83;
        }

        /* Smooth hover effect for card */
        label {
            transition: all 0.3s ease;
        }

        label:hover {
            transform: translateY(-2px);
        }
    </style>
</x-app-layout>
