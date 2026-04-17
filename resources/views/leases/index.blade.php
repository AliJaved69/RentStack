<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Leases (Contracts)') }}
            </h2>
            <a href="{{ route('leases.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
                + New Lease
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
                                <th class="px-4 py-3 text-center">ID</th>
                                <th class="px-4 py-3">Property</th>
                                <th class="px-4 py-3">Tenant</th>
                                <th class="px-4 py-3">Start Date</th>
                                <th class="px-4 py-3 text-right">Base Rent</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($leases as $lease)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-center text-sm text-gray-500">{{ $lease->id }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $lease->property->title }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $lease->tenant->name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $lease->start_date }}</td>
                                    <td class="px-4 py-4 text-right font-semibold text-gray-900">${{ number_format($lease->base_rent, 2) }}</td>
                                    <td class="px-4 py-4 uppercase text-xs">
                                        <span class="px-2 py-1 rounded {{ $lease->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $lease->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center gap-3 text-sm">
                                            @if($lease->status === 'active')
                                                <a href="{{ route('leases.terminate', $lease) }}" class="text-red-600 hover:text-red-900">Terminate</a>
                                            @endif
                                            
                                            @if($lease->e_stamp_file_path)
                                                <a href="{{ asset('storage/' . $lease->e_stamp_file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900">E-Stamp</a>
                                            @else
                                                <span class="text-gray-400 text-xs italic">No Doc</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
