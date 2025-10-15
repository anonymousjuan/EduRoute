{{-- Faculty & Subject Modals --}}
<div>
    {{-- Add New Faculty Modal --}}
    <div x-data="{ open: @entangle('showFacultyModal') }">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-semibold mb-4">➕ Add New Faculty</h2>
                <input type="text" wire:model.defer="newFacultyName" placeholder="Faculty Name" 
                       class="w-full border rounded px-3 py-2 mb-4">
                <div class="flex justify-end gap-2">
                    <button @click="open = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button wire:click="addFaculty" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add New Subject Modal --}}
    <div x-data="{ open: @entangle('showSubjectModal') }">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-semibold mb-4">➕ Add New Subject</h2>
                <input type="text" wire:model.defer="newSubject.code" placeholder="Subject Code" 
                       class="w-full border rounded px-3 py-2 mb-2">
                <input type="text" wire:model.defer="newSubject.title" placeholder="Subject Title" 
                       class="w-full border rounded px-3 py-2 mb-2">
                <input type="text" wire:model.defer="newSubject.units" placeholder="Units" 
                       class="w-full border rounded px-3 py-2 mb-4">
                <div class="flex justify-end gap-2">
                    <button @click="open = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button wire:click="addSubject" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add New Student Modal --}}
    <div x-data="{ open: @entangle('showStudentModal') }">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-semibold mb-4">➕ Add New Student</h2>
                <input type="text" wire:model.defer="newStudent.studentId" placeholder="Student ID" 
                       class="w-full border rounded px-3 py-2 mb-2">
                <input type="text" wire:model.defer="newStudent.firstName" placeholder="First Name" 
                       class="w-full border rounded px-3 py-2 mb-2">
                <input type="text" wire:model.defer="newStudent.lastName" placeholder="Last Name" 
                       class="w-full border rounded px-3 py-2 mb-4">
                <div class="flex justify-end gap-2">
                    <button @click="open = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button wire:click="addStudent" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
