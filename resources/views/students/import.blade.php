<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📥 Import Students CSV
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
            <form action="{{ route('students.import.csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="block w-full border p-2 rounded mb-3" required>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                    Upload CSV
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
