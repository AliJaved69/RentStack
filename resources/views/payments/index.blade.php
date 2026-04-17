<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Payment History') }}
            </h2>
            <a href="{{ route('payments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
                + Record New Payment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 uppercase text-xs font-semibold text-gray-500">
                                <th class="px-4 py-3">Tenant</th>
                                <th class="px-4 py-3">Property</th>
                                <th class="px-4 py-3">Month</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 font-medium">{{ $payment->invoice->lease->tenant->name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $payment->invoice->lease->property->title }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $payment->invoice->billing_month }}</td>
                                    <td class="px-4 py-4 text-right font-bold text-green-600">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-4 text-sm uppercase">{{ $payment->payment_method }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $payment->payment_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
