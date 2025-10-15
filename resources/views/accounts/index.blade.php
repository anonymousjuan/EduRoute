<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            👤 Accounts Management
        </h2>
    </x-slot>

    <div class="p-6 max-w-6xl mx-auto">
        {{-- ✅ Add Account Button --}}
        <a href="{{ route('accounts.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-black text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
            + Add Account
        </a>

        {{-- ✅ Accounts Table --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Role</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($accounts as $account)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $account->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $account->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($account->role === 'admin') bg-red-100 text-red-700
                                    @elseif($account->role === 'dean') bg-blue-100 text-blue-700
                                    @elseif($account->role === 'program_head') bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700
                                    @endif
                                ">
                                    {{ ucwords(str_replace('_', ' ', $account->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <form action="{{ route('accounts.toggle', $account) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-full shadow
                                        {{ $account->status ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                        {{ $account->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
