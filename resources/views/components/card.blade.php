<div class="flex">
    <div class="swiper mySwiper2">
        <div class="swiper-wrapper my-6">
            @foreach($animes as $index => $anime)
            <div class="swiper-slide">
                <div class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 transition relative">
                    <div class="absolute top-2 left-2 bg-gray-800 p-2 rounded-xl z-50">
                        <p class="font-extrabold text-xl text-white duration-500 group-hover:pt-4">{{ $index + 1 }}</p>
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
            </div>
            @endforeach
        </div>
        <div class="swiper-scrollbar"></div>
    </div>
</div>