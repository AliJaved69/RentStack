<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Property') }}: {{ $property->title }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <form method="POST" action="{{ route('properties.update', $property) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if(auth()->user()->role === 'super_admin' && count($owners) > 0)
                    <div>
                        <x-input-label for="owner_id" :value="__('Owner')" class="text-sm font-medium text-gray-700" />
                        <select name="owner_id" id="owner_id" class="mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm py-2.5">
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
                        <x-input-label for="title" :value="__('Property Title')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="title" class="block mt-1 w-full rounded-xl py-2.5" type="text" name="title" :value="old('title', $property->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Address')" class="text-sm font-medium text-gray-700" />
                        <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" rows="3" required>{{ old('address', $property->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status')" class="text-sm font-medium text-gray-700" />
                        <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm py-2.5">
                            <option value="vacant" {{ old('status', $property->status) === 'vacant' ? 'selected' : '' }}>Vacant</option>
                            <option value="occupied" {{ old('status', $property->status) === 'occupied' ? 'selected' : '' }}>Occupied</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                            Cancel
                        </a>
                        <x-primary-button class="rounded-xl px-6 py-2.5 shadow-md shadow-indigo-100">
                            {{ __('Update Property') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

