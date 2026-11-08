<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    {{ __('Manage Tenants') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Register tenants, view identity records, and manage agreements</p>
            </div>
            <a href="{{ route('tenants.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Tenant
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <!-- Mobile Card View -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse($tenants as $tenant)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-slate-900">{{ $tenant->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $tenant->phone }}</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">
                                #{{ $tenant->id }}
                            </span>
                        </div>

                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 flex justify-between">
                            <span class="font-medium text-slate-500">CNIC / ID:</span>
                            <span class="font-bold text-slate-800">{{ $tenant->cnic_or_id }}</span>
                        </div>

                        <div class="pt-2 flex items-center justify-between border-t border-slate-100 text-xs font-semibold">
                            <a href="{{ route('tenants.show', $tenant) }}" class="text-indigo-600 hover:text-indigo-800">
                                View Details & Leases →
                            </a>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('tenants.edit', $tenant) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" onsubmit="return confirm('Are you sure you want to remove this tenant?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-dashed border-slate-200 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800">No Tenants Added Yet</h3>
                        <p class="text-xs text-slate-500 mt-1">Add your tenants so you can assign leases and record rent payments.</p>
                        <a href="{{ route('tenants.create') }}" class="inline-flex mt-4 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-semibold">
                            Add Your First Tenant
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white overflow-hidden shadow-xs rounded-2xl border border-slate-200/80">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Tenant Name</th>
                            <th class="px-6 py-4">Phone Number</th>
                            <th class="px-6 py-4">CNIC / ID</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">#{{ $tenant->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('tenants.show', $tenant) }}" class="font-semibold text-slate-900 hover:text-emerald-600 transition">
                                            {{ $tenant->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $tenant->phone }}</td>
                                <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ $tenant->cnic_or_id }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('tenants.show', $tenant) }}" class="px-2.5 py-1 text-xs font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition">
                                            Details
                                        </a>
                                        <a href="{{ route('tenants.edit', $tenant) }}" class="px-2.5 py-1 text-xs font-medium rounded-lg text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" onsubmit="return confirm('Are you sure you want to delete this tenant?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    No tenants found. Click "Add New Tenant" above to add your first tenant.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
