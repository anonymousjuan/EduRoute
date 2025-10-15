<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            ✏️ Edit Student
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white p-6 rounded-xl shadow-lg">

                {{-- ✅ Flash Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('students.update', $student->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Student ID</label>
                            <input type="text" name="studentID" value="{{ old('studentID', $student->studentID) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Course ID</label>
                            <input type="text" name="courseID" value="{{ old('courseID', $student->courseID) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Last Name</label>
                            <input type="text" name="lastName" value="{{ old('lastName', $student->lastName) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">First Name</label>
                            <input type="text" name="firstName" value="{{ old('firstName', $student->firstName) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Middle Name</label>
                            <input type="text" name="middleName" value="{{ old('middleName', $student->middleName) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Suffix</label>
                            <input type="text" name="suffix" value="{{ old('suffix', $student->suffix) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Gender</label>
                            <select name="gender" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                                <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">School Year</label>
                            <input type="text" name="schoolYearTitle" value="{{ old('schoolYearTitle', $student->schoolYearTitle) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Course Title</label>
                            <input type="text" name="courseTitle" value="{{ old('courseTitle', $student->courseTitle) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Year Level</label>
                        <select name="yearLevel" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                            <option value="1st Year" {{ old('yearLevel', $student->yearLevel) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('yearLevel', $student->yearLevel) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('yearLevel', $student->yearLevel) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('yearLevel', $student->yearLevel) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('students.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            ⬅ Back
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            💾 Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
