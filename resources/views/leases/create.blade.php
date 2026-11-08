<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Create Lease Agreement') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Assign tenant to a portion or shop, set rent and security advance</p>
            </div>
            <a href="{{ route('leases.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back
            </a>
        </div>
    </x-slot>

    @php
        $propertiesData = $properties->map(function($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'address' => $p->address,
                'units' => $p->units->map(function($u) {
                    return [
                        'id' => $u->id,
                        'name' => $u->name,
                        'type' => $u->type,
                        'default_rent' => (float) $u->default_rent,
                    ];
                })->values(),
            ];
        });
    @endphp

    <div class="py-6 sm:py-10" 
         x-data="{
            properties: {{ Js::from($propertiesData) }},
            selectedPropertyId: '{{ old('property_id') }}',
            selectedUnitId: '{{ old('unit_id') }}',
            baseRent: '{{ old('base_rent', '') }}',
            securityDeposit: '{{ old('security_deposit_expected', '') }}',
            securityPaid: '{{ old('security_deposit_paid', '0') }}',
            
            get currentProperty() {
                return this.properties.find(p => p.id == this.selectedPropertyId) || null;
            },

            get availableUnits() {
                return this.currentProperty ? this.currentProperty.units : [];
            },

            onPropertyChange() {
                this.selectedUnitId = '';
                if (this.availableUnits.length > 0) {
                    this.onUnitChange(this.availableUnits[0].id);
                }
            },

            onUnitChange(unitId) {
                this.selectedUnitId = unitId;
                const unit = this.availableUnits.find(u => u.id == unitId);
                if (unit && unit.default_rent > 0) {
                    this.baseRent = unit.default_rent;
                    this.securityDeposit = unit.default_rent * 2;
                }
            },

            updateSecurity() {
                if (this.baseRent && (!this.securityDeposit || this.securityDeposit == 0)) {
                    this.securityDeposit = Number(this.baseRent) * 2;
                }
            }
         }">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xs rounded-3xl border border-slate-200/90 p-6 sm:p-8">
                <form method="POST" action="{{ route('leases.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- 1. Property / Building Selection -->
                    <div>
                        <label for="property_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Property / Building <span class="text-rose-500">*</span>
                        </label>
                        <select name="property_id" 
                                id="property_id" 
                                x-model="selectedPropertyId" 
                                @change="onPropertyChange()"
                                class="w-full rounded-2xl border-slate-200 text-slate-900 font-bold focus:border-emerald-500 focus:ring-emerald-500 p-3 shadow-2xs" 
                                required>
                            <option value="">-- Choose Property / Building --</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                    🏢 {{ $property->title }} ({{ $property->address }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                    </div>

                    <!-- 2. Portion / Shop Selection (Dynamic) -->
                    <div x-show="selectedPropertyId">
                        <label for="unit_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Portion / Shop / Unit <span class="text-rose-500">*</span>
                        </label>
                        <template x-if="availableUnits.length > 0">
                            <select name="unit_id" 
                                    id="unit_id" 
                                    x-model="selectedUnitId" 
                                    @change="onUnitChange($event.target.value)"
                                    class="w-full rounded-2xl border-slate-200 text-slate-900 font-bold focus:border-emerald-500 focus:ring-emerald-500 p-3 shadow-2xs">
                                <option value="">-- Select Portion / Shop --</option>
                                <template x-for="unit in availableUnits" :key="unit.id">
                                    <option :value="unit.id" x-text="'🚪 ' + unit.name + ' (' + unit.type + ') - Default: Rs. ' + Number(unit.default_rent).toLocaleString()"></option>
                                </template>
                            </select>
                        </template>
                        <template x-if="availableUnits.length === 0">
                            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800">
                                ⚠️ No vacant portions/shops found under this property. The lease will apply to the whole property, or you can add a portion first under the property details page.
                            </div>
                        </template>
                        <x-input-error :messages="$errors->get('unit_id')" class="mt-2" />
                    </div>

                    <!-- 3. Tenant Selection -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="tenant_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Tenant Name <span class="text-rose-500">*</span>
                            </label>
                            <a href="{{ route('tenants.create') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                + Add New Tenant
                            </a>
                        </div>
                        <select name="tenant_id" id="tenant_id" class="w-full rounded-2xl border-slate-200 text-slate-900 font-bold focus:border-emerald-500 focus:ring-emerald-500 p-3 shadow-2xs" required>
                            <option value="">-- Select Tenant --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    👤 {{ $tenant->name }} ({{ $tenant->phone }}) - CNIC: {{ $tenant->cnic_or_id }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tenant_id')" class="mt-2" />
                    </div>

                    <!-- 4. Monthly Rent & Security Deposit Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <!-- Monthly Base Rent -->
                        <div>
                            <label for="base_rent" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Monthly Base Rent (Rs.) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3.5 text-slate-400 font-bold text-sm">Rs.</span>
                                <input type="number" 
                                       step="1" 
                                       name="base_rent" 
                                       id="base_rent" 
                                       x-model="baseRent" 
                                       @input="updateSecurity()"
                                       class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 text-lg font-black text-slate-900 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs" 
                                       placeholder="25000" 
                                       required>
                            </div>
                            <x-input-error :messages="$errors->get('base_rent')" class="mt-1" />
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Agreement Start Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   value="{{ old('start_date', date('Y-m-d')) }}" 
                                   class="w-full py-3 px-4 rounded-2xl border-slate-200 text-base font-bold text-slate-800 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs" 
                                   required>
                            <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                        </div>
                    </div>

                    <!-- 5. Security Deposit (Advance) Section -->
                    <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Security Deposit / Advance (Standard 2x Rent)
                            </span>
                            <span class="text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded">
                                Auto-calculated (2x)
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Expected Total -->
                            <div>
                                <label for="security_deposit_expected" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Total Security Expected (Rs.)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-xs">Rs.</span>
                                    <input type="number" 
                                           step="1" 
                                           name="security_deposit_expected" 
                                           id="security_deposit_expected" 
                                           x-model="securityDeposit" 
                                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border-slate-200 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Usually 2 months rent</span>
                            </div>

                            <!-- Already Paid Advance -->
                            <div>
                                <label for="security_deposit_paid" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Advance Paid Today (Rs.)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-xs">Rs.</span>
                                    <input type="number" 
                                           step="1" 
                                           name="security_deposit_paid" 
                                           id="security_deposit_paid" 
                                           x-model="securityPaid" 
                                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border-slate-200 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-emerald-500" 
                                           placeholder="0">
                                </div>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">If 0, can be paid in installments later</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. E-Stamp Contract Document (Optional) -->
                    <div>
                        <label for="e_stamp" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            E-Stamp Agreement Document (Optional)
                        </label>
                        <input type="file" 
                               name="e_stamp" 
                               id="e_stamp" 
                               accept=".pdf,image/*" 
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 rounded-2xl bg-white p-1.5">
                        <p class="mt-1 text-[11px] text-slate-400">PDF or photo of rental stamp paper.</p>
                        <x-input-error :messages="$errors->get('e_stamp')" class="mt-1" />
                    </div>

                    <!-- Submit & Cancel -->
                    <div class="pt-4 flex items-center justify-between border-t border-slate-100 gap-3">
                        <a href="{{ route('leases.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 sm:flex-none py-3 px-8 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition active:scale-95 text-center">
                            Save Lease Agreement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
