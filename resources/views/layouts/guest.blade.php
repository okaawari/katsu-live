<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Background with anime-inspired gradient -->
        <div class="min-h-screen relative bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
            <!-- Animated background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3Ccircle cx="27" cy="7" r="1"/%3E%3Ccircle cx="47" cy="7" r="1"/%3E%3Ccircle cx="7" cy="27" r="1"/%3E%3Ccircle cx="27" cy="27" r="1"/%3E%3Ccircle cx="47" cy="27" r="1"/%3E%3Ccircle cx="7" cy="47" r="1"/%3E%3Ccircle cx="27" cy="47" r="1"/%3E%3Ccircle cx="47" cy="47" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>
            
            <!-- Main content -->
            <div class="relative min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
                <!-- Logo section -->
                <div class="mb-8 text-center">
                    <a href="/" wire:navigate class="inline-block group">
                        <div class="relative">
                            <x-application-logo class="w-16 h-16 sm:w-20 sm:h-20 fill-current text-white drop-shadow-lg transition-transform duration-300 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-white/20 rounded-full blur-xl -z-10 group-hover:bg-white/30 transition-colors duration-300"></div>
                        </div>
                        <h1 class="mt-4 text-2xl sm:text-3xl font-bold text-white drop-shadow-lg">
                            {{ config('app.name', 'KatsuLive') }}
                        </h1>
                        <p class="text-purple-200 text-sm mt-1">Your Anime Streaming Platform</p>
                    </a>
                </div>

                <!-- Auth card -->
                <div class="w-full max-w-md">
                    <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="px-8 py-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-purple-200 text-xs">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
