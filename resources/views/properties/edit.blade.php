<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Edit Property') }}: {{ $property->title }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Update building details, photo, and location</p>
            </div>
            <a href="{{ route('properties.show', $property) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                ← Back to Details
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200/80 p-6 sm:p-8">
                <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if(auth()->user()->role === 'super_admin' && count($owners) > 0)
                    <div>
                        <x-input-label for="owner_id" :value="__('Owner')" class="font-semibold text-slate-700" />
                        <select name="owner_id" id="owner_id" class="w-full mt-1.5 border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-xs">
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('owner_id', $property->owner_id) == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('owner_id')" class="mt-2" />
                    </div>
                    @endif

                    <div>
                        <x-input-label for="title" :value="__('Property / Building Title')" class="font-semibold text-slate-700" />
                        <x-text-input id="title" class="block mt-1.5 w-full rounded-xl" type="text" name="title" :value="old('title', $property->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Photo Upload with Mobile Preview -->
                    <div x-data="{ photoName: null, photoPreview: null }">
                        <x-input-label for="image" :value="__('Change Property Photo')" class="font-semibold text-slate-700" />
                        
                        <!-- New Preview (if selected) -->
                        <div x-show="photoPreview" class="mt-2 mb-3">
                            <span class="block h-44 rounded-2xl bg-cover bg-center border-2 border-emerald-500 shadow-sm"
                                  :style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                            <div class="flex justify-between items-center mt-1.5 px-1">
                                <span class="text-xs text-emerald-700 font-semibold" x-text="photoName"></span>
                                <button type="button" @click="photoPreview = null; photoName = null; $refs.editPhotoInput.value = ''" class="text-xs text-rose-600 font-bold hover:underline">
                                    ✕ Cancel change
                                </button>
                            </div>
                        </div>

                        <!-- Current Photo (if no new photo chosen yet) -->
                        @if($property->image_url)
                            <div x-show="!photoPreview" class="mt-2 mb-3">
                                <img src="{{ $property->image_url }}" alt="{{ $property->title }}" class="h-44 w-full object-cover rounded-2xl border border-slate-200 shadow-xs">
                                <span class="text-xs text-slate-500 mt-1 block">Current photo shown above (stored on device).</span>
                            </div>
                        @endif

                        <input id="image" 
                               name="image" 
                               x-ref="editPhotoInput"
                               type="file" 
                               accept="image/*" 
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                               @change="
                                    photoName = $refs.editPhotoInput.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL($refs.editPhotoInput.files[0]);
                               ">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Address')" class="font-semibold text-slate-700" />
                        <textarea id="address" name="address" class="block mt-1.5 w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-xs" rows="3" required>{{ old('address', $property->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <input type="hidden" name="status" value="{{ $property->status }}">

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 py-2.5 px-6 rounded-xl font-semibold shadow-xs">
                            {{ __('Save Changes') }}
                        </x-primary-button>
                        <a href="{{ route('properties.show', $property) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
