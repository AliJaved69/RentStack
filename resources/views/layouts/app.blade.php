<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RentStack') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-emerald-500 selection:text-white">
        <div class="min-h-screen flex flex-col justify-between">
            <div>
                @include('layouts.navigation')

                <!-- Global Notifications -->
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm flex items-center justify-between shadow-xs">
                            <div class="flex items-center space-x-2.5">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div x-data="{ show: true }" x-show="show" class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm flex items-center justify-between shadow-xs">
                            <div class="flex items-center space-x-2.5">
                                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-rose-500 hover:text-rose-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-slate-200/60 shadow-xs">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="pb-28 md:pb-10">
                    {{ $slot }}
                </main>
            </div>
            
            <!-- Mobile Bottom Navigation Bar (Persistent Thumb Navigation) -->
            <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-lg border-t border-slate-200/90 z-40 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] pt-1 pb-[max(env(safe-area-inset-bottom,0px),0.6rem)]">
                <div class="grid grid-cols-5 items-center justify-around px-1 max-w-lg mx-auto">
                    
                    <!-- 1. Home / Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-90 {{ request()->routeIs('dashboard') ? 'text-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                        <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('dashboard') ? '2.5' : '1.8' }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5">Home</span>
                    </a>

                    <!-- 2. Properties -->
                    <a href="{{ route('properties.index') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-90 {{ request()->routeIs('properties.*') ? 'text-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                        <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('properties.*') ? 'text-emerald-600' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('properties.*') ? '2.5' : '1.8' }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5">Properties</span>
                    </a>

                    <!-- 3. Tenants -->
                    <a href="{{ route('tenants.index') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-90 {{ request()->routeIs('tenants.*') ? 'text-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                        <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('tenants.*') ? 'text-emerald-600' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('tenants.*') ? '2.5' : '1.8' }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5">Tenants</span>
                    </a>

                    <!-- 4. Leases -->
                    <a href="{{ route('leases.index') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-90 {{ request()->routeIs('leases.*') ? 'text-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                        <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('leases.*') ? 'text-emerald-600' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('leases.*') ? '2.5' : '1.8' }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5">Leases</span>
                    </a>

                    <!-- 5. Payments -->
                    <a href="{{ route('payments.index') }}" class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-90 {{ request()->routeIs('payments.*') ? 'text-emerald-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                        <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('payments.*') ? 'text-emerald-600' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('payments.*') ? '2.5' : '1.8' }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5">Payments</span>
                    </a>
                </div>
            </nav>
        </div>
    </body>
</html>
