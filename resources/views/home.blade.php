<x-app-layout>
    <!-- Swiper -->
    <div class="max-w-auto relative">
        <div class="mx-4">
            <div class="max-w-7xl swiper mySwiper1 m-8">
                <div class="swiper-wrapper">
                    @foreach($random as $episode)
                        <div class="swiper-slide">
                            <a href="{{ route('watch', $episode->slug) }}" class="group bg-slate-800 flex rounded-lg overflow-hidden hover:bg-slate-700 h-[212px]">
                                <span class="w-1 bg-blue-300 rounded-lg"></span>
                                <div class="basis-2/3 p-4 flex flex-col h-full justify-between">
                                    <div class="flex">
                                        <p class="text-gray-300">
                                            Episode {{ $episode->episode_number }}
                                        </p>
                                    </div>
                                    <div class="flex">
                                        <p class="transition duration-700 text-white font-semi-bold line-clamp-1 text-xl hover:text-blue-500">
                                            {{ $episode->anime->title ?? $episode->anime->name ?? 'Untitled' }}
                                        </p>
                                    </div>
                                    <div class="flex">
                                        <p class="text-gray-400 line-clamp-2 pt-4">{{ $episode->synopsis ?? $episode->anime->description ?? 'No description available' }}</p>
                                    </div>
                                    <div class="flex">
                                        <p class="text-gray-500 pt-4">{{ $episode->formatted_duration ?? 'Unknown duration' }}</p>
                                    </div>
                                    <div class="flex gap-x-3">
                                        @if($episode->tags && $episode->tags->count() > 0)
                                            @foreach($episode->tags->shuffle()->take(2) as $tag)
                                                <span class="transition duration-700 text-blue-300 hover:text-blue-500">{{ $tag->name_mn ?? 'Tag' }}</span>
                                            @endforeach
                                        @else
                                            <span class="transition duration-700 text-blue-300 hover:text-blue-500">{{ $episode->anime->type ?? 'Anime' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="basis-1/3 flex items-center justify-center transition duration-500 rotate-12 opacity-70 scale-125 group-hover:rotate-0 group-hover:opacity-100 group-hover:scale-100">
                                    <img class="object-cover h-full w-auto"
                                        src="storage/poster/{{ $episode->poster_image ?? $episode->anime->cover_image ?? $episode->anime->poster ?? '/images/poster.jpg' }}"
                                        alt="{{ $episode->anime->title ?? $episode->anime->name ?? 'Anime' }} Episode {{ $episode->episode_number }} poster" />
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        

        <!-- Positioned Navigation Buttons Outside the Swiper -->
        <div class="custom-prev">&#10094;</div>
        <div class="custom-next">&#10095;</div>
    </div>

    <div class="max-w-7xl m-auto px-4">
        <!-- Tab -->
        <div class="tab-wrapper my-6" x-data="{ activeTab: 0 }">
            <div class="flex justify-between">
                <div class="flex">
                    <h1 class="font-bold text-gray-300 text-2xl">Most Viewed</h1>
                </div>
                <div class="flex gap-x-3 place-items-center">
                    <!-- Tab Controls -->
                    <p 
                        @click="activeTab = 0" 
                        :class="{ 'text-blue-400': activeTab === 0, 'text-gray-400': activeTab !== 0 }" 
                        class="cursor-pointer hover:text-white transition-all duration-300"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        Өдөр
                    </p>
                    <p 
                        @click="activeTab = 1" 
                        :class="{ 'text-blue-400': activeTab === 1, 'text-gray-400': activeTab !== 1 }" 
                        class="cursor-pointer hover:text-white transition-all duration-300"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        7 хоног
                    </p>
                    <p 
                        @click="activeTab = 2" 
                        :class="{ 'text-blue-400': activeTab === 2, 'text-gray-400': activeTab !== 2 }" 
                        class="cursor-pointer hover:text-white transition-all duration-300"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        Сар
                    </p>
                </div>
            </div>


            <!-- Day -->
            <div class="tab-panel" x-show="activeTab === 0" x-show.transition.in.opacity.duration.600="activeTab === 0">
                <x-episode-card :episodes="$episodes" />
            </div>

            <!-- Week -->
            <div class="tab-panel" x-show="activeTab === 1" x-show.transition.in.opacity.duration.600="activeTab === 1">
                <x-episode-card :episodes="$episodes1" />
            </div>

            <!-- Month -->
            <div class="tab-panel" x-show="activeTab === 2" x-show.transition.in.opacity.duration.600="activeTab === 2">
                <x-episode-card :episodes="$episodes2" />
            </div>
        </div>

        <!-- Continue Reading -->

        <div class="continue-reading-section">
            <div class="flex justify-between items-center mb-6">
                <h1 class="font-bold text-gray-300 text-2xl">Continue Watching</h1>
                <a href="#" class="flex items-center text-gray-400 hover:text-white transition">
                    More
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-1 transition-transform transform hover:translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                    </svg>
                </a>
            </div>

            <div id="watching-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @forelse($watching as $watch)
                <article 
                        class="relative bg-slate-800 rounded-lg border border-slate-800 hover:border-slate-700 transition-all duration-300 group shadow-lg hover:shadow-xl watch-card"
                        itemscope
                        itemtype="https://schema.org/MediaObject"
                    >
                        <a href="{{ url('watch', $watch->animes_id) }}"
                            class="block p-4 hover:no-underline focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-slate-800 rounded-lg"
                            aria-label="Continue watching {{ $watch->anime->title ?? $watch->anime->name ?? 'Anime' }}">
                            <div class="flex gap-4">
                                <!-- Image Section -->
                                <div class="flex-shrink-0 w-1/4 relative">
                                    <img 
                                        class="object-cover w-full aspect-[2/3] rounded-lg"
                                        src="storage/poster/{{ $watch->anime->cover_image ?? $watch->anime->poster ?? '/images/poster.jpg' }}"
                                        alt="{{ $watch->anime->title ?? $watch->anime->name ?? 'Anime' }} poster"
                                        width="160"
                                        height="240"
                                        loading="lazy"
                                        itemprop="image"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent rounded-lg"></div>
                                </div>

                                <!-- Content Section -->
                                <div class="flex-1 min-w-0">
                                    <span class="inline-block text-blue-400 text-sm font-medium mb-1">
                                        {{ $watch->anime->category->name ?? 'Uncategorized' }}
                                    </span>
                                    <h3 
                                        class="text-white font-medium truncate"
                                        itemprop="name"
                                    >
                                        {{ $watch->anime->title ?? $watch->anime->name ?? 'Unknown Anime' }}
                                    </h3>
                                    
                                    <div class="mt-2 space-y-1">
                                        <p class="text-sm text-gray-400 truncate">
                                            Анги {{ $watch->anime->current_episode ?? '1' }}
                                        </p>
                                        
                                        <!-- Progress -->
                                        <div class="mt-2">
                                            <div 
                                                class="text-sm text-gray-300 flex justify-between mb-1"
                                                aria-hidden="true"
                                            >
                                                <time datetime="{{ gmdate('H:i:s', $watch->current_time) }}">
                                                    {{ gmdate("i:s", $watch->current_time) }}
                                                </time>
                                                <time datetime="{{ sprintf('%02d:00:00', $watch->anime->duration ?? 24) }}">
                                                    {{ sprintf("%02d:00", $watch->anime->duration ?? 24) }}
                                                </time>
                                            </div>
                                            <div 
                                                class="w-full bg-gray-700 h-1.5 rounded-full"
                                                role="progressbar"
                                                aria-valuenow="{{ round($watch->progressPercentage()) }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            >
                                                <div 
                                                    class="h-1.5 bg-gray-300 rounded-full transition-all duration-500"
                                                    style="width: {{ $watch->progressPercentage() }}%"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="absolute top-2 right-2 p-1 bg-slate-900 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                            <form action="{{ route('watching.destroy', $watch->animes_id) }}" method="POST" class="inline watch-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-white transition duration-300 p-0 bg-transparent border-none rounded-full w-6 h-6 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2.5"
                                        stroke="currentColor"
                                        class="w-5 h-5" >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-300 mb-2">No watching history</h3>
                        <p class="text-gray-500">Start watching some anime to see your progress here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed bottom-5 left-5 flex flex-col space-y-2 z-50"></div>

        <!-- Recently Updated -->

        <div class="my-8">
            <div class="flex justify-between mb-4">
                <h1 class="font-bold text-gray-300 text-2xl">Recently Updated</h1>
                <div class="flex gap-x-3 place-items-center">
                    <p class="text-gray-400 cursor-pointer hover:text-white">Updated</p>
                    <p class="text-gray-400 cursor-pointer hover:text-white">Trending</p>
                    <p class="text-gray-400 cursor-pointer hover:text-white" aria-label="Next Page">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </p>
                    <p class="text-gray-400 cursor-pointer hover:text-white" aria-label="Previous Page">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </p>
                </div>
            </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
                    @foreach($episodes2 as $episode)
                    <article 
                        class="group relative flex bg-slate-800 rounded-xl hover:bg-slate-800/75 transition-colors duration-300 shadow-lg hover:shadow-xl overflow-hidden"
                        itemscope itemtype="https://schema.org/CreativeWork"
                    >
                        <div class="basis-1/3 min-w-[120px] relative">
                            <img 
                                class="w-full h-full object-cover aspect-[2/3] rounded-l-xl"
                                src="storage/poster/{{ $episode->poster_image ?? $episode->anime->cover_image ?? $episode->anime->poster ?? '/images/poster.jpg' }}"
                                alt="{{ $episode->anime->title ?? $episode->anime->name }} Episode {{ $episode->episode_number }} cover art"
                                width="200"
                                height="300"
                                loading="lazy"
                                itemprop="image"
                            >
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-800/50 to-transparent"></div>
                        </div>

                        <div class="basis-2/3 p-3 sm:p-4 flex flex-col">
                            <a 
                                href="{{ route('watch', $episode->slug) }}" 
                                class="inline-block mb-2 transition-opacity hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                itemprop="url"
                            >
                                <h3 
                                    class="font-semibold text-white line-clamp-2 mb-1" 
                                    itemprop="name"
                                >
                                    {{ $episode->anime->title ?? $episode->anime->name }}
                                </h3>
                            </a>
                            
                            <div class="text-xs text-slate-400 mb-2 flex items-center gap-2">
                                <span class="px-2 py-1 bg-slate-700/50 rounded-full">Episode {{ $episode->episode_number }}</span>
                            </div>

                            <div class="mt-auto space-y-1">
                                <a 
                                    href="{{ route('watch', $episode->slug) }}"
                                    class="flex justify-between items-center p-2 sm:px-3 bg-slate-900/30 hover:bg-slate-900/60 rounded-lg transition-colors duration-200 group/chapter"
                                    itemprop="hasPart"
                                    itemscope
                                    itemtype="https://schema.org/CreativeWork"
                                >
                                    <span class="text-sm text-slate-300 group-hover/chapter:text-white truncate" itemprop="name">
                                        {{ $episode->formatted_duration ?? 'Unknown duration' }}
                                    </span>
                                    <time 
                                        class="text-xs text-slate-500 group-hover/chapter:text-slate-300 ml-2 shrink-0" 
                                        itemprop="datePublished"
                                    >
                                        {{ $episode->published_at ? $episode->published_at->diffForHumans() : 'Recently' }}
                                    </time>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
        </div>


        <!-- New Releases -->

        <div class="my-8">
            <div class="flex justify-between">
                <div class="flex">
                    <h1 class="font-bold text-gray-300 text-2xl">New Release</h1>
                </div>
                <div class="flex gap-x-3 place-items-center text-gray-400">
                    <p class="cursor-pointer hover:text-white">Day</p>
                    <p class="cursor-pointer hover:text-white">Week</p>
                </div>
            </div>
        </div>
        <div class="flex pb-8">
            <div class="swiper mySwiper3">
                <div class="swiper-wrapper">
                    @foreach($episodes2 as $episode)
                    <div class="swiper-slide">
                        <a href="{{ route('watch', $episode->slug) }}" class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 block">
                            <div class="overflow-hidden">
                                <img
                                    class="transition rounded-lg opacity-80 ease-in-out duration-500 group-hover:opacity-100 group-hover:scale-105"
                                    src="storage/poster/{{ $episode->poster_image ?? $episode->anime->cover_image ?? $episode->anime->poster ?? '/images/poster.jpg' }}"
                                    alt="{{ $episode->anime->title ?? $episode->anime->name }} Episode {{ $episode->episode_number }}"
                                />
                            </div>
                            
                            <div>
                                <p class="text-center text-gray-300 duration-500 truncate p-3 group-hover:text-white">{{ $episode->anime->title ?? $episode->anime->name }} - Episode {{ $episode->episode_number }}</p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
        
        .animate-fadeOut {
            animation: fadeOut 0.3s ease-in;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>
@endsection

@section('header-scripts')
    
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper1 = new Swiper(".mySwiper1", {
        loop: true,
        autoplay: {
            delay: 5000,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.custom-next',
            prevEl: '.custom-prev',
        },
        slidesPerView: 3,
        spaceBetween: 10,
        centeredSlides: false,
        pagination: {
            el: ".swiper-pagination1",
            clickable: true,
        },
        
        breakpoints: {
            0: {
                slidesPerView: 1, 
            },
            640: {
                slidesPerView: 1, 
            },
            920: {
                slidesPerView: 2, 
            },
            1280: {
                slidesPerView: 3, 
            },
        },
    })

    var swiper2 = new Swiper(".mySwiper2", {
        spaceBetween: 10,
        pagination: {
            el: ".swiper-pagination2",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 3, 
            },
            460: {
                slidesPerView: 4, 
            },
            640: {
                slidesPerView: 5, 
            },
            920: {
                slidesPerView: 6, 
            },
            1280: {
                slidesPerView: 7, 
            },
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            draggable: true,
        },
    });

    var swiper3 = new Swiper(".mySwiper3", {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        slidesPerView: 6,
        spaceBetween: 10,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            640: {
                slidesPerView: 3,
            },
            920: {
                slidesPerView: 4,
            },
            1280: {
                slidesPerView: 6,
            },
        },
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('.watch-delete-form');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const toastContainer = document.getElementById('toast-container');

    deleteForms.forEach((form) => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Stop page refresh

            try {
                // Collect the form data (_method & _token)
                const formData = new FormData(form);

                // Send the AJAX DELETE request
                const response = await fetch(form.action, {
                    method: 'POST', // We use POST + _method=DELETE
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error(`Error: ${response.status} ${response.statusText}`);
                }

                const data = await response.json();

                // Remove the deleted item from DOM
                const card = form.closest('.watch-card');
                if (card) {
                    card.remove();
                }

                // Show a toast notification
                showToast('Anime deleted from history!', toastContainer);

                // ✅ Fetch and update the next item
                await refreshWatchingList();

            } catch (error) {
                console.error('Error deleting progress:', error);
                showToast('An error occurred while trying to delete.', toastContainer, true);
            }
        });
    });
});

// Fetches and updates the watching list
async function refreshWatchingList() {
    try {
        const response = await fetch('/watching'); // Fetch the updated top 4
        if (!response.ok) {
            throw new Error(`Fetch error: ${response.status}`);
        }

        const data = await response.json();
        const container = document.querySelector('#watching-container');
        if (!container) return; // Safety check

        container.innerHTML = ''; // Clear existing content

        // Rebuild the UI with updated data
        data.forEach(item => {
            const cardHTML = buildWatchingCardHTML(item); // Helper function to generate HTML
            container.insertAdjacentHTML('beforeend', cardHTML);
        });

    } catch (error) {
        console.error('Error fetching updated list:', error);
    }
}

// Helper function to build watching card HTML
function buildWatchingCardHTML(item) {
    const progressPercentage = item.duration > 0 ? Math.min(100, (item.current_time / item.duration) * 100) : 0;
    const currentTimeFormatted = formatTime(item.current_time);
    const durationFormatted = formatTime(item.duration || 24 * 60); // Default 24 minutes
    
    return `
        <article class="relative bg-slate-800 rounded-lg border border-slate-800 hover:border-slate-700 transition-all duration-300 group shadow-lg hover:shadow-xl watch-card">
            <a href="/watch/${item.animes_id}" class="block p-4 hover:no-underline focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-slate-800 rounded-lg">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-1/4 relative">
                        <img class="object-cover w-full aspect-[2/3] rounded-lg" 
                             src="${item.anime?.cover_image || item.anime?.poster || '/images/poster.jpg'}" 
                             alt="${item.anime?.title || item.anime?.name || 'Anime'} poster" 
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent rounded-lg"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="inline-block text-blue-400 text-sm font-medium mb-1">
                            ${item.anime?.category?.name || 'Uncategorized'}
                        </span>
                        <h3 class="text-white font-medium truncate">
                            ${item.anime?.title || item.anime?.name || 'Unknown Anime'}
                        </h3>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-400 truncate">
                                Анги ${item.anime?.current_episode || '1'}
                            </p>
                            <div class="mt-2">
                                <div class="text-sm text-gray-300 flex justify-between mb-1">
                                    <time>${currentTimeFormatted}</time>
                                    <time>${durationFormatted}</time>
                                </div>
                                <div class="w-full bg-gray-700 h-1.5 rounded-full">
                                    <div class="h-1.5 bg-gray-300 rounded-full transition-all duration-500" 
                                         style="width: ${progressPercentage}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <div class="absolute top-2 right-2 p-1 bg-slate-900 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                <form action="/watching/${item.animes_id}" method="POST" class="inline watch-delete-form">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-gray-500 hover:text-white transition duration-300 p-0 bg-transparent border-none rounded-full w-6 h-6 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            </div>
        </article>
    `;
}

// Helper function to format time
function formatTime(seconds) {
    if (!seconds) return '00:00';
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

/**
 * Utility function to show a toast.
 * @param {string} message - The text content of the toast
 * @param {HTMLElement} container - The DOM element in which to place the toast
 * @param {boolean} isError - Whether this is an error toast or not
 */
function showToast(message, container, isError = false) {
    const toast = document.createElement('div');
    toast.textContent = message;

    // Add some utility classes (Tailwind or custom)
    toast.classList.add(
        'py-2',
        'px-3',
        'rounded',
        'shadow-md',
        'text-white',
        'mb-2',
        'animate-slideIn'  // We'll define this animation below
    );

    // Different background for success vs error
    if (isError) {
        toast.classList.add('bg-red-400');
    } else {
        toast.classList.add('bg-green-400');
    }

    // Append the toast
    container.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        // Animate out (optional)
        toast.classList.add('animate-fadeOut');
        // Remove from DOM after animation
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

</script>

@endsection
</x-app-layout>
    
