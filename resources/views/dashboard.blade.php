<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                    {{ $stats['month_name'] }} Cycle
                </span>
                <h2 class="font-black text-2xl sm:text-3xl text-slate-900 leading-tight mt-1">
                    {{ __('Rent Collection Dashboard') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500">Track monthly rents, arrears, and partial payments across all portions</p>
            </div>

            <!-- Top Quick Actions -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <form method="POST" action="{{ route('rent.rollover') }}" class="inline">
                    @csrf
                    <button type="submit" title="Check & generate current month's rents" class="inline-flex items-center px-3.5 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 shadow-xs transition active:scale-95">
                        <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Month Rents
                    </button>
                </form>

                <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl shadow-xs transition active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    View Properties & Portions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Onboarding Card (shown when database is fresh) -->
            @if($stats['units_count'] == 0)
                <div class="p-6 sm:p-8 bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl text-white shadow-lg space-y-4">
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white uppercase tracking-wider">
                            👋 Welcome to RentStack
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black mt-2">Let's set up your family properties</h3>
                        <p class="text-xs sm:text-sm text-emerald-100 max-w-xl mt-1">
                            Your database is clean and fresh. Follow these 3 simple steps to organize your rental properties and start tracking rents:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div class="bg-white/10 backdrop-blur p-4 rounded-2xl border border-white/20">
                            <div class="w-8 h-8 rounded-xl bg-white text-emerald-700 font-black flex items-center justify-center text-sm mb-2">1</div>
                            <div class="font-bold text-sm">Add Property / Building</div>
                            <p class="text-xs text-emerald-100 mt-1">e.g. Main Market Plaza with a picture.</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur p-4 rounded-2xl border border-white/20">
                            <div class="w-8 h-8 rounded-xl bg-white text-emerald-700 font-black flex items-center justify-center text-sm mb-2">2</div>
                            <div class="font-bold text-sm">Add Portions & Shops</div>
                            <p class="text-xs text-emerald-100 mt-1">Divide into portions, shops, or floors.</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur p-4 rounded-2xl border border-white/20">
                            <div class="w-8 h-8 rounded-xl bg-white text-emerald-700 font-black flex items-center justify-center text-sm mb-2">3</div>
                            <div class="font-bold text-sm">Assign Tenants & Rents</div>
                            <p class="text-xs text-emerald-100 mt-1">Record tenant phone, rent, and 2x advance.</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('properties.create') }}" class="inline-flex items-center px-5 py-3 rounded-2xl bg-white text-emerald-800 font-bold text-sm shadow-md hover:bg-emerald-50 active:scale-95 transition">
                            + Add Your First Property Now →
                        </a>
                    </div>
                </div>
            @endif

            <!-- 4 KEY FINANCIAL METRICS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Collected This Month -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                            Collected
                        </span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-black text-emerald-700">
                            Rs. {{ number_format($stats['month_collected'], 0) }}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Paid in {{ $stats['month_name'] }}</p>
                    </div>
                </div>

                <!-- Remaining / Pending Rent -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-700 bg-rose-50 px-2 py-0.5 rounded">
                            Pending / Arrears
                        </span>
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-black text-rose-700">
                            Rs. {{ number_format($stats['month_remaining'], 0) }}
                        </div>
                        @if($stats['total_arrears'] > 0)
                            <p class="text-xs text-rose-600 font-semibold mt-1">
                                (Includes Rs. {{ number_format($stats['total_arrears'], 0) }} previous arrears)
                            </p>
                        @else
                            <p class="text-xs text-slate-500 mt-1">Uncollected this month</p>
                        @endif
                    </div>
                </div>

                <!-- Total Month Billing -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                            Total Expected
                        </span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">
                            Rs. {{ number_format($stats['month_due'], 0) }}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Base rents + carried arrears</p>
                    </div>
                </div>

                <!-- Portions & Shops Count -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                            Rental Spaces
                        </span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-2xl sm:text-3xl font-black text-indigo-900">
                            {{ $stats['units_count'] }} <span class="text-sm font-semibold text-slate-500">Units</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $stats['occupied_units_count'] }} Rented, {{ $stats['vacant_units_count'] }} Vacant
                        </p>
                    </div>
                </div>

            </div>

            <!-- PENDING RENT ATTENTION LIST (WHICH TENANTS OWE RENT) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
                            <span>Pending Rent Collections ({{ count($pendingInvoices) }})</span>
                            @if(count($pendingInvoices) > 0)
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500">Portions and shops with unpaid or partial rent for {{ $stats['month_name'] }}</p>
                    </div>
                    <a href="{{ route('properties.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">
                        View All Buildings →
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($pendingInvoices as $invoice)
                        @php
                            $lease = $invoice->lease;
                            $unit = $lease ? $lease->unit : null;
                            $tenant = $lease ? $lease->tenant : null;
                            $property = $lease ? $lease->property : null;
                        @endphp
                        <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/60 transition">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-base text-slate-900">
                                        {{ $unit ? $unit->name : 'Main Unit' }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-semibold">
                                        ({{ $property->title ?? 'Property' }})
                                    </span>
                                </div>
                                <div class="text-xs text-slate-600 flex items-center gap-3">
                                    <span>👤 <strong>{{ $tenant->name ?? 'Tenant' }}</strong></span>
                                    <span>📞 {{ $tenant->phone ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                <div class="text-left sm:text-right">
                                    <div class="text-sm font-black text-rose-700">
                                        Rs. {{ number_format($invoice->remaining_balance, 0) }} Pending
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        Total Due: Rs. {{ number_format($invoice->amount_due, 0) }}
                                        @if($invoice->arrears > 0)
                                            <span class="text-rose-600">(Arrears: Rs. {{ number_format($invoice->arrears, 0) }})</span>
                                        @endif
                                    </div>
                                </div>

                                @if($property)
                                <a href="{{ route('properties.show', $property) }}" 
                                   class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition active:scale-95 shrink-0">
                                    💵 Collect Rent
                                </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 text-sm">
                            🎉 All active portions have paid their rent for {{ $stats['month_name'] }}! No pending balances.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- RECENT PAYMENTS LOG -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900">
                            Recent Rent Collections
                        </h3>
                        <p class="text-xs text-slate-500">Latest payments received across all portions</p>
                    </div>
                    <a href="{{ route('payments.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">
                        View All Collections →
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentPayments as $payment)
                        @php
                            $lease = $payment->invoice ? $payment->invoice->lease : null;
                            $unit = $lease ? $lease->unit : null;
                            $tenant = $lease ? $lease->tenant : null;
                            $property = $lease ? $lease->property : null;
                        @endphp
                        <div class="p-4 sm:p-5 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    Rs
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-900">
                                        {{ $unit ? $unit->name : 'Unit' }}
                                        <span class="text-slate-400 font-normal">({{ $tenant->name ?? 'Tenant' }})</span>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : now()->format('d M, Y') }} • Via {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-sm text-emerald-800">
                                    + Rs. {{ number_format($payment->amount, 0) }}
                                </div>
                                @if($payment->security_amount > 0)
                                    <div class="text-[10px] text-indigo-600 font-semibold">
                                        (Rs. {{ number_format($payment->security_amount, 0) }} Security)
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            No rent collections recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- QUICK SHORTCUTS FOR PARENTS -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('properties.index') }}" class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-emerald-500 transition group flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 group-hover:text-emerald-700 transition">Buildings & Plazas</div>
                        <div class="text-xs text-slate-500">View cards with photos</div>
                    </div>
                </a>

                <a href="{{ route('tenants.index') }}" class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-emerald-500 transition group flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 group-hover:text-indigo-700 transition">Manage Tenants</div>
                        <div class="text-xs text-slate-500">Identity & phone numbers</div>
                    </div>
                </a>

                <a href="{{ url('/admin') }}" class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-emerald-500 transition group flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 group-hover:text-slate-800 transition">Filament Admin</div>
                        <div class="text-xs text-slate-500">Advanced backend panel</div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
