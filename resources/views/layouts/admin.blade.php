<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Katsu') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-sm flex-shrink-0">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ config('app.name') }} Admin
                    </h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.anime.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.anime.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4h10l2 13H5l2-13z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v8"/>
                        </svg>
                        Anime
                    </a>

                    <a href="{{ route('admin.episodes.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.episodes.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 001.5-1.5V7a1.5 1.5 0 00-1.5-1.5H9M15 10h1.5a1.5 1.5 0 011.5-1.5V7a1.5 1.5 0 00-1.5-1.5H15"/>
                        </svg>
                        Episodes
                    </a>

                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('home') }}" 
                           class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Site
                        </a>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ auth()->user()->name }}
                            </p>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        @yield('page-title', 'Admin Dashboard')
                    </h1>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>