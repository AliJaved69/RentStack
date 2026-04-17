<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Tenants') }}
            </h2>
            <a href="{{ route('tenants.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
                + Add New Tenant
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 uppercase text-xs font-semibold text-gray-500">
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Phone</th>
                                <th class="px-4 py-3">CNIC / ID</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tenants as $tenant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ $tenant->id }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $tenant->name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $tenant->phone }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $tenant->cnic_or_id }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('tenants.edit', $tenant) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 text-sm">Edit</a>
                                        <a href="{{ route('tenants.show', $tenant) }}" class="text-gray-600 hover:text-gray-900 text-sm">View History</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $tenants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
