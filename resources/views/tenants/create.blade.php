<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Add New Tenant') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Register tenant identity and WhatsApp contact for rent receipts</p>
            </div>
            <a href="{{ route('tenants.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xs rounded-3xl border border-slate-200/90 p-6 sm:p-8">
                <form method="POST" action="{{ route('tenants.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tenant Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               value="{{ old('name') }}" 
                               placeholder="e.g. Muhammad Ali, Tariq Mahmood" 
                               required 
                               autofocus 
                               class="w-full rounded-2xl border-slate-200 text-slate-900 font-bold focus:border-emerald-500 focus:ring-emerald-500 p-3 shadow-2xs text-base">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Phone (WhatsApp) -->
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Mobile / WhatsApp Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-slate-400 font-bold text-sm">📞</span>
                            <input id="phone" 
                                   name="phone" 
                                   type="tel" 
                                   value="{{ old('phone') }}" 
                                   placeholder="03001234567 or +923001234567" 
                                   required 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 text-slate-900 font-mono font-bold focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-base">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Rent receipts will be sent to this number directly via WhatsApp.</p>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- CNIC / National ID -->
                    <div>
                        <label for="cnic_or_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            CNIC / National Identity Card <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-slate-400 font-bold text-sm">🪪</span>
                            <input id="cnic_or_id" 
                                   name="cnic_or_id" 
                                   type="text" 
                                   value="{{ old('cnic_or_id') }}" 
                                   placeholder="35202-1234567-1" 
                                   required 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 text-slate-900 font-mono font-bold focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-base">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Matches Nadra CNIC / B-Form number for legal lease agreement.</p>
                        <x-input-error :messages="$errors->get('cnic_or_id')" class="mt-1" />
                    </div>

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100 gap-3">
                        <a href="{{ route('tenants.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 sm:flex-none py-3 px-8 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition active:scale-95 text-center">
                            Save Tenant Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
