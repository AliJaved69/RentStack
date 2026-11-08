<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Properties & Plazas') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Select any property to view its portions, shops, and collect rent</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <form method="POST" action="{{ route('properties.export-bundle') }}" class="inline">
                    @csrf
                    <button type="submit" title="Sync current database to mobile APK & Supabase" class="inline-flex items-center justify-center px-3.5 py-2.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition shadow-xs active:scale-95">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Sync Data
                    </button>
                </form>
                <a href="{{ route('properties.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-semibold rounded-xl shadow-xs transition active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Property / Building
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Property Cards Grid (WITH PICTURES) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($properties as $property)
                    @php
                        $unitsCount = $property->units->count();
                        $occupiedCount = $property->units->where('status', 'occupied')->count();
                        $vacantCount = $property->units->where('status', 'vacant')->count();
                        $totalExpectedRent = $property->units->sum('default_rent');
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between group">
                        
                        <!-- Clickable Link Area -->
                        <a href="{{ route('properties.show', $property) }}" class="block">
                            <!-- Property Image -->
                            <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                                @if($property->image_url)
                                    <img src="{{ $property->image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <!-- Fallback Modern Building Card Illustration -->
                                    <div class="w-full h-full bg-gradient-to-tr from-slate-800 to-slate-700 flex flex-col items-center justify-center text-white p-4">
                                        <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-emerald-400 mb-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-300 tracking-wide uppercase">Rental Building</span>
                                    </div>
                                @endif

                                <!-- Units Count Badge on top of picture -->
                                <div class="absolute top-3 right-3 flex items-center gap-1.5 bg-slate-900/80 backdrop-blur-md text-white px-3 py-1 rounded-full text-xs font-semibold shadow-xs">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    <span>{{ $unitsCount }} {{ Str::plural('Portion/Shop', $unitsCount) }}</span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 space-y-3">
                                <div>
                                    <h3 class="font-extrabold text-lg text-slate-900 group-hover:text-emerald-700 transition">
                                        {{ $property->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 flex items-center mt-1">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $property->address }}</span>
                                    </p>
                                </div>

                                <!-- Portions & Shops Summary -->
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-xs">
                                    <div class="bg-emerald-50/70 p-2.5 rounded-xl border border-emerald-100">
                                        <span class="text-emerald-700 font-semibold block">Occupied</span>
                                        <span class="text-base font-bold text-emerald-950">{{ $occupiedCount }} Units</span>
                                    </div>
                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <span class="text-slate-500 font-semibold block">Vacant</span>
                                        <span class="text-base font-bold text-slate-800">{{ $vacantCount }} Units</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card Action Footer -->
                        <div class="px-5 pb-5 pt-2 flex items-center justify-between border-t border-slate-100/80 bg-slate-50/40">
                            <a href="{{ route('properties.show', $property) }}" class="inline-flex items-center text-xs font-bold text-emerald-700 hover:text-emerald-800 transition">
                                <span>View Portions & Collect Rent</span>
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('properties.edit', $property) }}" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100" title="Edit property details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-2xl border border-dashed border-slate-300 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-900">No Properties Added Yet</h3>
                            <p class="text-sm text-slate-500 max-w-md mx-auto mt-1">
                                Add your first building or plaza (e.g. Main Market Plaza) with a photo, then add its portions, shops, and tenants.
                            </p>
                        </div>
                        <a href="{{ route('properties.create') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-xs">
                            Add Your First Property
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
