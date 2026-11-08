<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Create your Account</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Start tracking your properties & tenants locally</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="name" class="block w-full pl-10 pr-4 py-2.5 sm:py-3 bg-gray-50 border border-gray-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 rounded-xl text-sm transition" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" class="block w-full pl-10 pr-4 py-2.5 sm:py-3 bg-gray-50 border border-gray-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 rounded-xl text-sm transition" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="john@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" class="block w-full pl-10 pr-4 py-2.5 sm:py-3 bg-gray-50 border border-gray-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 rounded-xl text-sm transition"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="Minimum 8 characters" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input id="password_confirmation" class="block w-full pl-10 pr-4 py-2.5 sm:py-3 bg-gray-50 border border-gray-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 rounded-xl text-sm transition"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Re-type password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md shadow-indigo-200 transition duration-150">
                <span>Create Free Account</span>
                <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

        <div class="text-center pt-4 border-t border-gray-100 text-xs sm:text-sm text-gray-500">
            Already registered?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition ms-1">
                Sign in
            </a>
        </div>
    </form>
</x-guest-layout>
