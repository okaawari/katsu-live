@extends('layouts.admin')

@section('page-title', 'View Episode')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Episode {{ $episode->episode_number }} - {{ $episode->anime->title }}
        </h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.episodes.edit', $episode) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.episodes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <!-- Episode Details -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Images -->
                <div class="space-y-4">
                    @if($episode->poster_image)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poster Image</h4>
                            <img class="w-full h-48 object-cover rounded-lg" src="{{ asset('storage/poster/' . $episode->poster_image) }}" alt="Episode {{ $episode->episode_number }}">
                        </div>
                    @endif

                    @if($episode->thumbnail_image)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Thumbnail</h4>
                            <img class="w-full h-32 object-cover rounded-lg" src="{{ asset('storage/' . $episode->thumbnail_image) }}" alt="Episode {{ $episode->episode_number }} thumbnail">
                        </div>
                    @endif

                    @if(!$episode->poster_image && !$episode->thumbnail_image)
                        <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No images uploaded</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Basic Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Anime</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->anime->title }}</p>
                            @if($episode->anime->title_english)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $episode->anime->title_english }}</p>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Episode Number</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->episode_number }}</p>
                        </div>

                        @if($episode->title)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Episode Title</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->title }}</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($episode->status === 'published') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($episode->status === 'scheduled') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                @elseif($episode->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @endif">
                                {{ ucfirst($episode->status) }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Visibility</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($episode->visibility === 'public') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($episode->visibility === 'premium') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @endif">
                                {{ ucfirst($episode->visibility) }}
                            </span>
                        </div>

                        @if($episode->scheduled_at)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Scheduled At</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->scheduled_at->format('M d, Y g:i A') }}</p>
                            </div>
                        @endif

                        @if($episode->published_at)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Published At</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->published_at->format('M d, Y g:i A') }}</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $episode->is_featured ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                {{ $episode->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Premium</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $episode->is_premium ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                {{ $episode->is_premium ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploaded By</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->uploader->name ?? 'Unknown' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Created</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->created_at->format('M d, Y g:i A') }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Last Updated</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Synopsis -->
                    @if($episode->synopsis)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Synopsis</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $episode->synopsis }}</p>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($episode->tags->count() > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($episode->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        {{ $tag->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Files Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Files</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Video -->
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 001.5-1.5V7a1.5 1.5 0 00-1.5-1.5H9"/>
                        </svg>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Video (720p)</h4>
                    </div>
                    @if($episode->video_720p)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ basename($episode->video_720p) }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            Available
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                            Missing
                        </span>
                    @endif
                </div>

                <!-- Mongolian Subtitles -->
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Mongolian Subtitles</h4>
                    </div>
                    @if($episode->subtitle_mongolian)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ basename($episode->subtitle_mongolian) }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            Available
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                            Not Available
                        </span>
                    @endif
                </div>

                <!-- English Subtitles -->
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">English Subtitles</h4>
                    </div>
                    @if($episode->subtitle_english)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ basename($episode->subtitle_english) }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            Available
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                            Not Available
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Episodes -->
    @if($episode->anime->episodes->count() > 1)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Other Episodes from {{ $episode->anime->title }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($episode->anime->episodes->sortBy('episode_number')->take(8) as $otherEpisode)
                        @if($otherEpisode->id !== $episode->id)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center mb-2">
                                    @if($otherEpisode->poster_image)
                                        <img class="h-12 w-12 rounded object-cover flex-shrink-0" src="{{ asset('storage/' . $otherEpisode->poster_image) }}" alt="Episode {{ $otherEpisode->episode_number }}">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $otherEpisode->episode_number }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-3 min-w-0 flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            Episode {{ $otherEpisode->episode_number }}
                                        </h4>
                                        @if($otherEpisode->title)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $otherEpisode->title }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($otherEpisode->status === 'published') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($otherEpisode->status === 'scheduled') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @elseif($otherEpisode->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                        @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @endif">
                                        {{ ucfirst($otherEpisode->status) }}
                                    </span>
                                    <a href="{{ route('admin.episodes.show', $otherEpisode) }}" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
