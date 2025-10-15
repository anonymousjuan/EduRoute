<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('curriculum.update', $curriculum->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium">Course No.</label>
                        <input type="text" name="course_no" class="w-full border p-2" value="{{ $curriculum->course_no }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Descriptive Title</label>
                        <input type="text" name="descriptive_title" class="w-full border p-2" value="{{ $curriculum->descriptive_title }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Units</label>
                        <input type="number" name="units" class="w-full border p-2" value="{{ $curriculum->units }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Lecture Hours</label>
                        <input type="number" name="lec" class="w-full border p-2" value="{{ $curriculum->lec }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Lab Hours</label>
                        <input type="number" name="lab" class="w-full border p-2" value="{{ $curriculum->lab }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Prerequisite</label>
                        <input type="text" name="prerequisite" class="w-full border p-2" value="{{ $curriculum->prerequisite }}">
                    </div>

                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-black rounded hover:bg-blue-600">
                        Update
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
