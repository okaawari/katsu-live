<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Profile Header -->
        <div class="relative bg-gradient-to-r from-blue-600 to-purple-600">
            <!-- Cover Image Background -->
            <div class="absolute inset-0">
                <img src="{{ $user->coverImage() }}" alt="Cover Image" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black opacity-40"></div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-8">
                    <!-- Profile Picture -->
                    <div class="relative">
                        <img class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-lg object-cover" 
                             src="{{ $user->avatar() }}" 
                             alt="{{ $user->name }}'s profile picture">
                        @if($user->created_at->diffInDays(now()) < 7)
                            <div class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                New
                            </div>
                        @endif
                    </div>
                    
                    <!-- Profile Info -->
                    <div class="text-center md:text-left text-white">
                        <h1 class="text-3xl md:text-4xl font-bold">{{ $user->name }}</h1>
                        @if($user->bio)
                            <p class="mt-2 text-lg opacity-90">{{ $user->bio }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-4 text-sm">
                            @if($user->location)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $user->location }}
                                </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Member since {{ $user->created_at->format('M Y') }}
                            </div>
                        </div>
                        
                        @if($isOwnProfile)
                            <div class="mt-4">
                                <a href="{{ route('profile.settings') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Edit Profile
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-sm">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_watched'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Episodes Watched</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_anime'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Anime Created</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-sm">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['total_comments'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Comments</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-sm">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['total_ratings'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ratings</div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button class="border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 py-4 px-1 text-sm font-medium" 
                                onclick="showTab('watching')">
                            Recently Watching
                        </button>
                        @if($userAnime->count() > 0)
                        <button class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 py-4 px-1 text-sm font-medium" 
                                onclick="showTab('anime')">
                            Created Anime
                        </button>
                        @endif
                    </nav>
                </div>

                <!-- Recently Watching Tab -->
                <div id="watching-tab" class="p-6">
                    @if($recentWatching->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($recentWatching as $progress)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start space-x-3">
                                        <img class="w-16 h-20 object-cover rounded" 
                                             src="{{ asset('images/poster.jpg') }}" 
                                             alt="{{ $progress->anime->name ?? 'Anime' }}">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $progress->anime->name ?? 'Unknown Anime' }}
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Episode {{ $progress->episode_number ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $progress->updated_at->diffForHumans() }}
                                            </p>
                                            @if($progress->progress_percentage)
                                                <div class="mt-2">
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1">
                                                        <div class="bg-blue-600 h-1 rounded-full" style="width: {{ $progress->progress_percentage }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ round($progress->progress_percentage) }}%</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start watching some anime to see your activity here.</p>
                        </div>
                    @endif
                </div>

                <!-- Created Anime Tab -->
                @if($userAnime->count() > 0)
                <div id="anime-tab" class="p-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($userAnime as $anime)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start space-x-3">
                                    <img class="w-16 h-20 object-cover rounded" 
                                         src="{{ asset('images/poster.jpg') }}" 
                                         alt="{{ $anime->name ?? 'Anime' }}">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $anime->name ?? 'Unknown Anime' }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Created {{ $anime->created_at->diffForHumans() }}
                                        </p>
                                        @if($anime->episodes_count)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $anime->episodes_count }} episodes
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('[id$="-tab"]').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active state from all buttons
            document.querySelectorAll('nav button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Add active state to clicked button
            event.target.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            event.target.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
        }
    </script>
</x-app-layout>
