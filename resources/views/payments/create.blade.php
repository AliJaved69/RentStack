<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Record Rent Payment') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Collect monthly rent or security installment and generate WhatsApp receipt</p>
            </div>
            <a href="{{ route('payments.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10" 
         x-data="{
            invoices: {{ Js::from($invoices->map(fn($inv) => [
                'id' => $inv->id,
                'due' => (float) ($inv->amount_due - $inv->amount_paid),
                'total_due' => (float) $inv->amount_due,
                'paid_so_far' => (float) $inv->amount_paid,
                'portion' => $inv->lease->unit ? $inv->lease->unit->name : 'Whole Property',
                'property' => $inv->lease->property->title,
                'tenant' => $inv->lease->tenant->name,
                'phone' => $inv->lease->tenant->phone,
                'month' => $inv->billing_month ? \Carbon\Carbon::parse($inv->billing_month)->format('F Y') : '',
            ])) }},
            selectedInvoiceId: '{{ old('invoice_id', $invoice->id ?? '') }}',
            rentAmount: '{{ old('rent_amount', '') }}',
            securityAmount: '{{ old('security_amount', '0') }}',

            get currentInvoice() {
                return this.invoices.find(inv => inv.id == this.selectedInvoiceId) || null;
            },

            onInvoiceSelect() {
                if (this.currentInvoice) {
                    this.rentAmount = this.currentInvoice.due;
                }
            }
         }"
         x-init="if (selectedInvoiceId && !rentAmount) { onInvoiceSelect(); }">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xs rounded-3xl border border-slate-200/90 p-6 sm:p-8">
                <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
                    @csrf

                    <!-- Invoice Selection -->
                    <div>
                        <label for="invoice_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Select Portion / Pending Bill <span class="text-rose-500">*</span>
                        </label>
                        <select name="invoice_id" 
                                id="invoice_id" 
                                x-model="selectedInvoiceId"
                                @change="onInvoiceSelect()"
                                class="w-full rounded-2xl border-slate-200 text-slate-900 font-bold focus:border-emerald-500 focus:ring-emerald-500 p-3 shadow-2xs text-sm" 
                                required>
                            <option value="">-- Choose Tenant & Portion Due --</option>
                            @foreach($invoices as $dueInvoice)
                                @php
                                    $unitName = $dueInvoice->lease->unit ? $dueInvoice->lease->unit->name : 'Whole Property';
                                    $propName = $dueInvoice->lease->property->title;
                                    $tenantName = $dueInvoice->lease->tenant->name;
                                    $dueBalance = $dueInvoice->amount_due - $dueInvoice->amount_paid;
                                    $monthName = \Carbon\Carbon::parse($dueInvoice->billing_month)->format('M Y');
                                @endphp
                                <option value="{{ $dueInvoice->id }}" 
                                    {{ (old('invoice_id') ?? ($invoice->id ?? '')) == $dueInvoice->id ? 'selected' : '' }}>
                                    🚪 {{ $unitName }} ({{ $propName }}) - {{ $tenantName }} [{{ $monthName }}] - Pending: Rs. {{ number_format($dueBalance, 0) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('invoice_id')" class="mt-2" />
                    </div>

                    <!-- Selected Invoice Status Box -->
                    <template x-if="currentInvoice">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Portion / Shop:</span>
                                <span class="font-bold text-slate-900" x-text="currentInvoice.portion + ' (' + currentInvoice.property + ')'"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Tenant:</span>
                                <span class="font-bold text-slate-900" x-text="currentInvoice.tenant + ' (' + currentInvoice.phone + ')'"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Billing Month:</span>
                                <span class="font-semibold text-slate-700" x-text="currentInvoice.month"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-900 font-extrabold text-sm pt-2 border-t border-slate-200">
                                <span>Remaining Balance Due:</span>
                                <span class="text-rose-600" x-text="'Rs. ' + Number(currentInvoice.due).toLocaleString()"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Rent Amount Received -->
                    <div>
                        <label for="rent_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Rent Amount Received Today (Rs.) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-slate-400 font-bold text-sm">Rs.</span>
                            <input type="number" 
                                   name="rent_amount" 
                                   id="rent_amount" 
                                   x-model="rentAmount"
                                   step="1" 
                                   required 
                                   placeholder="25000"
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 text-lg font-black text-slate-900 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">If tenant pays partial, enter what you received. Remaining balance rolls over automatically.</p>
                        <x-input-error :messages="$errors->get('rent_amount')" class="mt-1" />
                    </div>

                    <!-- Security / Advance Installment (Optional) -->
                    <div>
                        <label for="security_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Security Deposit / Advance Installment (Optional)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-400 font-bold text-xs">Rs.</span>
                            <input type="number" 
                                   name="security_amount" 
                                   id="security_amount" 
                                   x-model="securityAmount"
                                   step="1" 
                                   placeholder="0"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Fill this only if tenant is paying a security deposit installment alongside rent.</p>
                        <x-input-error :messages="$errors->get('security_amount')" class="mt-1" />
                    </div>

                    <!-- Total Received Indicator -->
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex justify-between items-center text-xs">
                        <span class="font-bold text-emerald-950">Total Cash / Money Collected:</span>
                        <span class="text-xl font-black text-emerald-800" x-text="'Rs. ' + (Number(rentAmount || 0) + Number(securityAmount || 0)).toLocaleString()"></span>
                    </div>

                    <!-- Payment Method & Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Payment Method <span class="text-rose-500">*</span>
                            </label>
                            <select name="payment_method" id="payment_method" class="w-full rounded-2xl border-slate-200 text-slate-800 font-bold focus:border-emerald-500 focus:ring-emerald-500 p-3 shadow-2xs text-sm">
                                <option value="cash">💵 Cash</option>
                                <option value="easypaisa">📱 EasyPaisa</option>
                                <option value="jazzcash">📱 JazzCash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-1" />
                        </div>

                        <div>
                            <label for="payment_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Payment Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" 
                                   name="payment_date" 
                                   id="payment_date" 
                                   value="{{ old('payment_date', date('Y-m-d')) }}" 
                                   class="w-full py-3 px-4 rounded-2xl border-slate-200 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs" 
                                   required>
                            <x-input-error :messages="$errors->get('payment_date')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 flex items-center justify-between border-t border-slate-100 gap-3">
                        <a href="{{ route('payments.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 sm:flex-none py-3 px-8 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition active:scale-95 text-center">
                            Record Payment & Create Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
