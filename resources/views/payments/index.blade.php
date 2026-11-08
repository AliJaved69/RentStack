<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Payment History & Receipts') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Track rent collections, advance installments, and send receipts</p>
            </div>
            <a href="{{ route('payments.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-bold rounded-xl shadow-xs transition active:scale-95">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Record Rent Payment
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            <!-- Mobile Payment Cards View (md:hidden) -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse($payments as $payment)
                    @php
                        $lease = $payment->invoice?->lease;
                        $unit = $lease?->unit;
                        $property = $lease?->property;
                        $tenant = $lease?->tenant;
                    @endphp

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3.5">
                        
                        <!-- Header: Portion/Shop & Amount -->
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                    {{ $property->title ?? 'Property' }}
                                </span>
                                <h3 class="font-extrabold text-lg text-slate-900 leading-snug">
                                    {{ $unit ? $unit->name : 'Whole Property' }}
                                </h3>
                                <p class="text-xs text-slate-500 font-semibold mt-0.5">
                                    Tenant: {{ $tenant->name ?? 'Unknown' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-black text-emerald-700 block">
                                    Rs. {{ number_format($payment->amount, 0) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Split Breakdown -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs space-y-1.5">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Rent Amount:</span>
                                <span class="font-bold text-slate-800">Rs. {{ number_format($payment->rent_amount, 0) }}</span>
                            </div>
                            @if($payment->security_amount > 0)
                                <div class="flex justify-between items-center text-purple-700">
                                    <span>Security / Advance:</span>
                                    <span class="font-bold text-purple-800">Rs. {{ number_format($payment->security_amount, 0) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pt-1 border-t border-slate-200/60 text-slate-600">
                                <span>Payment Method:</span>
                                <span class="font-semibold uppercase text-[11px] px-2 py-0.5 rounded bg-white border border-slate-200">
                                    @if($payment->payment_method === 'cash') 💵 Cash
                                    @elseif($payment->payment_method === 'easypaisa') 📱 EasyPaisa
                                    @elseif($payment->payment_method === 'jazzcash') 📱 JazzCash
                                    @else 🏦 Bank Transfer
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- WhatsApp Receipt Button -->
                        @if($payment->whatsapp_receipt_url)
                            <a href="{{ $payment->whatsapp_receipt_url }}" 
                               target="_blank" 
                               class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition active:scale-95">
                                <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z" />
                                </svg>
                                <span>📲 Send WhatsApp Receipt</span>
                            </a>
                        @endif

                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-dashed border-slate-200 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-800">No Payments Recorded Yet</h4>
                        <p class="text-xs text-slate-500 mt-1">When tenants pay rent or security advances, their payment history and WhatsApp receipts will show here.</p>
                        <a href="{{ route('payments.create') }}" class="inline-flex mt-4 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">
                            Record First Payment
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View (hidden md:block) -->
            <div class="hidden md:block bg-white overflow-hidden shadow-xs rounded-2xl border border-slate-200/80">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Portion / Shop</th>
                            <th class="px-6 py-4">Tenant</th>
                            <th class="px-6 py-4 text-right">Rent Paid</th>
                            <th class="px-6 py-4 text-right">Security Paid</th>
                            <th class="px-6 py-4 text-right">Total Amount</th>
                            <th class="px-6 py-4">Method</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            @php
                                $lease = $payment->invoice?->lease;
                                $unit = $lease?->unit;
                                $property = $lease?->property;
                                $tenant = $lease?->tenant;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $unit ? $unit->name : 'Whole Property' }}</div>
                                    <div class="text-xs text-slate-400">{{ $property->title ?? 'Property' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $tenant->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-400">{{ $tenant->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-900">
                                    Rs. {{ number_format($payment->rent_amount, 0) }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-purple-700">
                                    {{ $payment->security_amount > 0 ? 'Rs. ' . number_format($payment->security_amount, 0) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-black text-emerald-700">
                                    Rs. {{ number_format($payment->amount, 0) }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-semibold uppercase">
                                        {{ $payment->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($payment->whatsapp_receipt_url)
                                        <a href="{{ $payment->whatsapp_receipt_url }}" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg border border-emerald-200 transition">
                                            📲 WhatsApp
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-slate-400 text-sm">
                                    No payments recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
