<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ $tenant->name }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Tenant Details and Lease History</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tenants.edit', $tenant) }}" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition">
                    Edit Tenant
                </a>
                <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Full Name</span>
                        <span class="text-base font-bold text-slate-900 mt-1 block">{{ $tenant->name }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Phone</span>
                        <span class="text-base font-bold text-slate-900 mt-1 block">{{ $tenant->phone }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">CNIC / ID Number</span>
                        <span class="text-base font-bold text-slate-900 mt-1 block">{{ $tenant->cnic_or_id }}</span>
                    </div>
                </div>
            </div>

            <!-- Lease History -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6 sm:p-8">
                <h3 class="font-bold text-lg text-slate-900 mb-4">Lease Agreements</h3>
                @if($tenant->leases->isEmpty())
                    <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-xl">
                        No active or past lease agreements found for this tenant.
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($tenant->leases as $lease)
                            <div class="py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    <div class="font-bold text-slate-900">{{ $lease->property->title ?? 'Property #' . $lease->property_id }}</div>
                                    <div class="text-xs text-slate-500">From {{ $lease->start_date }} {{ $lease->end_date ? 'to ' . $lease->end_date : '(Active)' }}</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-emerald-700">${{ number_format($lease->base_rent, 2) }}/mo</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $lease->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ ucfirst($lease->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

