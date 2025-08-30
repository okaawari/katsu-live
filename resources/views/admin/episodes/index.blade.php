@extends('layouts.admin')

@section('page-title', 'Episode Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Episode List</h2>
        <a href="{{ route('admin.episodes.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Episode
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 lg:p-6">
        <form method="GET" action="{{ route('admin.episodes.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="sm:col-span-2 lg:col-span-1">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by title, episode number, anime name, ID..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                
                <div>
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-green-500 focus:border-green-500 rounded-md">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div>
                    <label for="anime_id" class="sr-only">Anime</label>
                    <select name="anime_id" id="anime_id" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-green-500 focus:border-green-500 rounded-md">
                        <option value="">All Anime</option>
                        @foreach($anime_list as $anime)
                            <option value="{{ $anime->id }}" {{ request('anime_id') == $anime->id ? 'selected' : '' }}>
                                {{ $anime->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Search
                </button>

                @if(request()->hasAny(['search', 'status', 'anime_id']))
                    <a href="{{ route('admin.episodes.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Episodes Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Episode
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Visibility
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
                    @forelse($episodes as $episode)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 flex-shrink-0">
                                        @if($episode->poster_image)
                                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ asset('storage/poster/' . $episode->poster_image) }}" alt="Episode {{ $episode->episode_number }}">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $episode->episode_number }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            Episode {{ $episode->episode_number }}
                                        </div>
                                        @if($episode->title)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($episode->title, 30) }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-400">
                                            ID: {{ $episode->id }}
                                        </div>
                                    </div>
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
                                @if($episode->scheduled_at && $episode->status === 'scheduled')
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $episode->scheduled_at->format('M d, Y g:i A') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($episode->visibility === 'public') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                    @elseif($episode->visibility === 'premium') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                    @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                    @endif">
                                    {{ ucfirst($episode->visibility) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $episode->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <!-- View -->
                                    <a href="{{ route('admin.episodes.show', $episode) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-100 text-blue-600 
                                              hover:bg-blue-200 dark:bg-blue-900/40 dark:text-blue-400 dark:hover:bg-blue-800/60 
                                              transition">
                                        View
                                    </a>
                            
                                    <!-- Edit -->
                                    <a href="{{ route('admin.episodes.edit', $episode) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-100 text-indigo-600 
                                              hover:bg-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-400 dark:hover:bg-indigo-800/60 
                                              transition">
                                        Edit
                                    </a>
                            
                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.episodes.destroy', $episode) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this episode? This will also delete all associated files.')"
                                                class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-600 
                                                       hover:bg-red-200 dark:bg-red-900/40 dark:text-red-400 dark:hover:bg-red-800/60 
                                                       transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 001.5-1.5V7a1.5 1.5 0 00-1.5-1.5H9"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No episodes found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new episode.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.episodes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Episode
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($episodes->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $episodes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
