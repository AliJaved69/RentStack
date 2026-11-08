<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('properties.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Back to Properties">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                        {{ $property->title }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 flex items-center mt-0.5">
                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $property->address }}
                    </p>
                </div>
            </div>

            <!-- Top Actions -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="button" 
                        @click="$dispatch('open-add-unit-modal')"
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-bold rounded-xl shadow-xs transition active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    + Add Portion / Shop
                </button>
                <a href="{{ route('properties.edit', $property) }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition" title="Edit Building">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Main Container with Alpine state for modals -->
    <div x-data="{ 
            collectModalOpen: false, 
            addUnitModalOpen: false, 
            assignModalOpen: false,
            selectedUnit: null,
            selectedInvoice: null,
            selectedLease: null,
            selectedTenant: null,
            rentAmount: 0,
            securityAmount: 0,
            
            openCollectModal(unit, lease, invoice, tenant) {
                this.selectedUnit = unit;
                this.selectedLease = lease;
                this.selectedInvoice = invoice;
                this.selectedTenant = tenant;
                this.rentAmount = invoice ? invoice.remaining_balance : 0;
                this.securityAmount = 0;
                this.collectModalOpen = true;
            },

            openAssignModal(unit) {
                this.selectedUnit = unit;
                this.assignModalOpen = true;
            }
         }" 
         @open-add-unit-modal.window="addUnitModalOpen = true"
         class="py-6 sm:py-10">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- WhatsApp Receipt Flash Banner -->
            @if(session('whatsapp_url'))
                <div class="p-4 bg-emerald-600 text-white rounded-2xl shadow-md flex flex-col sm:flex-row items-center justify-between gap-3 animate-bounce-once">
                    <div class="flex items-center space-x-3 text-center sm:text-left">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-base">Payment Recorded! Send Receipt via WhatsApp</div>
                            <div class="text-xs text-emerald-100">Click below to send the pre-filled rent receipt to {{ session('receipt_tenant') }}.</div>
                        </div>
                    </div>
                    <a href="{{ session('whatsapp_url') }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-white text-emerald-700 font-bold text-sm rounded-xl shadow-sm hover:bg-emerald-50 transition active:scale-95">
                        <span>📲 Send WhatsApp Receipt</span>
                    </a>
                </div>
            @endif

            <!-- Property Overview Banner & Summary Stats -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-6 sm:p-8">
                    @php
                        $totalUnits = $property->units->count();
                        $occupiedUnits = $property->units->where('status', 'occupied')->count();
                        $vacantUnits = $property->units->where('status', 'vacant')->count();
                        
                        $totalMonthlyDue = 0;
                        $totalCollected = 0;
                        $totalRemaining = 0;

                        foreach($property->units as $u) {
                            if ($u->activeLease && $u->activeLease->latestInvoice) {
                                $inv = $u->activeLease->latestInvoice;
                                $totalMonthlyDue += $inv->amount_due;
                                $totalCollected += $inv->amount_paid;
                                $totalRemaining += $inv->remaining_balance;
                            } elseif ($u->default_rent) {
                                $totalMonthlyDue += $u->default_rent;
                            }
                        }
                    @endphp

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Portions & Shops</span>
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="text-2xl font-black text-slate-900">{{ $totalUnits }}</span>
                                <span class="text-xs font-semibold text-emerald-700">({{ $occupiedUnits }} Rented, {{ $vacantUnits }} Vacant)</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Monthly Rent</span>
                            <div class="mt-1">
                                <span class="text-2xl font-black text-slate-900">Rs. {{ number_format($totalMonthlyDue, 0) }}</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-100">
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">Collected This Month</span>
                            <div class="mt-1">
                                <span class="text-2xl font-black text-emerald-800">Rs. {{ number_format($totalCollected, 0) }}</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl {{ $totalRemaining > 0 ? 'bg-rose-50/80 border-rose-100' : 'bg-slate-50 border-slate-100' }}">
                            <span class="text-xs font-bold {{ $totalRemaining > 0 ? 'text-rose-700' : 'text-slate-400' }} uppercase tracking-wider block">Remaining / Arrears</span>
                            <div class="mt-1">
                                <span class="text-2xl font-black {{ $totalRemaining > 0 ? 'text-rose-700' : 'text-slate-900' }}">Rs. {{ number_format($totalRemaining, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Units / Portions / Shops Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-xl text-slate-900">
                        {{ __('Portions, Shops & Units') }}
                    </h3>
                    <p class="text-xs text-slate-500">Tap on any portion or shop to enter rent paid by the tenant</p>
                </div>
            </div>

            <!-- UNIT CARDS (WITHOUT PICTURES - PURE RENT INFO) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($property->units as $unit)
                    @php
                        $lease = $unit->activeLease;
                        $invoice = $lease ? $lease->latestInvoice : null;
                        $tenant = $lease ? $lease->tenant : null;

                        $rent = $lease ? $lease->base_rent : $unit->default_rent;
                        $arrears = $invoice ? $invoice->arrears : 0;
                        $totalDue = $invoice ? $invoice->amount_due : $rent;
                        $amountPaid = $invoice ? $invoice->amount_paid : 0;
                        $remaining = $invoice ? $invoice->remaining_balance : $rent;

                        $securityExpected = $lease ? $lease->security_deposit_expected : ($rent * 2);
                        $securityPaid = $lease ? $lease->security_deposit_paid : 0;
                        $securityRemaining = max(0, $securityExpected - $securityPaid);

                        // Status Calculation
                        if (!$lease) {
                            $status = 'vacant';
                            $statusLabel = '⚪ Vacant';
                            $statusBadgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                        } elseif ($invoice && $invoice->status === 'paid') {
                            $status = 'paid';
                            $statusLabel = '🟢 Paid';
                            $statusBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                        } elseif ($invoice && $invoice->amount_paid > 0) {
                            $status = 'partial';
                            $statusLabel = '🟡 Partial (Rs. ' . number_format($remaining, 0) . ' Pending)';
                            $statusBadgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                        } else {
                            $status = 'unpaid';
                            $statusLabel = '🔴 Unpaid (Full Due)';
                            $statusBadgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                        }
                    @endphp

                    <!-- Unit Card -->
                    <div class="bg-white rounded-2xl border {{ $remaining > 0 && $lease ? 'border-amber-300 shadow-xs' : 'border-slate-200/90' }} hover:shadow-md transition-all duration-150 flex flex-col justify-between overflow-hidden">
                        
                        <div class="p-5 space-y-4">
                            <!-- Card Header: Unit Name & Type Badge -->
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 mb-1">
                                        {{ ucfirst($unit->type) }}
                                    </span>
                                    <h4 class="font-extrabold text-lg text-slate-900 leading-snug">
                                        {{ $unit->name }}
                                    </h4>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusBadgeClass }} shrink-0">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <!-- Tenant Info -->
                            @if($tenant)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-800">{{ $tenant->name }}</div>
                                            <div class="text-[11px] text-slate-500 font-mono">{{ $tenant->phone }}</div>
                                        </div>
                                    </div>
                                    <a href="tel:{{ $tenant->phone }}" class="p-1.5 text-slate-400 hover:text-emerald-600 rounded-lg hover:bg-slate-200/60" title="Call tenant">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-400 text-center">
                                    No tenant assigned to this portion yet
                                </div>
                            @endif

                            <!-- Rent & Arrears Breakdown Box -->
                            <div class="space-y-2 pt-1 text-xs">
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Monthly Rent:</span>
                                    <span class="font-bold text-slate-900">Rs. {{ number_format($rent, 0) }}</span>
                                </div>

                                @if($arrears > 0)
                                <div class="flex justify-between items-center text-rose-600 font-semibold bg-rose-50 px-2 py-1 rounded-md">
                                    <span>Previous Arrears:</span>
                                    <span>+ Rs. {{ number_format($arrears, 0) }}</span>
                                </div>
                                @endif

                                <div class="flex justify-between items-center text-slate-700 font-semibold pt-1 border-t border-slate-100">
                                    <span>Total Due:</span>
                                    <span class="font-extrabold text-sm text-slate-900">Rs. {{ number_format($totalDue, 0) }}</span>
                                </div>

                                <div class="flex justify-between items-center text-emerald-700">
                                    <span>Paid This Month:</span>
                                    <span class="font-bold">Rs. {{ number_format($amountPaid, 0) }}</span>
                                </div>

                                <div class="flex justify-between items-center text-slate-900 font-bold bg-slate-100 px-2.5 py-1.5 rounded-lg">
                                    <span>Remaining Balance:</span>
                                    <span class="text-sm {{ $remaining > 0 ? 'text-rose-700 font-extrabold' : 'text-emerald-700' }}">
                                        Rs. {{ number_format($remaining, 0) }}
                                    </span>
                                </div>

                                <!-- Security Deposit / Advance Progress -->
                                <div class="pt-2 border-t border-slate-100">
                                    <div class="flex justify-between text-[11px] text-slate-500">
                                        <span>Security / Advance:</span>
                                        <span class="font-semibold text-slate-700">Rs. {{ number_format($securityPaid, 0) }} / {{ number_format($securityExpected, 0) }}</span>
                                    </div>
                                    @if($securityRemaining > 0)
                                    <div class="text-[10px] text-amber-700 font-medium mt-0.5">
                                        (Rs. {{ number_format($securityRemaining, 0) }} security installment pending)
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Button: Big Touch Target for Parents -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            @if($lease && $invoice)
                                <button type="button" 
                                        @click="openCollectModal({{ json_encode($unit) }}, {{ json_encode($lease) }}, {{ json_encode($invoice) }}, {{ json_encode($tenant) }})"
                                        class="w-full flex items-center justify-center py-3 px-4 rounded-xl text-sm font-bold shadow-xs transition active:scale-[0.98] {{ $remaining > 0 ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $remaining > 0 ? '💵 Collect Rent' : 'Record Extra Payment' }}
                                </button>
                            @else
                                <button type="button" 
                                        @click="openAssignModal({{ json_encode($unit) }})"
                                        class="w-full flex items-center justify-center py-2.5 px-4 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition active:scale-[0.98]">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Assign Tenant to this Portion
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="col-span-full bg-white p-10 rounded-2xl border border-dashed border-slate-300 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base">No Portions or Shops Added Yet</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Click "+ Add Portion / Shop" above to divide this building into its rental spaces (e.g. Ground Floor, Shop 1, 1st Floor Back).
                        </p>
                        <button type="button" 
                                @click="addUnitModalOpen = true"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">
                            + Add First Portion or Shop
                        </button>
                    </div>
                @endforelse
            </div>

        </div>

        <!-- ==================================================================== -->
        <!-- MODAL 1: COLLECT RENT / ENTER PAYMENT (TOUCH FRIENDLY FOR PARENTS)   -->
        <!-- ==================================================================== -->
        <div x-show="collectModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.outside="collectModalOpen = false" 
                 class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 p-6 sm:p-8 space-y-5 animate-scale-up">

                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                            Rent Payment Entry
                        </span>
                        <h3 class="font-extrabold text-2xl text-slate-900 mt-1" x-text="selectedUnit ? selectedUnit.name : 'Collect Rent'"></h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Tenant: <span class="font-semibold text-slate-800" x-text="selectedTenant ? selectedTenant.name : ''"></span>
                        </p>
                    </div>
                    <button type="button" @click="collectModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                        ✕
                    </button>
                </div>

                <!-- Balance Summary Card -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Current Month Due:</span>
                        <span class="font-bold text-slate-900" x-text="'Rs. ' + Number(selectedInvoice ? selectedInvoice.amount_due : 0).toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Already Paid So Far:</span>
                        <span class="font-bold text-emerald-700" x-text="'Rs. ' + Number(selectedInvoice ? selectedInvoice.amount_paid : 0).toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-extrabold text-sm pt-2 border-t border-slate-200">
                        <span>Remaining Balance:</span>
                        <span class="text-rose-700" x-text="'Rs. ' + Number(selectedInvoice ? selectedInvoice.remaining_balance : 0).toLocaleString()"></span>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="invoice_id" :value="selectedInvoice ? selectedInvoice.id : ''">

                    <!-- Rent Amount Received -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Rent Amount Received Today (Rs.)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 font-bold text-sm">Rs.</span>
                            <input type="number" 
                                   name="rent_amount" 
                                   x-model="rentAmount"
                                   step="1"
                                   required 
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-lg font-black text-slate-900">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">If tenant pays partial, enter only the amount received today. The rest will roll over next month.</p>
                    </div>

                    <!-- Security Deposit Installment (Optional) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Security Deposit / Advance Installment (Optional)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-slate-400 font-bold text-sm">Rs.</span>
                            <input type="number" 
                                   name="security_amount" 
                                   x-model="securityAmount"
                                   step="1"
                                   placeholder="0"
                                   class="w-full pl-11 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-slate-900">
                        </div>
                    </div>

                    <!-- Total Received Preview -->
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex justify-between items-center text-xs">
                        <span class="font-bold text-emerald-900">Total Money Received:</span>
                        <span class="text-base font-black text-emerald-800" x-text="'Rs. ' + (Number(rentAmount || 0) + Number(securityAmount || 0)).toLocaleString()"></span>
                    </div>

                    <!-- Payment Method & Date -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Payment Method</label>
                            <select name="payment_method" class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                                <option value="cash">💵 Cash</option>
                                <option value="easypaisa">📱 EasyPaisa</option>
                                <option value="jazzcash">📱 JazzCash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Payment Date</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 flex items-center justify-between gap-3">
                        <button type="button" @click="collectModalOpen = false" class="px-4 py-2.5 text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 py-3 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition active:scale-95 text-center">
                            Save Payment & Create Receipt
                        </button>
                    </div>
                </form>

            </div>
        </div>


        <!-- ==================================================================== -->
        <!-- MODAL 2: ADD NEW PORTION / SHOP UNDER THIS PROPERTY                  -->
        <!-- ==================================================================== -->
        <div x-show="addUnitModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.outside="addUnitModalOpen = false" 
                 class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 p-6 sm:p-8 space-y-5 animate-scale-up">

                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                            Add Portion or Shop
                        </span>
                        <h3 class="font-extrabold text-xl text-slate-900 mt-1">New Space in {{ $property->title }}</h3>
                        <p class="text-xs text-slate-500">e.g. Ground Floor, Shop 1 (Mobile Accessories), 1st Floor Back</p>
                    </div>
                    <button type="button" @click="addUnitModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                        ✕
                    </button>
                </div>

                <form method="POST" action="{{ route('properties.units.store', $property) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Portion / Shop Name *</label>
                        <input type="text" name="name" placeholder="e.g. Shop 1 - Mobile Accessories, 1st Floor Back Portion" required class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Space Type *</label>
                            <select name="type" required class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                                <option value="portion">Portion</option>
                                <option value="shop">Shop</option>
                                <option value="floor">Full Floor</option>
                                <option value="flat">Flat / Apartment</option>
                                <option value="room">Room</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Rent (Rs.) *</label>
                            <input type="number" name="default_rent" placeholder="25000" step="1" required class="w-full rounded-xl border-slate-200 text-sm font-bold">
                        </div>
                    </div>

                    <!-- Optional: Assign Tenant Right Away -->
                    <div class="pt-3 border-t border-slate-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-700">Assign Tenant Right Now (Optional)</label>
                        <select name="tenant_id" class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                            <option value="">-- Leave Vacant for now --</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->phone }})</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400">If assigned, an active lease and current month rent will be initialized automatically.</p>
                    </div>

                    <div class="pt-3 flex items-center justify-between gap-3">
                        <button type="button" @click="addUnitModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-500">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 py-3 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition active:scale-95">
                            Save Portion / Shop
                        </button>
                    </div>
                </form>

            </div>
        </div>


        <!-- ==================================================================== -->
        <!-- MODAL 3: ASSIGN TENANT TO VACANT PORTION                             -->
        <!-- ==================================================================== -->
        <div x-show="assignModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.outside="assignModalOpen = false" 
                 class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 p-6 sm:p-8 space-y-5 animate-scale-up">

                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                            Assign Tenant
                        </span>
                        <h3 class="font-extrabold text-xl text-slate-900 mt-1" x-text="selectedUnit ? selectedUnit.name : 'Assign Tenant'"></h3>
                        <p class="text-xs text-slate-500">Set the tenant, monthly rent, and security deposit</p>
                    </div>
                    <button type="button" @click="assignModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                        ✕
                    </button>
                </div>

                <form method="POST" :action="selectedUnit ? '{{ url('units') }}/' + selectedUnit.id + '/assign' : '#'" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Select Tenant *</label>
                        <select name="tenant_id" required class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                            <option value="">-- Choose Tenant --</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Rent (Rs.) *</label>
                            <input type="number" 
                                   name="base_rent" 
                                   :value="selectedUnit ? selectedUnit.default_rent : 25000"
                                   required 
                                   class="w-full rounded-xl border-slate-200 text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Lease Start Date *</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Security Expected (Rs.)</label>
                            <input type="number" name="security_deposit_expected" placeholder="Usually 2x Rent" class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Security Paid Upfront (Rs.)</label>
                            <input type="number" name="security_deposit_paid" placeholder="0" class="w-full rounded-xl border-slate-200 text-sm font-semibold">
                        </div>
                    </div>

                    <div class="pt-3 flex items-center justify-between gap-3">
                        <button type="button" @click="assignModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-500">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 py-3 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-xs transition active:scale-95">
                            Confirm Lease & Activate Portion
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-app-layout>

