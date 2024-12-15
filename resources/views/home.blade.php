<x-app-layout>
    <!-- Swiper -->
    <div class="max-w-auto relative">
        <div class="mx-4">
            <div class="max-w-7xl swiper mySwiper1 m-8">
                <div class="swiper-wrapper">
                    @foreach($random as $anime)
                    <div class="swiper-slide cursor-pointer">
                        <div class="group bg-slate-800 flex rounded-lg overflow-hidden hover:bg-slate-700 h-[212px]" data-href="{{ url('watch/'.$anime->id) }}">
                            <span class="w-1 bg-blue-300 rounded-lg"></span>
                            <div class="basis-2/3 p-4 flex flex-col h-full justify-between">
                                <div class="flex">
                                    <p class="text-gray-300">
                                        @if($anime->status == 1)
                                            Гарч дууссан
                                        @else
                                            Гарч байгаа
                                        @endif
                                    </p>
                                </div>
                                <div class="flex">
                                    <a wire:navigate href="{{ url('watch/'.$anime->id) }}" class="transition duration-700 text-white font-semi-bold line-clamp-1 text-xl hover:text-blue-500">{{ $anime->name }}</a>
                                </div>
                                <div class="flex">
                                    <p class="text-gray-400 line-clamp-2 pt-4">{{ $anime->synopsis }}</p>
                                </div>
                                <div class="flex">
                                    <p class="text-gray-500 pt-4">Нийт анги - {{ $anime->episode_list }}</p>
                                </div>
                                <div class="flex gap-x-3">
                                    @if($anime->tags->count() > 0)
                                        @foreach($anime->tags->shuffle()->take(2) as $tag)
                                            <a href="#" class="transition duration-700 text-blue-300 hover:text-blue-500">{{ $tag->name_mn }}</a>
                                        @endforeach
                                    @else
                                        <a href="#" class="transition duration-700 text-blue-300 hover:text-blue-500">{{ $anime->type }}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="basis-1/3 flex items-center justify-center transition duration-500 rotate-12 opacity-70 scale-125 group-hover:rotate-0 group-hover:opacity-100 group-hover:scale-100">
                                <img
                                    class="object-cover h-full w-auto"
                                    src="{{ $anime->poster }}" alt="{{ $anime->name }} poster" />
                            </div>
                        </div>
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
                    <p @click="activeTab = 0" :class="{ 'text-white': activeTab === 0, 'text-gray-400': activeTab !== 0 }" class="cursor-pointer hover:text-white">
                        Өдөр
                    </p>
                    <p @click="activeTab = 1" :class="{ 'text-white': activeTab === 1, 'text-gray-400': activeTab !== 1 }" class="cursor-pointer hover:text-white">
                        7 хоног
                    </p>
                    <p @click="activeTab = 2" :class="{ 'text-white': activeTab === 2, 'text-gray-400': activeTab !== 2 }" class="cursor-pointer hover:text-white">
                        Сар
                    </p>
                </div>
            </div>

            <!-- Day -->
            <div class="tab-panel" x-show="activeTab === 0" x-show.transition.in.opacity.duration.600="activeTab === 0">
                <x-card :animes="$animes" />
            </div>

            <!-- Week -->
            <div class="tab-panel" x-show="activeTab === 1" x-show.transition.in.opacity.duration.600="activeTab === 1">
                <x-card :animes="$animes1" />
            </div>

            <!-- Month -->
            <div class="tab-panel" x-show="activeTab === 2" x-show.transition.in.opacity.duration.600="activeTab === 2">
                <x-card :animes="$animes2" />
            </div>
        </div>

        <!-- Continue Reading -->

        <div class="continue-reading-section">
            <div class="flex justify-between items-center mb-6">
                <h1 class="font-bold text-gray-300 text-2xl">Үргэлжлүүлж үзэх</h1>
                <a href="#" class="flex items-center text-gray-400 hover:text-white transition">
                    More
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-1 transition-transform transform hover:translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($watching as $watch)
                <div class="relative bg-slate-800 rounded-lg border border-slate-800 hover:border-slate-700 hover:shadow-inner transition duration-300 cursor-pointer group">
                    <div class="flex items-center p-4 overflow-hidden">
                        <div class="flex-shrink-0 w-1/4">
                            <img class="object-cover w-full h-full rounded-lg" src="{{ $watch->anime->poster }}" alt="{{ $watch->anime->name }} Poster" />
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-blue-400 text-sm">{{ $watch->anime->category->name }}</p>
                            <p class="line-clamp-1 text-white mt-2">{{ $watch->anime->name }}</p>
                            <p class="text-sm text-gray-400 mt-1">Chapter 1 EN - Page 1</p>

                            <!-- Show current time and total duration in minutes -->
                            @php
                                $currentTimeFormatted = gmdate("i:s", $watch->current_time); // Convert seconds to mm:ss
                                $durationFormatted = sprintf("%02d:00", $watch->anime->duration); // Format duration as mm:00
                            @endphp
                            <p class="text-sm text-gray-300 mt-2">
                            {{ $currentTimeFormatted }} / {{ $durationFormatted }}
                            </p>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0">
                            <div class="w-full bg-gray-700 h-1 rounded-b-lg">
                                <div class="h-1 bg-gray-300 rounded-bl-lg"
                                    style="width: {{ round($watch->current_time / ($watch->anime->duration * 60) * 100, 2) }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="absolute top-2 right-2 p-1 bg-slate-900 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                        <a href="#" class="text-gray-500 hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach



            </div>
        </div>


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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($animes2 as $anime)
                <div class="flex bg-slate-800 border border-slate-800 rounded-xl hover:bg-slate-800/75 hover:border-slate-700 transition duration-300">
                    <div class="basis-1/3 relative group max-h-[200px]">
                        <img 
                            class="object-cover w-full h-auto rounded-l-xl"
                            src="{{ $anime->poster }}"
                            alt="{{ $anime->name }} poster" 
                            loading="lazy"/>
                        
                            <div class="absolute top-16 left-24 opacity-0 group-hover:opacity-100 transition-opacity delay-500 duration-300 rounded-lg z-50 bg-slate-700 p-4 text-gray-300">
                                <div>
                                    <a href="#" class="text-sm text-blue-400 hover:text-blue-100">Releasing</a>
                                </div>
                                <a href="#" class="my-2 block">
                                    <p class="hover:text-white">{{ $anime->name }}</p>
                                </a>
                                <p class="text-gray-400 text-sm my-2 select-none">{{ $anime->synopsis }}</p>
                                <div class="flex gap-1 text-sm">
                                    <a href="#" class="rounded-xl bg-slate-800 py-1 px-2 hover:bg-slate-900 hover:text-white">Comedy</a>
                                    <a href="#" class="rounded-xl bg-slate-800 py-1 px-2 hover:bg-slate-900 hover:text-white">Drama</a>
                                    <a href="#" class="rounded-xl bg-slate-800 py-1 px-2 hover:bg-slate-900 hover:text-white">Romance</a>
                                </div>
                            </div>

                    </div>

                    <div class="basis-2/3 p-4">
                        <a href="#">
                            <p class="text-blue-400 text-sm hover:text-blue-300 transition duration-500">Manga</p>
                        </a>
                        <a href="#" class="my-4 block">
                            <p class="line-clamp-1 text-white">{{ $anime->name }}</p>
                        </a>
                        <div class="text-gray-500">
                        <a href="#" class="flex justify-between bg-slate-900/50 rounded-lg my-2 px-3 hover:bg-slate-900 hover:text-white">
                            <p class="inline-flex text-sm duration-500">Chap 003 MN</p>
                            <p class="inline-flex text-sm duration-500">1 hour ago</p>
                        </a>
                        <a href="#" class="flex justify-between bg-slate-900/50 rounded-lg my-2 px-3 hover:bg-slate-900 hover:text-white">
                            <p class="inline-flex text-sm duration-500 ">Chap 002 MN</p>
                            <p class="inline-flex text-sm duration-500 ">23 hour ago</p>
                        </a>
                        <a href="#" class="flex justify-between bg-slate-900/50 rounded-lg my-2 px-3 hover:bg-slate-900 hover:text-white">
                            <p class="inline-flex text-sm duration-500">Chap 001 MN</p>
                            <p class="inline-flex text-sm duration-500">2023-11-23</p>
                        </a>
                    </div>
                    </div>
                </div>
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
                    @foreach($animes2 as $anime)
                    <div class="swiper-slide">
                        <div href="#" class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700">
                            <div class="overflow-hidden">
                                <img
                                    class="transition rounded-lg opacity-80 ease-in-out duration-500 group-hover:opacity-100 group-hover:scale-105"
                                    src="{{ $anime->poster }}"
                                    alt="{{ $anime->name }}"
                                />
                            </div>
                            
                            <div>
                                <p class="text-center text-gray-300 duration-500 truncate p-3 group-hover:text-white">{{ $anime->name }}</p>
                            </div>
                        </div>
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
        
    </style>
@endsection

@section('header-scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endsection

@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const clickableDivs = document.querySelectorAll('[data-href]');

            clickableDivs.forEach(div => {
                div.addEventListener('click', function() {
                    // Redirect to the link stored in the data-href attribute
                    window.location.href = div.getAttribute('data-href');
                });
            });
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
        });

        var swiper2 = new Swiper(".mySwiper2", {
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
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
        });
    </script>
@endsection
</x-app-layout>
    
