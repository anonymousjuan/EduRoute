<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <h2 class="text-2xl md:text-3xl font-bold text-white bg-gradient-to-r from-[#800000] to-[#F7CDE9] px-4 py-2 rounded-xl shadow-lg animate-pop">
                📘 {{ $subject->subjectTitle }} ({{ $subject->subjectCode }})
            </h2>
            <a href="{{ url()->previous() }}" 
               class="btn-modern shadow hover:scale-105 transition transform duration-200">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4 space-y-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-xl shadow-md animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        {{-- Students grouped by School Year --}}
        @foreach($students as $schoolYear => $studentsBySY)
            <div class="space-y-4 glass-card p-4">
                <h3 class="text-lg font-semibold text-gray-700 bg-white/40 backdrop-blur-md px-4 py-2 rounded-xl shadow-inner animate-pop">
                    {{ $schoolYear }}
                </h3>

                <div class="overflow-x-auto glass-card p-2">
                    <table class="min-w-full divide-y divide-gray-200 text-sm table-modern">
                        <thead class="bg-gradient-to-r from-[#A45D83]/80 to-[#F7CDE9]/80 text-white uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Student ID</th>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-center">Final Grade</th>
                                <th class="px-4 py-3 text-center">Retake Grade</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 divide-y divide-gray-200">
                            @foreach($studentsBySY as $student)
                                <tr class="hover:bg-[#F7CDE9]/30 transition duration-300 cursor-pointer">
                                    <td class="px-4 py-2 font-medium text-[#800000] hover:underline">{{ $student->studentID }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $student->lastName }}, {{ $student->firstName }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <form id="grade-form-{{ $student->id }}" action="{{ route('faculty.subject.update', $subject->subjectCode) }}" method="POST" class="flex justify-center">
                                            @csrf
                                            <input type="text" name="grades[{{ $student->id }}]" value="{{ $student->Final_Rating }}"
                                                   class="glass-input w-16 text-center" />
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-center text-gray-500 font-medium">{{ $student->Retake_Grade ?? '-' }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="submit" form="grade-form-{{ $student->id }}" 
                                                class="btn-modern shadow hover:scale-105 transition transform duration-200">
                                            Save
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>

    <style>
        /* =========================
           Glassmorphic Card
           ========================= */
        .glass-card {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        /* =========================
           Buttons
           ========================= */
        .btn-modern {
            background: linear-gradient(90deg, #A45D83, #F7CDE9);
            color: #fff;
            font-weight: 600;
            border-radius: 1rem;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(164, 93, 131, 0.4);
        }
        .btn-modern:hover {
            background: linear-gradient(90deg, #F7CDE9, #A45D83);
            color: #800000;
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(164, 93, 131, 0.5);
        }

        /* =========================
           Inputs
           ========================= */
        .glass-input {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            border-color: #A45D83;
            box-shadow: 0 0 8px rgba(164, 93, 131, 0.5);
            outline: none;
            backdrop-filter: blur(12px);
        }

        /* =========================
           Table
           ========================= */
        .table-modern th {
            background: linear-gradient(90deg, #A45D83, #F7CDE9);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table-modern td {
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .table-modern tr:hover td {
            background: rgba(247, 205, 233, 0.25);
            transform: scale(1.01);
        }

        /* =========================
           Animations
           ========================= */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease forwards;
        }

        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.95); }
            80% { transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-pop {
            animation: popIn 0.4s ease forwards;
        }
    </style>
</x-app-layout>
