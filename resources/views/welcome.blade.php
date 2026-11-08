<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>{{ config('app.name', 'RentStack') }} - Property Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased font-sans selection:bg-indigo-500 selection:text-white flex flex-col justify-between min-h-screen pt-safe pb-safe relative overflow-x-hidden">
        <!-- Ambient Background Lights -->
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 -left-40 w-96 h-96 rounded-full bg-emerald-300/25 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 w-full">
            <!-- Header -->
            <header class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-application-logo class="w-10 h-10 sm:w-11 sm:h-11 shadow-md shadow-indigo-100 rounded-xl" />
                    <div>
                        <span class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">Rent<span class="text-indigo-600">Stack</span></span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition">
                            <span>Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-gray-700 hover:text-indigo-600 px-3 py-2 transition">
                            Sign in
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition">
                            Get Started
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Hero Section -->
            <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-16 pb-12 sm:pb-20">
                <div class="text-center max-w-3xl mx-auto space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/60 text-indigo-700 text-xs font-semibold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Mobile Native &bull; 100% Stored On-Device
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-[1.15]">
                        Rental Management, <br class="hidden sm:inline" />
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-indigo-700 to-emerald-600">
                            Fast, Private & Offline
                        </span>
                    </h1>

                    <p class="text-base sm:text-lg text-gray-600 max-w-xl mx-auto font-normal leading-relaxed">
                        Effortlessly track properties, generate tenant invoices, collect rent payments, and balance your ledger directly on your phone.
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-base rounded-2xl shadow-lg shadow-indigo-200 transition">
                                Enter App Dashboard
                                <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('properties.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-semibold text-base rounded-2xl shadow-sm transition">
                                View Properties
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-base rounded-2xl shadow-lg shadow-indigo-200 transition">
                                Start Managing Free
                                <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-semibold text-base rounded-2xl shadow-sm transition">
                                Existing Sign In
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Feature Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-14 sm:mt-24">
                    <!-- Feature 1 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition group">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Properties & Units</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                            Organize multiple properties, manage occupancy status, and configure owner assignments effortlessly.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-100 transition group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Leases & Tenants</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                            Track tenant CNIC/IDs, active lease terms, security deposits, and move-out deductions seamlessly.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition group">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Rent Payments</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                            Automatic monthly invoices, partial or full payment receipts, and automated 10% anniversary increments.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md hover:border-amber-100 transition group">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Local SQLite Ledger</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                            Everything is stored right inside your phone's storage. No external servers or monthly subscriptions required.
                        </p>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-gray-400">
            RentStack &bull; Powered by NativePHP Mobile & SQLite
        </footer>
    </body>
</html>
