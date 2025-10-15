<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Add New Student
        </h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto bg-white p-6 rounded shadow">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('student_grades.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label>Student ID</label>
                <input type="text" name="studentID" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label>First Name</label>
                <input type="text" name="firstName" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label>Last Name</label>
                <input type="text" name="lastName" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label>Middle Name</label>
                <input type="text" name="middleName" class="w-full border-gray-300 rounded-lg">
            </div>

            <div class="mb-4">
                <label>Suffix</label>
                <input type="text" name="suffix" class="w-full border-gray-300 rounded-lg">
            </div>

            <div class="mb-4">
                <label>Gender</label>
                <select name="gender" class="w-full border-gray-300 rounded-lg" required>
                    <option value="">-- Select Gender --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="mb-4">
                <label>School Year</label>
                <input type="text" name="schoolYearTitle" class="w-full border-gray-300 rounded-lg" placeholder="e.g., 1st year 2025–2026" required>
            </div>

            <div class="mb-4">
                <label>Course</label>
                <select name="courseID" class="w-full border-gray-300 rounded-lg" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->courseTitle }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Course Title</label>
                <input type="text" name="courseTitle" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label>Year Level</label>
                <select name="yearLevel" class="w-full border-gray-300 rounded-lg" required>
                    <option value="">-- Select Year Level --</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
                <label>Faculty</label>
                <select name="faculty_id" class="w-full border-gray-300 rounded-lg" required>
                    <option value="">-- Select Faculty --</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Subject Code</label>
                <input type="text" name="subjectCode" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label>Subject Title</label>
                <input type="text" name="subjectTitle" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label>Units</label>
                <input type="number" name="units" class="w-full border-gray-300 rounded-lg">
            </div>

            <div class="text-center">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    💾 Add Student
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
