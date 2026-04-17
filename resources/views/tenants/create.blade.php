<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Tenant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="POST" action="{{ route('tenants.store') }}" class="max-w-xl space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Tenant Full Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- CNIC / ID -->
                        <div>
                            <x-input-label for="cnic_or_id" :value="__('CNIC / National ID')" />
                            <x-text-input id="cnic_or_id" class="block mt-1 w-full" type="text" name="cnic_or_id" :value="old('cnic_or_id')" required />
                            <p class="mt-1 text-xs text-gray-500">Ensure this is unique and matches official identity documents.</p>
                            <x-input-error :messages="$errors->get('cnic_or_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Add Tenant') }}
                            </x-primary-button>
                            <a href="{{ route('tenants.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 line-through decoration-transparent hover:decoration-gray-300">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
