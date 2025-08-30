<div class="">
    <!-- Search and Filter Section -->
    <div class="lg:flex grid grid-cols-6 justify-center my-8 gap-4 text-sm">
        <!-- Search Input -->
        <div class="col-span-2 min-w-full lg:min-w-[160px] w-full">
            <input 
                type="text" 
                placeholder="Хайх..." 
                wire:model.live.debounce.300ms="searchTerm" 
                wire:keydown.enter="submitSearch"
                class="w-full h-full bg-slate-800 text-gray-200 px-5 py-2.5 border-slate-800 rounded-md focus:border-transparent focus:ring-0" 
            />
        </div>

        <!-- Year Filter -->
        <div x-data="{ open: false, query: '', selected: @entangle('selectedYear') }" class="relative col-span-2 order-4 lg:order-2 min-w-full lg:min-w-[160px] w-full">
            <button 
                @click="open = !open" class="w-full bg-slate-800 text-gray-300 px-3 py-2.5 rounded-md flex justify-between items-center">
                <span x-text="selected ? selected : 'Он'"></span>
                <div class="flex items-center gap-2">
                    <span x-show="selected" 
                        @click.stop="selected = ''; $wire.set('selectedYear', ''); query = ''" 
                        class="cursor-pointer text-red-500 hover:text-red-700"
                        title="Clear selection">
                        &times;
                    </span>
                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            <div x-show="open" @click.away="open = false" class="dropdown-scroll absolute mt-2 w-full bg-slate-800 rounded-md shadow-lg z-50">
                <div class="p-2">
                    <input type="text" 
                        x-model="query" 
                        placeholder="Search year..." 
                        class="w-full px-3 py-2 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-gray-600">
                </div>
                <ul class="max-h-72 overflow-y-auto">
                    @foreach ($years as $year)
                        <li 
                            x-show="!query || '{{ strtolower($year) }}'.includes(query.toLowerCase())"
                            @click="selected = '{{ $year }}'; $wire.set('selectedYear', selected); open = false"
                            class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                        >
                            {{ $year }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Tag Filter -->
        <div x-data="{ open: false, query: '', selected: @entangle('selectedTag') }" class="relative col-span-2 order-5 lg:order-3 min-w-full lg:min-w-[160px] w-full">
            <button 
                @click="open = !open" class="w-full bg-slate-800 text-gray-300 px-3 py-2.5 rounded-md flex justify-between items-center">
                <span x-text="selected ? selected : 'Төрөл'"></span>
                <div class="flex items-center gap-2">
                    <span x-show="selected" 
                        @click.stop="selected = ''; $wire.set('selectedTag', ''); query = ''" 
                        class="cursor-pointer text-red-500 hover:text-red-700"
                        title="Clear selection">
                        &times;
                    </span>
                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            <div x-show="open" @click.away="open = false" class="dropdown-scroll absolute mt-2 w-full bg-slate-800 rounded-md shadow-lg z-50">
                <div class="p-2">
                    <input type="text" 
                        x-model="query" 
                        placeholder="Төрлөөс хайх..." 
                        class="w-full px-3 py-2 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-gray-600">
                </div>
                <ul class="max-h-72 overflow-y-auto">
                    @foreach ($tags as $tag)
                        <li 
                            x-show="!query || '{{ strtolower($tag) }}'.includes(query.toLowerCase())"
                            @click="selected = '{{ $tag }}'; $wire.set('selectedTag', selected); open = false"
                            class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                        >
                            {{ $tag }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Category Filter -->
        <div x-data="{ open: false, query: '', selected: @entangle('selectedStatus') }" class="relative col-span-2 order-6 lg:order-4 min-w-full lg:min-w-[160px] w-full">
            <button @click="open = !open" class="w-full bg-slate-800 text-gray-300 px-3 py-2.5 rounded-md flex justify-between items-center">
                <span x-text="selected ? '{{ $categories->firstWhere('id', $selectedStatus)->name ?? 'Unknown' }}' : 'Ангилал'"></span>
                <div class="flex items-center gap-2">
                    <span x-show="selected" 
                        @click.stop="selected = ''; $wire.set('selectedStatus', ''); query = ''" 
                        class="cursor-pointer text-red-500 hover:text-red-700"
                        title="Clear selection">
                        &times;
                    </span>
                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            <div x-show="open" @click.away="open = false" class="dropdown-scroll absolute mt-2 w-full bg-slate-800 rounded-md shadow-lg z-50">
                <div class="p-2">
                    <input type="text" 
                        x-model="query" 
                        placeholder="Search category..." 
                        class="w-full px-3 py-2 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-gray-600">
                </div>
                <ul class="max-h-72 overflow-y-auto">
                    @foreach ($categories as $category)
                        <li 
                            x-show="!query || '{{ strtolower($category->name) }}'.includes(query.toLowerCase())"
                            @click="selected = '{{ $category->id }}'; $wire.set('selectedStatus', '{{ $category->id }}'); open = false" 
                            class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                        >
                            {{ $category->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Studio Filter -->
        <div x-data="{ open: false, query: '', selected: @entangle('selectedStudio') }" class="relative col-span-2 order-7 lg:order-5 min-w-full lg:min-w-[160px] w-full">
            <button @click="open = !open" class="w-full bg-slate-800 text-gray-300 px-3 py-2.5 rounded-md flex justify-between items-center">
                <span x-text="selected ? selected : 'Студи'"></span>
                <div class="flex items-center gap-2">
                    <span x-show="selected" 
                        @click.stop="selected = ''; $wire.set('selectedStudio', ''); query = ''" 
                        class="cursor-pointer text-red-500 hover:text-red-700"
                        title="Clear selection">
                        &times;
                    </span>
                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            <div x-show="open" @click.away="open = false" class="dropdown-scroll absolute mt-2 w-full bg-slate-800 rounded-md shadow-lg z-50">
                <div class="p-2">
                    <input type="text" 
                        x-model="query" 
                        placeholder="Search studio..." 
                        class="w-full px-3 py-2 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-gray-600">
                </div>
                <ul class="max-h-72 overflow-y-auto">
                    @foreach ($studios as $studio)
                        <li 
                            x-show="!query || '{{ strtolower($studio) }}'.includes(query.toLowerCase())"
                            @click="selected = '{{ $studio }}'; $wire.set('selectedStudio', selected); open = false"
                            class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                        >
                            {{ $studio }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Search Button -->
        <div class="relative col-span-1 order-2 lg:order-6 min-w-full lg:min-w-[80px] lg:w-[80px] w-full flex-none">
            <button wire:click="submitSearch" class="bg-slate-600 w-full h-full rounded-md px-4 py-2.5 text-gray-200 hover:bg-slate-500 transition-colors">
                Хайх
            </button>
        </div>
    </div>

    <!-- Clear Filters Button -->
    @if($searchTerm || $selectedYear || $selectedTag || $selectedStatus || $selectedStudio)
    <div class="flex justify-center mb-6">
        <button wire:click="clearFilters" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors">
            Clear All Filters
        </button>
    </div>
    @endif

    <!-- Sort Options -->
    <div class="flex justify-between items-center mb-6">
        <div class="text-gray-300">
            Showing {{ $episodes->firstItem() ?? 0 }} - {{ $episodes->lastItem() ?? 0 }} of {{ $episodes->total() }} episodes
        </div>
        <div class="flex gap-2">
            <button wire:click="sortBy('published_at')" class="px-3 py-1 rounded {{ $sortBy === 'published_at' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-gray-300' }} hover:bg-slate-600 transition-colors">
                Latest
            </button>
            <button wire:click="sortBy('title')" class="px-3 py-1 rounded {{ $sortBy === 'title' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-gray-300' }} hover:bg-slate-600 transition-colors">
                Name
            </button>
            <button wire:click="sortBy('episode_number')" class="px-3 py-1 rounded {{ $sortBy === 'episode_number' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-gray-300' }} hover:bg-slate-600 transition-colors">
                Episode
            </button>
            <button wire:click="sortBy('view_count')" class="px-3 py-1 rounded {{ $sortBy === 'view_count' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-gray-300' }} hover:bg-slate-600 transition-colors">
                Popular
            </button>
            <button wire:click="sortBy('average_rating')" class="px-3 py-1 rounded {{ $sortBy === 'average_rating' ? 'bg-blue-600 text-white' : 'bg-slate-700 text-gray-300' }} hover:bg-slate-600 transition-colors">
                Rating
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading>
        <div class="grid grid-cols-2 xs:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-6 relative pb-8">
            @for ($i = 0; $i < 12; $i++)
            <div class="group bg-slate-900 overflow-hidden transition relative animate-pulse w-full">
                <div class="relative overflow-hidden rounded-lg">
                    <div class="bg-slate-700 w-[188px] h-56 rounded-lg"></div>
                </div>
                <div class="px-3 pt-3">
                    <div class="bg-slate-700 h-4 w-3/4 mb-2 rounded"></div>
                    <div class="bg-slate-700 h-4 w-1/2 rounded"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Results -->
    <div wire:loading.remove>
        @if ($episodes->isEmpty())
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <title>No episodes icon</title>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-300 mb-2">No episodes found</h3>
                <p class="text-gray-500">Try adjusting your search criteria or filters.</p>
            </div>
        @else
            <div class="grid grid-cols-2 xs:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-6">
                @foreach ($episodes as $episode)
                    <a href="{{ url('watch', $episode->slug) }}" 
                    class="group relative block rounded-lg bg-slate-800 hover:bg-slate-700 overflow-hidden transition">
                        
                        {{-- Episode number --}}
                        <div class="absolute top-2 left-2 bg-gray-800 p-2 rounded-xl z-10">
                            <p class="font-extrabold text-xl text-white duration-500 group-hover:pt-4">
                                {{ $episode->episode_number }}
                            </p>
                        </div>

                        {{-- Premium badge --}}
                        @if ($episode->is_premium)
                            <div class="absolute top-2 right-2 bg-yellow-600 p-1 rounded-full z-10" title="Premium episode">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 
                                            1 0 00.95.69h3.462c.969 0 1.371 1.24.588 
                                            1.81l-2.8 2.034a1 1 0 00-.364 
                                            1.118l1.07 3.292c.3.921-.755 
                                            1.688-1.54 1.118l-2.8-2.034a1 
                                            1 0 00-1.175 0l-2.8 
                                            2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 
                                            1 0 00-.364-1.118L2.98 
                                            8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 
                                            1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Thumbnail --}}
                        <div class="aspect-[2/3] overflow-hidden rounded-lg">
                            <img 
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                src="storage/poster/{{ $episode->poster_image ?? $episode->thumbnail_image ?? $episode->anime->cover_image ?? '/images/poster.jpg' }}"
                                alt="Episode {{ $episode->episode_number }} poster"
                                loading="lazy"
                            />
                        </div>

                        {{-- Title + duration --}}
                        <div class="p-3">
                            <p class="text-center text-gray-400 text-[15px] truncate transition duration-500 group-hover:text-gray-200">
                                {{ $episode->title ?? $episode->anime->title ?? 'Untitled' }}
                            </p>
                            @if ($episode->duration_formatted)
                                <p class="text-center text-gray-600 text-xs mt-1">
                                    {{ $episode->duration_formatted }}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
    
    <!-- Pagination -->
    <div class="py-8">
        {{ $episodes->links() }}
    </div>
    
</div>