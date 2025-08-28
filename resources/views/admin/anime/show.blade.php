@extends('layouts.admin')

@section('page-title', 'View Anime')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $anime->title }}</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.anime.edit', $anime) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.anime.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <!-- Anime Details -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Images -->
                <div class="space-y-4">
                    @if($anime->cover_image)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Image</h4>
                            <img class="w-full h-48 object-cover rounded-lg" src="{{ asset('storage/' . $anime->cover_image) }}" alt="{{ $anime->title }}">
                        </div>
                    @endif

                    @if($anime->poster)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poster</h4>
                            <img class="w-full h-48 object-cover rounded-lg" src="{{ asset('storage/' . $anime->poster) }}" alt="{{ $anime->title }}">
                        </div>
                    @endif

                    @if(!$anime->cover_image && !$anime->poster)
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
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Title</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->title }}</p>
                        </div>

                        @if($anime->title_english)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">English Title</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->title_english }}</p>
                            </div>
                        @endif

                        @if($anime->title_japanese)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Japanese Title</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->title_japanese }}</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Category</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->category->name ?? 'No Category' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($anime->status === 'ongoing') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($anime->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @elseif($anime->status === 'upcoming') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @endif">
                                {{ ucfirst($anime->status) }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Episodes</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->episodes()->count() }} / {{ $anime->total_episodes ?? '∞' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Visibility</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($anime->visibility === 'public') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($anime->visibility === 'private') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                @endif">
                                {{ ucfirst($anime->visibility) }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured</h4>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $anime->is_featured ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                {{ $anime->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        @if($anime->published_at)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Published At</h4>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->published_at->format('M d, Y g:i A') }}</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Created</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->created_at->format('M d, Y g:i A') }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Last Updated</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($anime->tags->count() > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($anime->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ $tag->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    @if($anime->description)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $anime->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Episodes -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Episodes ({{ $anime->episodes->count() }})</h3>
            <a href="{{ route('admin.episodes.create', ['anime_id' => $anime->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Episode
            </a>
        </div>
        
        @if($anime->episodes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Episode
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($anime->episodes->sortBy('episode_number') as $episode)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($episode->poster_image)
                                            <img class="h-10 w-10 rounded object-cover flex-shrink-0" src="{{ asset('storage/' . $episode->poster_image) }}" alt="Episode {{ $episode->episode_number }}">
                                        @else
                                            <div class="h-10 w-10 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center flex-shrink-0">
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $episode->episode_number }}</span>
                                            </div>
                                        @endif
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Episode {{ $episode->episode_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $episode->title ?: 'No title' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($episode->status === 'published') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($episode->status === 'scheduled') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @elseif($episode->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                        @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @endif">
                                        {{ ucfirst($episode->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $episode->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.episodes.show', $episode) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            View
                                        </a>
                                        <a href="{{ route('admin.episodes.edit', $episode) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 001.5-1.5V7a1.5 1.5 0 00-1.5-1.5H9"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No episodes</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding the first episode.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.episodes.create', ['anime_id' => $anime->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Episode
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection