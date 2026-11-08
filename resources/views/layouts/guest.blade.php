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
    <body class="font-sans text-gray-900 antialiased bg-slate-50 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col justify-center items-center pt-safe pb-safe px-4 py-8 relative overflow-hidden">
            <!-- Background decorative ambient lighting -->
            <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full bg-indigo-200/40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-emerald-200/40 blur-3xl pointer-events-none"></div>

            <div class="w-full max-w-md relative z-10">
                <div class="text-center mb-6">
                    <a href="/" class="inline-flex items-center gap-2.5 group">
                        <x-application-logo class="w-12 h-12 shadow-lg shadow-indigo-200 rounded-xl group-hover:scale-105 transition" />
                        <span class="text-2xl font-bold tracking-tight text-gray-900">Rent<span class="text-indigo-600">Stack</span></span>
                    </a>
                </div>

                <div class="bg-white/95 backdrop-blur-md px-6 py-8 sm:px-8 sm:py-9 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100">
                    {{ $slot }}
                </div>

                <div class="text-center mt-6 text-xs text-gray-400">
                    &copy; {{ date('Y') }} RentStack &bull; Mobile Offline-Ready
                </div>
            </div>
        </div>
    </body>
</html>
