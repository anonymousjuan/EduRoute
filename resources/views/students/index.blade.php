<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
                <span class="text-3xl">🎓</span> Students List
            </h2>
            <!-- Optional: Add a button for adding a student -->
            <a href="{{ route('students.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-maroon-600 to-maroon-800 hover:from-maroon-700 hover:to-maroon-900 text-white font-semibold rounded-lg shadow-md transition transform hover:scale-105">
                <i class="fa-solid fa-user-plus"></i> Add Student
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 transition-transform transform hover:scale-105 hover:shadow-2xl duration-300">

                <!-- Livewire Student Search Component -->
                <div class="space-y-6">
                    @livewire('student-search')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
