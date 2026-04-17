<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record Rent Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="POST" action="{{ route('payments.store') }}" class="max-w-xl space-y-6">
                        @csrf

                        <!-- Invoice Selection -->
                        <div>
                            <x-input-label for="invoice_id" :value="__('Select Pending Invoice')" />
                            <select name="invoice_id" id="invoice_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Select Due Invoice --</option>
                                @foreach($invoices as $dueInvoice)
                                    <option value="{{ $dueInvoice->id }}" 
                                        {{ (old('invoice_id') ?? ($invoice->id ?? '')) == $dueInvoice->id ? 'selected' : '' }}>
                                        {{ $dueInvoice->lease->tenant->name }} - {{ $dueInvoice->lease->property->title }} 
                                        ({{ $dueInvoice->billing_month }}): 
                                        Due ${{ number_format($dueInvoice->amount_due - $dueInvoice->amount_paid, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('invoice_id')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div>
                            <x-input-label for="amount" :value="__('Amount Paid')" />
                            <x-text-input id="amount" class="block mt-1 w-full text-lg font-bold" type="number" step="0.01" name="amount" :value="old('amount')" required />
                            <p class="mt-1 text-xs text-gray-500">If less than total due, the invoice will remain 'Partially Paid'.</p>
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Method -->
                        <div>
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select name="payment_method" id="payment_method" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <!-- Date -->
                        <div>
                            <x-input-label for="payment_date" :value="__('Payment Date')" />
                            <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Record Payment & Send Receipt') }}
                            </x-primary-button>
                            <a href="{{ route('payments.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
