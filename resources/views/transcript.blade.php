<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            🎓 Offered Subjects
            <span class="text-indigo-600">
                ({{ $student->lastName ?? '' }}, {{ $student->firstName ?? '' }})
            </span>
        </h2>

        {{-- 🎯 Unit Limit --}}
        <div class="flex justify-center mt-4 space-x-2 items-center">
            <label for="unitLimit" class="font-semibold text-gray-700">Allowed Units:</label>
            <input type="number" id="unitLimit" name="unitLimit" min="1" max="30"
                   value="{{ session('unitLimit', 0) }}"
                   class="w-20 border border-gray-400 rounded-md text-center focus:ring focus:ring-indigo-300"
                   placeholder="0">
            <button id="saveUnitLimitBtn"
                    class="px-3 py-2 bg-green-700 text-white rounded-lg shadow hover:bg-green-800 transition">
                💾 Save Limit
            </button>
        </div>

        {{-- ➕ Generate Button --}}
        <div class="flex justify-center mt-3">
            <form id="generateSubjectsForm"
                  action="{{ route('students.generateSubjects', $student->studentID) }}"
                  method="POST">
                @csrf
                <input type="hidden" name="unitLimit" id="hiddenUnitLimit">
                <button type="submit"
                        class="px-4 py-2 bg-[#800000] text-white rounded-lg shadow hover:bg-[#a00000] transition">
                    ➕ Generate Next Semester
                </button>
            </form>
        </div>

        {{-- Message Box --}}
        <div id="generateMessage" class="text-center mt-3 font-semibold hidden"></div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">

                {{-- 🧾 Student Info + Back Button --}}
                <div class="mb-6 border-b pb-3 flex justify-between items-start flex-wrap">
                    <div class="space-y-1">
                        <p><strong>Student ID:</strong> {{ $student->studentID ?? '' }}</p>
                        <p><strong>Name:</strong>
                            {{ $student->lastName ?? '' }},
                            {{ $student->firstName ?? '' }}
                            {{ $student->middleName ?? '' }}
                            {{ $student->suffix ?? '' }}
                        </p>
                        <p><strong>Course:</strong> {{ $student->courseTitle ?? '' }}</p>
                        <p><strong>Year Level:</strong> {{ $student->yearLevel ?? '' }}</p>
                    </div>

                    <div class="mt-2 sm:mt-0">
                        <a href="{{ route('students.index') }}"
                           class="px-4 py-2 bg-[#800000] text-white rounded-lg shadow hover:bg-[#a00000] transition">
                            ← Back to Students
                        </a>
                    </div>
                </div>

                {{-- 📊 Group Grades --}}
                @php
                    $gradesGrouped = [];

                    foreach ($grades as $grade) {
                        $yearLevel = $grade->yearLevel ?? 'Unknown Year';
                        $semesterTitle = $grade->schoolYearTitle ?? 'Unknown Semester';
                        $gradesGrouped[$yearLevel][$semesterTitle][] = $grade;
                    }

                    uksort($gradesGrouped, function($a, $b) {
                        preg_match('/\d+/', $a, $ma);
                        preg_match('/\d+/', $b, $mb);
                        $na = intval($ma[0] ?? 0);
                        $nb = intval($mb[0] ?? 0);
                        return $na <=> $nb;
                    });
                @endphp

                <style>
                    .sem-row { display: flex; flex-wrap: wrap; gap: 1rem; align-items: stretch; }
                    .sem-card {
                        flex: 1 1 420px;
                        min-width: 340px;
                        background: #fdfdfd;
                        border: 1px solid #d1d5db;
                        border-radius: 12px;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        display: flex;
                        flex-direction: column;
                        transition: transform 0.2s;
                    }
                    .sem-card.highlight { border-color: #800000; background: #fff0f0; }
                    .sem-card:hover { transform: scale(1.01); }
                    .sem-card h3 {
                        background: #800000;
                        color: white;
                        text-align: center;
                        padding: 0.5rem;
                        border-radius: 12px 12px 0 0;
                        font-size: 1rem;
                        font-weight: 600;
                    }
                    .sem-table-wrap { overflow-x: auto; flex: 1; }
                    .sem-table { width: 100%; border-collapse: collapse; }
                    .sem-table th, .sem-table td {
                        border: 1px solid #e5e7eb;
                        padding: 0.5rem 0.75rem;
                        text-align: left;
                        font-size: 0.875rem;
                    }
                    .sem-table thead { background: #f3f4f6; font-weight: 600; }
                    .total-units { background: #f3f4f6; font-weight: 600; text-align: right; }
                </style>

                {{-- 📚 Display Grouped Grades --}}
                @forelse($gradesGrouped as $yearLevel => $semesters)
                    <h2 class="text-lg font-semibold mt-6 mb-2 text-[#800000]">{{ $yearLevel }}</h2>

                    @php
                        $sortedSemesters = collect($semesters)
                            ->sortKeysUsing(function ($aKey, $bKey) {
                                preg_match('/(\d{4})-(\d{4})/', $aKey, $ma);
                                preg_match('/(\d{4})-(\d{4})/', $bKey, $mb);
                                $ayA = intval($ma[1] ?? 0);
                                $ayB = intval($mb[1] ?? 0);
                                $semA = str_contains($aKey, '1st') ? 1 : 2;
                                $semB = str_contains($bKey, '1st') ? 1 : 2;

                                return [$ayA, $semA] <=> [$ayB, $semB];
                            });

                        $latestSem = collect($sortedSemesters)->keys()->last();
                    @endphp

                    <div class="sem-row mb-6">
                        @foreach($sortedSemesters as $semTitle => $gradeList)
                            @php
                                $totalUnits = collect($gradeList)->sum('units');
                                $isHighlight = ($semTitle === $latestSem);
                            @endphp
                            <div class="sem-card {{ $isHighlight ? 'highlight' : '' }}">
                                <h3>{{ $semTitle }}</h3>
                                <div class="sem-table-wrap">
                                    <table class="sem-table">
                                        <thead>
                                            <tr>
                                                <th>Subject Code</th>
                                                <th>Descriptive Title</th>
                                                <th>Units</th>
                                                <th>Final Grade</th>
                                                <th>Retake Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($gradeList as $subj)
                                                <tr>
                                                    <td>{{ $subj->subjectCode ?? '' }}</td>
                                                    <td>{{ $subj->subjectTitle 
                                                             ?? $subj->descriptiveTitle 
                                                             ?? $subj->descriptive_title 
                                                             ?? '' }}</td>
                                                    <td>{{ $subj->units ?? 0 }}</td>
                                                    <td>{{ $subj->Final_Rating ?? $subj->finalRating ?? '' }}</td>
                                                    <td>{{ $subj->Retake_Grade ?? $subj->retakeGrade ?? '' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-gray-500 py-2">
                                                        No subjects found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            <tr class="total-units">
                                                <td colspan="2">Total Units</td>
                                                <td>{{ $totalUnits }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <p class="text-center text-gray-600 mt-6">No grades available.</p>
                @endforelse

            </div>
        </div>
    </div>

    {{-- ⚡ AJAX & JS for Generate Button --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('generateSubjectsForm');
            const msgBox = document.getElementById('generateMessage');
            const unitInput = document.getElementById('unitLimit');
            const hiddenUnit = document.getElementById('hiddenUnitLimit');
            const saveBtn = document.getElementById('saveUnitLimitBtn');

            const savedLimit = localStorage.getItem('unitLimit');
            if (savedLimit) {
                unitInput.value = savedLimit;
                hiddenUnit.value = savedLimit;
            }

            saveBtn.addEventListener('click', () => {
                const units = parseInt(unitInput.value);
                if (!units || units < 1) {
                    alert("⚠️ Please enter a valid unit limit.");
                    return;
                }
                localStorage.setItem('unitLimit', units);
                hiddenUnit.value = units;
                msgBox.classList.remove('hidden', 'text-red-600');
                msgBox.classList.add('text-green-600');
                msgBox.textContent = `✅ Unit limit set to ${units} units.`;
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const limit = unitInput.value;
                if (!limit || limit <= 0) {
                    msgBox.classList.remove('hidden');
                    msgBox.classList.add('text-red-600');
                    msgBox.textContent = "⚠️ Please set a valid unit limit first.";
                    return;
                }

                hiddenUnit.value = limit;
                msgBox.classList.remove('hidden', 'text-green-600', 'text-red-600');
                msgBox.textContent = "⏳ Generating next semester subjects...";

                try {
                    const response = await fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();
                    msgBox.classList.remove('text-green-600', 'text-red-600');

                    if (data.success) {
                        msgBox.textContent = data.message ?? "✅ Subjects generated successfully!";
                        msgBox.classList.add("text-green-600");
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        msgBox.textContent = data.message ?? "⚠️ Cannot generate subjects.";
                        msgBox.classList.add("text-red-600");
                    }
                } catch (err) {
                    console.error(err);
                    msgBox.textContent = "❌ An error occurred. Please try again.";
                    msgBox.classList.add("text-red-600");
                }
            });
        });
    </script>



    {{-- ⚡ AJAX & JS for Generate Button --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('generateSubjectsForm');
            const msgBox = document.getElementById('generateMessage');
            const unitInput = document.getElementById('unitLimit');
            const hiddenUnit = document.getElementById('hiddenUnitLimit');
            const saveBtn = document.getElementById('saveUnitLimitBtn');

            const savedLimit = localStorage.getItem('unitLimit');
            if (savedLimit) {
                unitInput.value = savedLimit;
                hiddenUnit.value = savedLimit;
            }

            saveBtn.addEventListener('click', () => {
                const units = parseInt(unitInput.value);
                if (!units || units < 1) {
                    alert("⚠️ Please enter a valid unit limit.");
                    return;
                }
                localStorage.setItem('unitLimit', units);
                hiddenUnit.value = units;
                msgBox.classList.remove('hidden', 'text-red-600');
                msgBox.classList.add('text-green-600');
                msgBox.textContent = `✅ Unit limit set to ${units} units.`;
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const limit = unitInput.value;
                if (!limit || limit <= 0) {
                    msgBox.classList.remove('hidden');
                    msgBox.classList.add('text-red-600');
                    msgBox.textContent = "⚠️ Please set a valid unit limit first.";
                    return;
                }

                hiddenUnit.value = limit;
                msgBox.classList.remove('hidden', 'text-green-600', 'text-red-600');
                msgBox.textContent = "⏳ Generating next semester subjects...";

                try {
                    const response = await fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();
                    msgBox.classList.remove('text-green-600', 'text-red-600');

                    if (data.success) {
                        msgBox.textContent = data.message ?? "✅ Subjects generated successfully!";
                        msgBox.classList.add("text-green-600");
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        msgBox.textContent = data.message ?? "⚠️ Cannot generate subjects.";
                        msgBox.classList.add("text-red-600");
                    }
                } catch (err) {
                    console.error(err);
                    msgBox.textContent = "❌ An error occurred. Please try again.";
                    msgBox.classList.add("text-red-600");
                }
            });
        });
    </script>
</x-app-layout>
