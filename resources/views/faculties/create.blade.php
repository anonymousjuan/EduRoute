    @extends('layouts.app')

    @section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">

        {{-- Form Start --}}
        <form id="add-faculty-form" method="POST" action="{{ route('faculties.store') }}">
            @csrf

            {{-- Full Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="name" 
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" 
                    required>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" 
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" 
                    required>
            </div>

            {{-- Department --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Department</label>
                <input type="text" name="department" 
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            {{-- Year Level --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Select Year Level (for students)</label>
                <select name="year_level" 
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" 
                        required>
                    <option value="">-- Choose Year Level --</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>

            {{-- Subjects --}}
            <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Select Subjects</label>
        <div class="border rounded p-2 h-40 overflow-y-auto">
            @foreach($curriculums as $curr)
                <label class="block mb-1">
                    <input type="checkbox" name="subjects[]" value="{{ $curr->id }}" class="mr-2">
                    {{ $curr->course_no }} - {{ $curr->descriptive_title }} (Y{{ $curr->year_level }})
                </label>
            @endforeach
        </div>
    </div>
            {{-- Bottom Buttons --}}
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('faculties.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                 <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">
        Save Faculty
    </button>
</form>
    </div>

    {{-- Success Modal --}}
    @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 3000)" 
        class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6">
        
        <div class="max-w-sm w-full bg-green-500 text-white rounded-lg shadow-lg pointer-events-auto">
            <div class="p-4 flex items-center justify-between">
                <p class="font-semibold">{{ session('success') }}</p>
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200 font-bold">&times;</button>
            </div>
        </div>
    </div>
    @endif

    @endsection

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endsection
