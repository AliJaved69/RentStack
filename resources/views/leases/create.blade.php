<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Lease') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="POST" action="{{ route('leases.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf

                        <!-- Property -->
                        <div>
                            <x-input-label for="property_id" :value="__('Vacant Property')" />
                            <select name="property_id" id="property_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>{{ $property->title }} ({{ $property->address }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                        </div>

                        <!-- Tenant -->
                        <div>
                            <x-input-label for="tenant_id" :value="__('Tenant')" />
                            <select name="tenant_id" id="tenant_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tenant_id')" class="mt-2" />
                        </div>

                        <!-- Start Date -->
                        <div>
                            <x-input-label for="start_date" :value="__('Lease Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- Base Rent -->
                        <div>
                            <x-input-label for="base_rent" :value="__('Monthly Base Rent')" />
                            <x-text-input id="base_rent" class="block mt-1 w-full" type="number" step="0.01" name="base_rent" :value="old('base_rent')" required />
                            <x-input-error :messages="$errors->get('base_rent')" class="mt-2" />
                        </div>

                        <!-- Security Deposit Expected -->
                        <div>
                            <x-input-label for="security_deposit_expected" :value="__('Security Deposit Expected')" />
                            <x-text-input id="security_deposit_expected" class="block mt-1 w-full" type="number" step="0.01" name="security_deposit_expected" :value="old('security_deposit_expected')" required />
                            <x-input-error :messages="$errors->get('security_deposit_expected')" class="mt-2" />
                        </div>

                        <!-- Security Deposit Paid -->
                        <div>
                            <x-input-label for="security_deposit_paid" :value="__('Security Deposit Paid')" />
                            <x-text-input id="security_deposit_paid" class="block mt-1 w-full" type="number" step="0.01" name="security_deposit_paid" :value="old('security_deposit_paid')" required />
                            <x-input-error :messages="$errors->get('security_deposit_paid')" class="mt-2" />
                        </div>

                        <!-- E-stamp Upload -->
                        <div class="col-span-full">
                            <x-input-label for="e_stamp" :value="__('E-stamp Document (PDF/Image)')" />
                            <input type="file" name="e_stamp" id="e_stamp" class="block mt-1 w-full border border-gray-300 rounded shadow-sm p-2 bg-gray-50 text-sm">
                            <p class="mt-1 text-xs text-gray-500 italic">Optional: Upload the E-stamp document for digital records.</p>
                            <x-input-error :messages="$errors->get('e_stamp')" class="mt-2" />
                        </div>

                        <div class="col-span-full flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('leases.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Create Lease') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
