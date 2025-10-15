<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instructor Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen p-6">
            <nav class="space-y-4">
                <a href="{{ route('grades.index') }}" 
                   class="block px-4 py-2 rounded-lg hover:bg-indigo-100">
                    🎓 Grades
                </a>

                <a href="{{ route('enrolled.index') }}" 
                   class="block px-4 py-2 rounded-lg hover:bg-indigo-100">
                    📝 Enrolled Students
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-50">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Welcome, Instructor!</h3>
                <p class="text-gray-700">Select a menu from the sidebar to manage your students and grades.</p>
            </div>
        </main>
    </div>
</x-app-layout>
