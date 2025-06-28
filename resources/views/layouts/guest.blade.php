<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

            <div class="relative z-10">
                <a href="/" class="flex items-center space-x-3 my-8">
                    {{-- <div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl">R</span>
                    </div> --}}
                    <span class="font-bold text-2xl text-gray-900">Rentalin</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-xl border border-gray-200 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
