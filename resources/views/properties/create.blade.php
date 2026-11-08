<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Add New Property / Building') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Add a building or plaza to organize portions, shops, and units</p>
            </div>
            <a href="{{ route('properties.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back to Properties
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6 sm:p-8">
                <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @if(auth()->user()->role === 'super_admin' && count($owners) > 0)
                    <div>
                        <x-input-label for="owner_id" :value="__('Property Owner')" class="font-semibold text-slate-700" />
                        <select name="owner_id" id="owner_id" class="w-full mt-1.5 border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-xs">
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('owner_id')" class="mt-2" />
                    </div>
                    @endif

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Property / Building Name')" class="font-semibold text-slate-700" />
                        <x-text-input id="title" class="block mt-1.5 w-full rounded-xl" type="text" name="title" :value="old('title')" placeholder="e.g. Main Market Plaza, Gulberg House" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Photo Upload with Instant Mobile Preview -->
                    <div x-data="{ photoName: null, photoPreview: null }">
                        <x-input-label for="image" :value="__('Property Photo (Optional)')" class="font-semibold text-slate-700" />
                        
                        <!-- Preview Box (shown after selecting image) -->
                        <div x-show="photoPreview" class="mt-2 mb-3">
                            <span class="block h-48 rounded-2xl bg-cover bg-center border-2 border-emerald-500 shadow-sm"
                                  :style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                            <div class="flex justify-between items-center mt-1.5 px-1">
                                <span class="text-xs text-emerald-700 font-semibold" x-text="photoName"></span>
                                <button type="button" @click="photoPreview = null; photoName = null; $refs.photoInput.value = ''" class="text-xs text-rose-600 font-bold hover:underline">
                                    ✕ Remove photo
                                </button>
                            </div>
                        </div>

                        <!-- Upload Box -->
                        <div x-show="!photoPreview" 
                             @click="$refs.photoInput.click()"
                             class="mt-1.5 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl hover:border-emerald-500 transition cursor-pointer bg-slate-50/50 hover:bg-emerald-50/20 active:scale-[0.99]">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-10 w-10 text-emerald-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-sm text-slate-600">
                                    <span class="font-bold text-emerald-700">Tap to take photo or choose from gallery</span>
                                    <input id="image" 
                                           name="image" 
                                           x-ref="photoInput"
                                           type="file" 
                                           accept="image/*" 
                                           class="sr-only"
                                           @change="
                                                photoName = $refs.photoInput.files[0].name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => { photoPreview = e.target.result; };
                                                reader.readAsDataURL($refs.photoInput.files[0]);
                                           ">
                                    <span class="text-xs text-slate-400 block mt-1">Stored safely on device storage</span>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div>
                        <x-input-label for="address" :value="__('Full Address / Location')" class="font-semibold text-slate-700" />
                        <textarea id="address" name="address" class="block mt-1.5 w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-xs" rows="3" placeholder="e.g. Green Town Sarafa Market, Lahore" required>{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <input type="hidden" name="status" value="occupied">

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 py-2.5 px-6 rounded-xl font-semibold shadow-xs">
                            {{ __('Save Property & Add Units') }}
                        </x-primary-button>
                        <a href="{{ route('properties.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
