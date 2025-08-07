<div class="">
    <div class="lg:flex grid grid-cols-4 justify-center my-8 gap-4 text-sm">
        <div class="col-span-3 min-w-full lg:min-w-[160px] w-full">
            <input 
                type="text" 
                placeholder="Хайх..." 
                wire:model="searchTerm" 
                wire:keydown.enter="submitSearch"
                class="w-full h-full bg-slate-800 text-gray-200 px-5 py-2.5 border-slate-800 rounded-md focus:border-transparent focus:ring-0" 
            />
        </div>

        <div x-data="{ open: false, query: '', selected: @entangle('selectedStudio') }" class="relative col-span-2 order-3 lg:order-2 min-w-full lg:min-w-[160px] w-full">
            <button 
                @click="open = !open" 
                class="w-full bg-slate-800 text-gray-300 px-5 py-2.5 rounded-md flex justify-between items-center"
            >
                <span x-text="selected ? selected : 'Студи'" class="line-clamp-1"></span>
                <div class="flex items-center gap-2">
                    <span 
                        x-show="selected" 
                        @click.stop="selected = ''; $wire.set('selectedStudio', ''); query = ''" 
                        class="cursor-pointer text-red-500 hover:text-red-700"
                        title="Clear selection"
                    >
                        &times;
                    </span>
                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            <div 
                x-show="open" 
                @click.away="open = false" 
                class="dropdown-scroll absolute mt-2 w-full bg-slate-800 rounded-md shadow-lg z-50"
            >
                <div class="p-2">
                    <input 
                        type="text" 
                        x-model="query" 
                        placeholder="Search studios..." 
                        class="w-full px-3 py-2 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-gray-600"
                    >
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

        <div x-data="{ open: false, query: '', selected: @entangle('selectedYear') }" class="relative col-span-2 order-4 lg:order-3 min-w-full lg:min-w-[160px] w-full">
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

        <div x-data="{ open: false, query: '', selected: @entangle('selectedTag') }" class="relative col-span-2 order-5 lg:order-4 min-w-full lg:min-w-[160px] w-full">
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

        <div x-data="{ open: false, query: '', selected: @entangle('selectedStatus'), getStatusText(status) {
                switch (status) {
                    case '1': return 'OVA';
                    case '2': return 'TV-Series';
                    case '3': return 'Movie';
                    case '4': return 'ONA';
                    default: return 'Ангилал';
                }
            }
        }" class="relative col-span-2 order-6 lg:order-5 min-w-full lg:min-w-[160px] w-full">
            <button @click="open = !open" class="w-full bg-slate-800 text-gray-300 px-3 py-2.5 rounded-md flex justify-between items-center">
                <span x-text="selected ? getStatusText(selected) : 'Ангилал'"></span>
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
                        placeholder="Search status..." 
                        class="w-full px-3 py-2 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:ring-gray-600">
                </div>
                <ul class="max-h-72 overflow-y-auto">
                    <li 
                        x-show="!query || 'ova'.includes(query.toLowerCase())"
                        @click="selected = '1'; $wire.set('selectedStatus', '1'); open = false" 
                        class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                    >
                        OVA
                    </li>
                    <li 
                        x-show="!query || 'tv-series'.includes(query.toLowerCase())"
                        @click="selected = '2'; $wire.set('selectedStatus', '2'); open = false" 
                        class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                    >
                        TV-Series
                    </li>
                    <li 
                        x-show="!query || 'movie'.includes(query.toLowerCase())"
                        @click="selected = '3'; $wire.set('selectedStatus', '3'); open = false" 
                        class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                    >
                        Movie
                    </li>
                    <li 
                        x-show="!query || 'ona'.includes(query.toLowerCase())"
                        @click="selected = '4'; $wire.set('selectedStatus', '4'); open = false" 
                        class="px-3 py-2 hover:bg-slate-600 cursor-pointer rounded-lg text-gray-300"
                    >
                        ONA
                    </li>
                </ul>
            </div>
        </div>

        <div class="relative col-span-1 order-2 lg:order-6 min-w-full lg:min-w-[80px] lg:w-[80px] w-full flex-none">
            <button wire:click="submitSearch"class="bg-slate-600 w-full h-full rounded-md px-4 py-2.5 text-gray-200">
                Хайх
            </button>
        </div>
    </div>

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
    <div wire:loading.remove>
        @if($animes->isEmpty())
            <div class="col-span-full text-center text-gray-400 text-lg">
                <p>No results found for your search. Please try again!</p>
            </div>
        @else
        <div class="grid grid-cols-2 xs:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-6 relative">
            @foreach ($animes as $anime)
            <a href="{{ url('watch', $anime->id) }}" class="group relative block overflow-hidden">
            <div class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 transition relative">
                    <div class="absolute top-2 left-2 bg-gray-800 p-2 rounded-xl z-10">
                        <p class="font-extrabold text-xl text-white duration-500 group-hover:pt-4">{{ $anime->current_episode }}</p>
                    </div>
                    <div class="overflow-hidden">
                        <img class="object-cover w-full h-full opacity-80 transition duration-500 group-hover:opacity-100 group-hover:scale-105" 
                            src="{{ $anime->poster }}" 
                            alt="{{ $anime->name }} poster"
                            loading="lazy"
                            aria-label="Anime poster: {{ $anime->name }}" />
                    </div>
                    <div class="p-3">
                        <p class="text-center text-gray-400 text-[15px] truncate transition duration-500 group-hover:text-gray-200" aria-label="Anime name: {{ $anime->name }}">
                            {{ $anime->name }}
                        </p>
                    </div>
                </div>
            </a>
            
            @endforeach
        </div>
        @endif
    </div>
    
        
    <div class="py-8">
        {{ $animes->links() }}
    </div>
    
</div>