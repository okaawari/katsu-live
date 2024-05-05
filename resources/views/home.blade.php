<x-app-layout>
    <!-- Swiper -->
    <div class="swiper mySwiper1">
        <div class="swiper-wrapper my-8">
            @foreach($animes as $anime)
            <div class="swiper-slide">
                <div class="group bg-slate-800 flex rounded-lg overflow-hidden hover:bg-slate-700 h-60">
                    <span class="w-1 bg-blue-300 rounded-lg"></span>
                    <div href="#" class="basis-2/3 p-4 flex flex-col h-full justify-between">
                        <div class="flex">
                            <p class="text-gray-300 font-thin">Releasing</p>
                        </div>
                        <div class="flex"> 
                            <a href="#" class="transition duration-700 text-white font-semi-bold line-clamp-2 text-xl hover:text-blue-500">{{ $anime->name }}</a>
                        </div>
                        <div class="flex">
                            <p class="text-gray-400 line-clamp-2 pt-4">{{ $anime->synopsis }}</p>
                        </div>
                        <div class="flex">
                            <p class="text-gray-500 pt-4">Chapter {{ $anime->current_episode }} - Vol {{ $anime->episode_list }}</p>
                        </div>
                        <div class="flex gap-x-3">
                            <a href="#" class="transition duration-700 text-blue-300 hover:text-blue-500">Action</a>
                            <a href="#" class="transition duration-700 text-blue-300 hover:text-blue-500">Adventure</a>
                            <a href="#" class="transition duration-700 text-blue-300 hover:text-blue-500">Horror</a>
                        </div>
                    </div>
                    <!-- <div class="basis-1/12">

                    </div> -->
                    <div class="basis-1/3 transition duration-500 rotate-12 opacity-70 scale-125 group-hover:rotate-0 group-hover:opacity-100 group-hover:scale-100">
                        <img
                            class="object-cover"
                            src="{{ $anime->poster }}"
                        />
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- <div class="swiper-button-prev bg-blue-500 w-4 !left-0 !text-white"></div>
        <div class="swiper-button-next bg-blue-500 !right-0 !text-white"></div> -->
    </div>

        

    <div class="max-w-7xl m-auto mb-8 overflow-hidden">
        <!-- Tab -->
        <div class="tab-wrapper" x-data="{ activeTab:  0 }">
            <div class="flex justify-between">
                <div class="flex">
                    <h1 class="font-bold text-gray-300 text-2xl">Most Viewed</h1>
                </div>
                <div class="flex gap-x-3 place-items-center">
                    <p @click="activeTab = 0" class="tab-control text-gray-400 cursor-pointer hover:text-white" class="{ 'active': activeTab === 0 }">Day</p>
                    <p @click="activeTab = 1" class="tab-control text-gray-400 cursor-pointer hover:text-white" class="{ 'active': activeTab === 1 }">Week</p>
                    <p @click="activeTab = 2" class="tab-control text-gray-400 cursor-pointer hover:text-white" class="{ 'active': activeTab === 2 }">Month</p>
                </div>
            </div>
            <!-- Day -->
            <div class="tab-panel" :class="{ 'active': activeTab === 0 }" x-show.transition.in.opacity.duration.600="activeTab === 0">
                <div class="flex">
                    <div class="swiper mySwiper2">
                        <div class="swiper-wrapper my-6">
                            @foreach($animes as $index => $anime)
                            <div class="swiper-slide">
                                <div href="#" class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700">
                                    <div class="absolute m-2 bg-gray-800 z-50 p-2 rounded-xl">
                                        <p class="font-extrabold duration-500 text-xl group-hover:pt-4 text-white">{{ $index+1 }}</p>
                                    </div>
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
                        <div class="swiper-scrollbar"></div>
                    </div>
                </div>
            </div>
            <!-- Week -->
            <div class="tab-panel" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.600="activeTab === 1">
                <div class="flex">
                    <div class="swiper mySwiper2">
                        <div class="swiper-wrapper my-6">
                            @foreach($animes1 as $index => $anime)
                            <div class="swiper-slide">
                                <div href="#" class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700">
                                    <div class="absolute ml-36 mt-2 bg-gray-800 z-50 p-2 rounded-xl">
                                        <p class="font-extrabold duration-200 text-xl group-hover:pt-4 text-white">{{ $index+1 }}</p>
                                    </div>
                                    <div class="overflow-hidden">
                                        <img
                                            class="transition rounded-lg opacity-90 ease-in-out duration-300 group-hover:opacity-100 group-hover:scale-105"
                                            src="{{ $anime->poster }}"
                                            alt="{{ $anime->name }}"
                                        />
                                    </div>
                                    
                                    <div>
                                        <p class="text-center text-gray-300 duration-300 truncate p-3 group-hover:text-white">{{ $anime->name }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination2"></div>
                        <div class="swiper-scrollbar2"></div>
                    </div>
                </div>
            </div>
            <!-- Month -->
            <div class="tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.600="activeTab === 2">
                <div class="flex">
                    <div class="swiper mySwiper2">
                        <div class="swiper-wrapper my-6">
                            @foreach($animes2 as $index => $anime)
                            <div class="swiper-slide">
                                <div href="#" class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700">
                                    <div class="absolute ml-36 mt-2 bg-gray-800 z-50 p-2 rounded-xl">
                                        <p class="font-extrabold duration-200 text-xl group-hover:pt-4 text-white">{{ $index+1 }}</p>
                                    </div>
                                    <div class="overflow-hidden">
                                        <img
                                            class="transition rounded-lg opacity-90 ease-in-out duration-300 group-hover:opacity-100 group-hover:scale-105"
                                            src="{{ $anime->poster }}"
                                            alt="{{ $anime->name }}"
                                        />
                                    </div>
                                    <div>
                                        <p class="text-center text-gray-300 duration-300 truncate p-3 group-hover:text-white">{{ $anime->name }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination2"></div>
                        <div class="swiper-scrollbar2"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continue Reading -->

        <div class="">
            <div class="flex justify-between">
                <div class="flex">
                    <h1 class="font-bold text-gray-300 text-2xl">Continue Reading</h1>
                </div>
                <div class="flex gap-x-3 place-items-center">
                    <a href="" class="flex text-gray-400 cursor-pointer hover:text-white">More 
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="flex flex-row gap-x-4 my-6">
                @foreach($animes->take(4) as $anime)
                <div class="flex basis-1/4 bg-slate-800 rounded-lg border border-slate-800 duration-300 hover:bg-slate-800/75 hover:border-slate-700 hover:shadow-inner cursor-pointer">
                    <div class="basis-1/6 m-4 max-w-full w-fit">
                        <img
                            class="object-cover rounded-lg"
                            src="{{ $anime->poster }}"
                            alt="{{ $anime->name }}"
                        />
                    </div>
                    <div class="m-4">
                        <div>
                            <p class="text-blue-400 text-sm">Manga</p>
                        </div>
                        <div class="">
                            <p class="line-clamp-1 text-white">{{ $anime->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Chapter 1 EN - Page 1</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recently Updated -->

        <div class="my-8">
            <div class="flex justify-between">
                <div class="flex">
                    <h1 class="font-bold text-gray-300 text-2xl">Recently Updated</h1>
                </div>
                <div class="flex gap-x-3 place-items-center">
                    <p class="text-gray-400 cursor-pointer hover:text-white">Updated</p>
                    <p class="text-gray-400 cursor-pointer hover:text-white">Trending</p>
                    <p class="text-gray-400 cursor-pointer hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    </p>
                    <p class="text-gray-400 cursor-pointer hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    </p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($animes as $anime)
            <div class="flex bg-slate-800 border border-slate-800 duration-500 rounded-xl hover:bg-slate-800/75 hover:border-slate-700">
                <div href="#" class="basis-1/3 relative group">
                    <div>
                        <img 
                            class="object-cover"
                            src="{{ $anime->poster }}"
                            alt="{{ $anime->name }}"/>
                    </div>
                    <div class="absolute top-6 left-2 hidden transition-all delay-500 group-hover:block rounded-lg z-50 bg-slate-700 ml-32 text-gray-300 p-4 gap-x-2 ">
                        <div>
                            <a href="#" class="text-sm text-blue-400 hover:text-blue-100">Releasing</a>
                        </div>
                        <a href="#" class="my-2">
                            <p class="text-gray-300 hover:text-white">{{ $anime->name }}</p>
                        </a>
                        <div class="my-2">
                            <p class="text-gray-400 text-sm">{{ $anime->synopsis }}</p>
                        </div>
                        <div class="flex gap-1 text-sm">
                            <a href="#" class="rounded-xl bg-slate-800 py-1 px-2 hover:bg-slate-900 hover:text-white">Comedy</a>
                            <a href="#" class="rounded-xl bg-slate-800 py-1 px-2 hover:bg-slate-900 hover:text-white">Drama</a>
                            <a href="#" class="rounded-xl bg-slate-800 py-1 px-2 hover:bg-slate-900 hover:text-white">Romance</a>
                        </div>
                    </div>
                </div>

                <div class="basis-2/3 p-4">
                    <a href="#">
                        <p class="text-blue-400 text-sm duration-500 hover:text-blue-300">Manga</p>
                    </a>
                    <a href="#" class="my-4">
                        <p class="line-clamp-1 my-3 text-white">{{ $anime->name }}</p>
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
        <div class="flex mb-40">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        
    </style>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        var swiper1 = new Swiper(".mySwiper1", {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            slidesPerView: 3,
            spaceBetween: 30,
            centeredSlides: false,
            pagination: {
                el: ".swiper-pagination1",
                clickable: true,
            },
        });

        var swiper2 = new Swiper(".mySwiper2", {
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
            slidesPerView: 7,
            spaceBetween: 10,
            pagination: {
                el: ".swiper-pagination2",
                clickable: true,
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
    
