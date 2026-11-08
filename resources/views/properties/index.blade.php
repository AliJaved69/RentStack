<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Manage Properties') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Add and manage properties to pre-load into your mobile APK</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <form method="POST" action="{{ route('properties.export-bundle') }}" class="inline">
                    @csrf
                    <button type="submit" title="Sync current laptop database to the APK bundle file" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Export to APK Bundle
                    </button>
                </form>
                <a href="{{ route('properties.create') }}" class="inline-flex items-center justify-center bg-indigo-600 text-white px-4 py-2.5 text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Property
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info banner about laptop to mobile sync -->
            <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-xs sm:text-sm text-indigo-900">
                    <span class="font-semibold">Mobile Preload Active:</span> Every property you add here is automatically saved into your mobile APK package. When you build and install the APK on your phone, all properties and leases will already be loaded and ready to use offline!
                </div>
            </div>

            <!-- Property List Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($properties->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/80 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-3.5">Property</th>
                                    <th class="px-6 py-3.5">Owner</th>
                                    <th class="px-6 py-3.5">Address</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($properties as $property)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            {{ $property->title }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $property->owner?->name ?? 'Default' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ $property->address }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $property->status === 'vacant' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $property->status === 'vacant' ? 'bg-emerald-500' : 'bg-amber-500' }} mr-1.5"></span>
                                                {{ ucfirst($property->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <div class="inline-flex items-center gap-3">
                                                <a href="{{ route('properties.edit', $property) }}" class="font-medium text-indigo-600 hover:text-indigo-900 transition">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Are you sure you want to remove this property?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-500 hover:text-red-700 transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards View -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($properties as $property)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-base">{{ $property->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $property->address }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $property->status === 'vacant' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-gray-50 text-xs">
                                    <span class="text-gray-500">Owner: <strong class="text-gray-700">{{ $property->owner?->name ?? 'Default' }}</strong></span>
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('properties.edit', $property) }}" class="font-semibold text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Are you sure you want to remove this property?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold text-red-500 hover:text-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">No properties added yet</h3>
                        <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">
                            Add your rental buildings, apartments, or shops here on your laptop. They will be bundled into the mobile app!
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                + Add First Property
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
