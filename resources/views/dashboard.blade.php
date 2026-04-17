<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                {{ $isSuperAdmin ? 'Super Admin Dashboard' : 'Owner Dashboard' }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @if($isSuperAdmin)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Total Rent (Current Month)</div>
                        <div class="text-3xl font-bold text-green-600">${{ number_format($stats['total_collected_month'], 2) }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Total Pending Rent</div>
                        <div class="text-3xl font-bold text-red-600">${{ number_format($stats['total_pending'], 2) }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Borrowed by Family</div>
                        <div class="text-3xl font-bold text-orange-600">${{ number_format($stats['total_borrowed'], 2) }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Total Cash on Hand</div>
                        <div class="text-3xl font-bold text-blue-600">${{ number_format($stats['cash_on_hand'], 2) }}</div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">My Properties</div>
                        <div class="text-3xl font-bold text-indigo-600">{{ $stats['properties_count'] }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Vacant Properties</div>
                        <div class="text-3xl font-bold text-yellow-600">{{ $stats['vacant_properties'] }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Total Collected Rent</div>
                        <div class="text-3xl font-bold text-green-600">${{ number_format($stats['total_collected'], 2) }}</div>
                    </div>
                @endif
            </div>

            <div class="mt-12 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('properties.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">Manage Properties</a>
                    <a href="{{ route('tenants.index') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">Manage Tenants</a>
                    <a href="{{ route('leases.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">Active Leases</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
