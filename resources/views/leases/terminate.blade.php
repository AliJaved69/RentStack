<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lease Settlement & Termination') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800">Lease Details</h3>
                        <p class="text-gray-600">Tenant: <strong>{{ $lease->tenant->name }}</strong></p>
                        <p class="text-gray-600">Property: <strong>{{ $lease->property->title }}</strong></p>
                        <p class="text-gray-600">Security Deposit Paid: <strong class="text-green-600">${{ number_format($lease->security_deposit_paid, 2) }}</strong></p>
                    </div>

                    <form method="POST" action="{{ route('leases.settle', $lease) }}" class="max-w-xl space-y-6">
                        @csrf
                        
                        <h4 class="font-semibold text-gray-700">Deduct Utilities & Costs</h4>
                        
                        <div>
                            <x-input-label for="electricity" :value="__('Electricity Bill Deduction')" />
                            <x-text-input id="electricity" class="block mt-1 w-full" type="number" step="0.01" name="electricity" :value="old('electricity', 0)" required />
                        </div>

                        <div>
                            <x-input-label for="gas" :value="__('Gas Bill Deduction')" />
                            <x-text-input id="gas" class="block mt-1 w-full" type="number" step="0.01" name="gas" :value="old('gas', 0)" required />
                        </div>

                        <div>
                            <x-input-label for="water" :value="__('Water Bill Deduction')" />
                            <x-text-input id="water" class="block mt-1 w-full" type="number" step="0.01" name="water" :value="old('water', 0)" required />
                        </div>

                        <div class="bg-gray-50 p-4 rounded text-sm text-gray-600">
                            <strong>Note:</strong> The total deductions will be subtracted from the security deposit. The remaining amount will be marked as "Refunded" and the property will be set back to "Vacant".
                        </div>

                        <div class="flex items-center gap-4">
                            <x-danger-button>
                                {{ __('Settle & Terminate Lease') }}
                            </x-danger-button>
                            <a href="{{ route('leases.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
