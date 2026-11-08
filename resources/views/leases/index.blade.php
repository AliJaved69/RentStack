<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    {{ __('Lease Contracts & Agreements') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage tenant leases, monthly base rents, and security deposits</p>
            </div>
            <a href="{{ route('leases.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-semibold rounded-xl shadow-xs transition active:scale-95">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Create New Lease
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            
            <!-- Mobile Cards View -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse($leases as $lease)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                    {{ $lease->property->title }}
                                </span>
                                <h3 class="font-bold text-base text-slate-900">
                                    {{ $lease->unit ? $lease->unit->name : 'Full Property' }}
                                </h3>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $lease->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tenant:</span>
                                <span class="font-bold text-slate-800">{{ $lease->tenant->name }} ({{ $lease->tenant->phone }})</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Monthly Rent:</span>
                                <span class="font-bold text-emerald-700">Rs. {{ number_format($lease->base_rent, 0) }}/mo</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Security Deposit:</span>
                                <span class="font-semibold text-slate-700">Rs. {{ number_format($lease->security_deposit_paid, 0) }} / {{ number_format($lease->security_deposit_expected, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Start Date:</span>
                                <span class="text-slate-700">{{ $lease->start_date ? $lease->start_date->format('d M, Y') : '-' }}</span>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-between border-t border-slate-100 text-xs font-semibold">
                            <div>
                                @if($lease->e_stamp_file_path)
                                    <a href="{{ asset('storage/' . $lease->e_stamp_file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold">
                                        📄 View Contract
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">No Document</span>
                                @endif
                            </div>

                            @if($lease->status === 'active')
                                <a href="{{ route('leases.terminate', $lease) }}" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold">
                                    Terminate Lease
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-dashed border-slate-200 text-center">
                        <h4 class="font-bold text-slate-800">No Leases Created Yet</h4>
                        <p class="text-xs text-slate-500 mt-1">Assign your tenants to portions or shops to generate monthly rent invoices.</p>
                        <a href="{{ route('leases.create') }}" class="inline-flex mt-4 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">
                            Create First Lease
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white overflow-hidden shadow-xs rounded-2xl border border-slate-200/80">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Portion / Shop</th>
                            <th class="px-6 py-4">Tenant</th>
                            <th class="px-6 py-4 text-right">Monthly Rent</th>
                            <th class="px-6 py-4">Security / Advance</th>
                            <th class="px-6 py-4">Start Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leases as $lease)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">
                                        {{ $lease->unit ? $lease->unit->name : 'Full Property' }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $lease->property->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $lease->tenant->name }}</div>
                                    <div class="text-xs font-mono text-slate-400">{{ $lease->tenant->phone }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-slate-900">
                                    Rs. {{ number_format($lease->base_rent, 0) }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                    Rs. {{ number_format($lease->security_deposit_paid, 0) }} / {{ number_format($lease->security_deposit_expected, 0) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $lease->start_date ? $lease->start_date->format('d M, Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $lease->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($lease->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        @if($lease->e_stamp_file_path)
                                            <a href="{{ asset('storage/' . $lease->e_stamp_file_path) }}" target="_blank" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                                Contract
                                            </a>
                                        @endif

                                        @if($lease->status === 'active')
                                            <a href="{{ route('leases.terminate', $lease) }}" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100">
                                                Terminate
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    No lease agreements recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $leases->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
