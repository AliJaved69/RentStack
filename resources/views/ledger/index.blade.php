<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Family Internal Ledger') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Entry Form (Top) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Record New Borrowing / Return</h3>
                    <form method="POST" action="{{ route('ledger.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div>
                            <x-input-label for="type" :value="__('Transaction Type')" />
                            <select name="type" id="type" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="borrowed">Borrowed</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block w-full" type="number" step="0.01" name="amount" required />
                        </div>
                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block w-full" type="date" name="date" :value="date('Y-m-d')" required />
                        </div>
                        <div>
                            <x-primary-button class="w-full justify-center">Record Entry</x-primary-button>
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="description" :value="__('Description / Reason')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" placeholder="e.g. For utility bills payment" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- entries Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 uppercase text-xs font-semibold text-gray-500">
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($entries as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $entry->date }}</td>
                                    <td class="px-4 py-4 uppercase text-xs">
                                        <span class="px-2 py-1 rounded {{ $entry->type === 'borrowed' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $entry->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">{{ $entry->description }}</td>
                                    <td class="px-4 py-4 text-right font-bold {{ $entry->type === 'borrowed' ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $entry->type === 'borrowed' ? '-' : '+' }}${{ number_format($entry->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
