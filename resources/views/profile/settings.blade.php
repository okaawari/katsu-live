<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
                        <p class="mt-1 md:mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400">Manage your account settings and preferences</p>
                    </div>
                    <a href="{{ route('profile.show', $user->id) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm md:text-base">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Profile
                    </a>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <div class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_watched'] }}</div>
                            <div class="text-xs md:text-sm text-gray-600 dark:text-gray-400">Episodes Watched</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"></path>
                            </svg>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <div class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_anime'] }}</div>
                            <div class="text-xs md:text-sm text-gray-600 dark:text-gray-400">Anime Created</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <div class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_comments'] }}</div>
                            <div class="text-xs md:text-sm text-gray-600 dark:text-gray-400">Comments</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <div class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_ratings'] }}</div>
                            <div class="text-xs md:text-sm text-gray-600 dark:text-gray-400">Ratings</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">


                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button class="border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 py-4 px-1 text-sm font-medium" 
                                onclick="showSettingsTab('profile')">
                            Profile Information
                        </button>
                        <button class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 py-4 px-1 text-sm font-medium" 
                                onclick="showSettingsTab('picture')">
                            Profile Picture
                        </button>
                        <button class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 py-4 px-1 text-sm font-medium" 
                                onclick="showSettingsTab('password')">
                            Password
                        </button>
                        <button class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 py-4 px-1 text-sm font-medium" 
                                onclick="showSettingsTab('sessions')">
                            Sessions
                        </button>
                    </nav>
                </div>

                <!-- Profile Information Tab -->
                <div id="profile-tab" class="p-4 md:p-6">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 md:mb-4">Profile Information</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-6">Update your account's profile information and email address.</p>
                        
                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 md:space-y-6" enctype="multipart/form-data">
                            @csrf
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label>
                                <textarea name="bio" id="bio" rows="3" 
                                          class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                    <input type="text" name="location" id="location" value="{{ old('location', $user->location) }}" 
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="City, Country">
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                    <input type="url" name="website" id="website" value="{{ old('website', $user->website) }}" 
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="https://example.com">
                                    @error('website')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date) }}" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>



                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Profile Picture Tab -->
                <div id="picture-tab" class="p-4 md:p-6 hidden">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 md:mb-4">Profile Picture & Cover Image</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-6">Update your account's profile picture and cover image.</p>
                        
                        <!-- Profile Picture Section -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Profile Picture</h4>
                            <livewire:profile.update-profile-picture-form />
                        </div>
                        
                        <!-- Cover Image Section -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Cover Image</h4>
                            <livewire:profile.update-cover-image-form />
                        </div>
                    </div>
                </div>



                <!-- Password Tab -->
                <div id="password-tab" class="p-4 md:p-6 hidden">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 md:mb-4">Update Password</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-6">Ensure your account is using a long, random password to stay secure.</p>
                        
                        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4 md:space-y-6">
                            @csrf
                            
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                                <input type="password" name="current_password" id="current_password" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                <input type="password" name="password" id="password" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sessions Tab -->
                <div id="sessions-tab" class="p-4 md:p-6 hidden">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 md:mb-4">Browser Sessions</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-6">Manage and log out your active sessions on other browsers and devices.</p>
                        
                        <livewire:profile.view-session-history-form />
                    </div>
                </div>


            </div>

            <!-- Recent Activity -->
            @if($recentActivity->count() > 0)
            <div class="mt-6 md:mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-4 md:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 md:mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-shrink-0">
                                    <img class="w-10 h-12 object-cover rounded" 
                                         src="{{ asset('images/poster.jpg') }}" 
                                         alt="{{ $activity->anime->name ?? 'Anime' }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        Watched {{ $activity->anime->name ?? 'Unknown Anime' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Episode {{ $activity->episode_number ?? 'N/A' }} • {{ $activity->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function showSettingsTab(tabName) {
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
            
            // Add active state to clicked button (only if it's a button click)
            if (event && event.target && event.target.tagName === 'BUTTON') {
                event.target.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                event.target.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            }
        }
    </script>
</x-app-layout>
