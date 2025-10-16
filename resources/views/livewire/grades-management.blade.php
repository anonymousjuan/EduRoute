<div>
    <style>
        /* Table styling */
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        thead {
            background-color: #f9fafb; /* Tailwind gray-50 */
        }
        th, td {
            padding: 0.375rem 0.5rem; /* py-1 px-2 */
            border: 1px solid #e5e7eb; /* Tailwind gray-200 */
            text-align: left;
            font-size: 0.75rem; /* text-xs */
        }
        tbody tr:hover {
            background-color: #f3f4f6; /* Tailwind gray-100 */
        }
        /* Rounded corners on the table */
        thead tr th:first-child {
            border-top-left-radius: 0.5rem;
        }
        thead tr th:last-child {
            border-top-right-radius: 0.5rem;
        }
        tbody tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
        }
        tbody tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
        }
        /* Tabs styling */
        .tab-button {
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            border-bottom-width: 4px;
            border-bottom-color: transparent;
            transition: color 0.3s, border-color 0.3s;
        }
        .tab-button.active {
            color: #2563eb; /* Tailwind blue-600 */
            border-bottom-color: #2563eb;
        }
        .tab-button:hover:not(.active) {
            color: #2563eb;
        }
        /* Search input and button */
        input[type="text"] {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db; /* Tailwind gray-300 */
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: box-shadow 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5); /* indigo-500 shadow */
            border-color: #6366f1;
        }
        button.clear-btn {
            padding: 0.5rem 0.75rem;
            background-color: #e5e7eb; /* gray-200 */
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
        }
        button.clear-btn:hover {
            background-color: #d1d5db; /* gray-300 */
        }
        /* Actions link styling */
        a.action-link {
            padding: 0.25rem 0.5rem;
            background-color: #4f46e5; /* indigo-600 */
            color: white;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        a.action-link:hover {
            background-color: #4338ca; /* indigo-700 */
        }
    </style>

    {{-- 🔎 Search --}}
    <div class="flex items-center gap-2 mb-4">
        <input type="text" wire:model.debounce.300ms="search" 
               placeholder="Search by ID, First, Last name..."
               class="w-full" />
        <button wire:click="$set('search','')" 
                class="clear-btn">Clear</button>
    </div>

    @if($grades->count())
        <div x-data="{ tab: '{{ $studentsByYear->keys()->first() }}' }">
            {{-- Tabs --}}
            <div class="flex border-b mb-4">
                @foreach ($studentsByYear as $year => $students)
                    <button 
                        @click="tab = '{{ $year }}'"
                        :class="tab === '{{ $year }}' ? 'tab-button active' : 'tab-button'"
                    >
                        {{ $year }}
                    </button>
                @endforeach
            </div>

            {{-- Students Table per Year --}}
            @foreach ($studentsByYear as $year => $students)
                <div x-show="tab === '{{ $year }}'" x-transition>
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Year Level</th>
                                    <th>SY</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->studentID }}</td>
                                        <td>{{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}</td>
                                        <td>{{ $student->courseTitle }}</td>
                                        <td>{{ $student->yearLevel }}</td>
                                        <td>{{ $student->schoolYearTitle }}</td>
                                        <td class="flex space-x-1">
                                            <a href="{{ route('grades.show', $student->studentID) }}"
                                               class="action-link">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-400">
                                            No students found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $grades->links() }}
        </div>
    @else
        <p class="text-gray-500 text-center mt-4">No grades available.</p>
    @endif
</div>
