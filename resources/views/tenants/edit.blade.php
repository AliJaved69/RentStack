<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Edit Tenant') }}
            </h2>
            <a href="{{ route('tenants.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back to Tenants
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6 sm:p-8">
                <form method="POST" action="{{ route('tenants.update', $tenant) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Tenant Full Name')" class="text-slate-700 font-semibold" />
                        <x-text-input id="name" class="block mt-1.5 w-full rounded-xl" type="text" name="name" :value="old('name', $tenant->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" :value="__('Phone Number')" class="text-slate-700 font-semibold" />
                        <x-text-input id="phone" class="block mt-1.5 w-full rounded-xl" type="text" name="phone" :value="old('phone', $tenant->phone)" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- CNIC / ID -->
                    <div>
                        <x-input-label for="cnic_or_id" :value="__('CNIC / National ID')" class="text-slate-700 font-semibold" />
                        <x-text-input id="cnic_or_id" class="block mt-1.5 w-full rounded-xl" type="text" name="cnic_or_id" :value="old('cnic_or_id', $tenant->cnic_or_id)" required />
                        <x-input-error :messages="$errors->get('cnic_or_id')" class="mt-2" />
                    </div>

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                        <div class="flex items-center gap-3">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 py-2.5 px-5 rounded-xl font-semibold shadow-xs">
                                {{ __('Save Changes') }}
                            </x-primary-button>
                            <a href="{{ route('tenants.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 px-3 py-2">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

