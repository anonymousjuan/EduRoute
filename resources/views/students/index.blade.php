<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            🎓 Students List
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                @livewire('student-search')
            </div>
        </div>
    </div>
</x-app-layout>
