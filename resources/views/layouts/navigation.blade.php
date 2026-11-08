<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 pt-safe">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left: Logo & Brand -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">RentStack</span>
                        <div class="hidden sm:inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>
                            Local On-Device
                        </div>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2 ms-6">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('properties.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('properties.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ __('Properties') }}
                    </a>
                    <a href="{{ route('tenants.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('tenants.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ __('Tenants') }}
                    </a>
                    <a href="{{ route('leases.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('leases.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ __('Leases') }}
                    </a>
                    <a href="{{ route('payments.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('payments.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ __('Payments') }}
                    </a>
                    @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('ledger.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('ledger.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ __('Ledger') }}
                    </a>
                    @endif
                </div>
            </div>

            <!-- Right: Desktop Sync & Profile -->
            <div class="hidden md:flex items-center space-x-3">
                <form method="POST" action="{{ route('properties.export-bundle') }}" class="inline">
                    @csrf
                    <button type="submit" title="Sync current laptop database to the APK bundle file" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition shadow-xs active:scale-95">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Sync Data to APK
                    </button>
                </form>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150 shadow-xs">
                            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs mr-2">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 ml-1.5 text-slate-400" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-slate-400 border-b border-slate-100">
                            Signed in as <span class="font-semibold text-slate-700">{{ Auth::user()->email }}</span>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:text-red-700 hover:bg-red-50">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" 
                        type="button"
                        aria-label="Toggle navigation menu"
                        class="p-2.5 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200/80 active:scale-95 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 transition duration-150">
                    <svg class="h-6 w-6 transition-transform duration-200" :class="{'rotate-90': open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer / Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200 transform" 
         x-transition:enter-start="opacity-0 -translate-y-2" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-150 transform" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.outside="open = false"
         class="md:hidden border-t border-slate-200/80 bg-white/98 backdrop-blur-lg shadow-2xl px-4 pt-3 pb-safe divide-y divide-slate-100">

        <!-- Mobile User Info Header Card -->
        <div class="pb-3">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-4 shadow-md">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-bold text-lg flex items-center justify-center ring-2 ring-emerald-400/40 shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-base text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-300 truncate">{{ Auth::user()->email }}</div>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'admin')) }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-white/10 text-slate-300">
                                📱 On-Device SQLite
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links with Touch Targets and Icons -->
        <div class="py-3 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-base font-medium transition active:scale-[0.98] {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <!-- Properties -->
            <a href="{{ route('properties.index') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-base font-medium transition active:scale-[0.98] {{ request()->routeIs('properties.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('properties.*') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span>{{ __('Properties') }}</span>
            </a>

            <!-- Tenants -->
            <a href="{{ route('tenants.index') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-base font-medium transition active:scale-[0.98] {{ request()->routeIs('tenants.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('tenants.*') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span>{{ __('Tenants') }}</span>
            </a>

            <!-- Leases -->
            <a href="{{ route('leases.index') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-base font-medium transition active:scale-[0.98] {{ request()->routeIs('leases.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('leases.*') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span>{{ __('Leases') }}</span>
            </a>

            <!-- Payments -->
            <a href="{{ route('payments.index') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-base font-medium transition active:scale-[0.98] {{ request()->routeIs('payments.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('payments.*') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span>{{ __('Payments') }}</span>
            </a>

            <!-- Ledger (Super Admin) -->
            @if(auth()->user()->role === 'super_admin')
            <a href="{{ route('ledger.index') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-base font-medium transition active:scale-[0.98] {{ request()->routeIs('ledger.*') ? 'bg-emerald-50 text-emerald-800 font-bold border-l-4 border-emerald-600 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('ledger.*') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span>{{ __('Ledger') }}</span>
            </a>
            @endif
        </div>

        <!-- Sync Button Card (Quick action for laptop setup) -->
        <div class="py-3">
            <form method="POST" action="{{ route('properties.export-bundle') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-between p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 hover:bg-emerald-100 active:scale-[0.99] transition shadow-xs text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-emerald-900">Sync Data to Mobile APK</div>
                            <div class="text-xs text-emerald-600">Bundle laptop records for APK install</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Settings & Logout -->
        <div class="py-3 space-y-1">
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center space-x-3 px-3.5 py-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition active:scale-[0.98]">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span>{{ __('Profile Settings') }}</span>
            </a>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="w-full flex items-center space-x-3 px-3.5 py-3 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition active:scale-[0.98] text-left">
                    <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <span>{{ __('Log Out') }}</span>
                </button>
            </form>
        </div>

        <!-- Safe Bottom Spacing to ensure device navigation bar is respected -->
        <div class="h-4 pb-safe"></div>
    </div>
</nav>
