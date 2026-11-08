<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Lease Settlement & Move-Out') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Settle utility bills, refund security deposit, and release portion</p>
            </div>
            <a href="{{ route('leases.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back
            </a>
        </div>
    </x-slot>

    @php
        $unitName = $lease->unit ? $lease->unit->name : 'Whole Property';
        $securityPaid = (float) $lease->security_deposit_paid;
    @endphp

    <div class="py-6 sm:py-10" 
         x-data="{
            securityDeposit: {{ $securityPaid }},
            electricity: 0,
            gas: 0,
            water: 0,
            repairs: 0,
            
            get totalDeductions() {
                return Number(this.electricity || 0) + Number(this.gas || 0) + Number(this.water || 0) + Number(this.repairs || 0);
            },

            get netRefund() {
                return Math.max(0, this.securityDeposit - this.totalDeductions);
            },

            get tenantBalanceDue() {
                return Math.max(0, this.totalDeductions - this.securityDeposit);
            }
         }">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xs rounded-3xl border border-slate-200/90 p-6 sm:p-8 space-y-6">
                
                <!-- Lease Info Card -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2.5">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                {{ $lease->property->title }}
                            </span>
                            <h3 class="font-extrabold text-xl text-slate-900">{{ $unitName }}</h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            Move-Out Settlement
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2 text-xs text-slate-600 border-t border-slate-200/60">
                        <div>
                            <span class="text-slate-400 block">Tenant Name:</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $lease->tenant->name }}</span>
                            <span class="text-slate-500 font-mono text-[11px] block">{{ $lease->tenant->phone }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block">Security Advance in Hand:</span>
                            <span class="font-black text-emerald-700 text-base">Rs. {{ number_format($securityPaid, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('leases.settle', $lease) }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 mb-1">Deduct Unpaid Utilities & Expenses</h4>
                        <p class="text-xs text-slate-500">Enter pending electricity, gas, water bills or damages to deduct from security.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Electricity -->
                        <div>
                            <label for="electricity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Electricity Bill (Rs.)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-xs">Rs.</span>
                                <input type="number" 
                                       step="1" 
                                       name="electricity" 
                                       id="electricity" 
                                       x-model="electricity" 
                                       class="w-full pl-9 pr-3 py-2.5 rounded-xl border-slate-200 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <!-- Gas -->
                        <div>
                            <label for="gas" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Gas Bill (Rs.)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-xs">Rs.</span>
                                <input type="number" 
                                       step="1" 
                                       name="gas" 
                                       id="gas" 
                                       x-model="gas" 
                                       class="w-full pl-9 pr-3 py-2.5 rounded-xl border-slate-200 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <!-- Water -->
                        <div>
                            <label for="water" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Water / Maintenance Bill (Rs.)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-xs">Rs.</span>
                                <input type="number" 
                                       step="1" 
                                       name="water" 
                                       id="water" 
                                       x-model="water" 
                                       class="w-full pl-9 pr-3 py-2.5 rounded-xl border-slate-200 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>

                    <!-- Live Settlement Calculation Banner -->
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2 text-xs">
                        <div class="flex justify-between items-center text-emerald-900 font-medium">
                            <span>Initial Security Deposit:</span>
                            <span class="font-bold">Rs. {{ number_format($securityPaid, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-rose-700 font-medium">
                            <span>Total Deductions:</span>
                            <span class="font-bold" x-text="'- Rs. ' + Number(totalDeductions).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-emerald-200/80 text-sm">
                            <span class="font-extrabold text-emerald-950">Net Amount to Refund to Tenant:</span>
                            <span class="text-xl font-black text-emerald-800" x-text="'Rs. ' + Number(netRefund).toLocaleString()"></span>
                        </div>
                        <template x-if="tenantBalanceDue > 0">
                            <div class="pt-2 text-rose-600 font-bold">
                                ⚠️ Deductions exceed security deposit! Tenant owes additional: <span x-text="'Rs. ' + Number(tenantBalanceDue).toLocaleString()"></span>
                            </div>
                        </template>
                    </div>

                    <div class="text-[11px] text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        💡 Upon confirmation, this lease will be marked as <strong>Terminated</strong> and the portion ({{ $unitName }}) will automatically become <strong>Vacant</strong> and ready for a new tenant.
                    </div>

                    <div class="pt-2 flex items-center justify-between gap-3">
                        <a href="{{ route('leases.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to finalize this settlement and terminate the lease?');"
                                class="flex-1 sm:flex-none py-3 px-8 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-xs transition active:scale-95 text-center">
                            Confirm Settlement & Vacate Portion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
