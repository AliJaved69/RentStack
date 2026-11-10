<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RentStack') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 selection:bg-emerald-500 selection:text-white">
        <div class="min-h-screen flex flex-col justify-center items-center pt-safe pb-safe px-4 py-8 relative overflow-hidden">
            <!-- Background decorative ambient lighting -->
            <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full bg-emerald-200/40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-teal-200/40 blur-3xl pointer-events-none"></div>

            <div class="w-full max-w-md relative z-10">
                <div class="text-center mb-6">
                    <a href="/" class="inline-flex items-center gap-2.5 group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold tracking-tight text-slate-900">Rent<span class="text-emerald-600">Stack</span></span>
                    </a>
                </div>

                <div class="bg-white/95 backdrop-blur-md px-6 py-8 sm:px-8 sm:py-9 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100">
                    {{ $slot }}
                </div>

                <div class="text-center mt-6 text-xs text-slate-400">
                    &copy; {{ date('Y') }} RentStack &bull; Mobile Offline-Ready
                </div>
            </div>
        </div>
    </body>
</html>
