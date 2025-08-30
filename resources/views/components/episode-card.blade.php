<div class="flex">
    <div class="swiper mySwiper2">
        <div class="swiper-wrapper my-6">
            @foreach($episodes as $index => $episode)
            <div class="swiper-slide">
                <a href="{{ url('watch', $episode->slug) }}" class="group relative block overflow-hidden">
                    <div class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 transition relative">
                        <div class="absolute top-2 left-2 bg-gray-800 p-2 rounded-xl z-50">
                            <p class="font-extrabold text-xl text-white duration-500 group-hover:pt-4">{{ $index + 1 }}</p>
                        </div>
                        <div class="overflow-hidden">
                            <img class="object-cover w-full h-full opacity-80 transition duration-500 group-hover:opacity-100 group-hover:scale-105" 
                                src="storage/poster/{{ $episode->poster_image ?? $episode->anime->cover_image ?? $episode->anime->poster ?? '/images/poster.jpg' }}" 
                                alt="{{ $episode->anime->title ?? $episode->anime->name ?? 'Anime' }} Episode {{ $episode->episode_number }} poster"
                                loading="lazy"
                                aria-label="Episode poster: {{ $episode->anime->title ?? $episode->anime->name ?? 'Anime' }} Episode {{ $episode->episode_number }}" />
                        </div>
                        <div class="p-3">
                            <p class="text-center text-gray-400 text-[15px] truncate transition duration-500 group-hover:text-gray-200" aria-label="Episode name: {{ $episode->anime->title ?? $episode->anime->name ?? 'Anime' }} Episode {{ $episode->episode_number }}">
                                {{ $episode->anime->title ?? $episode->anime->name ?? 'Untitled' }} - Episode {{ $episode->episode_number }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="swiper-scrollbar"></div>
    </div>
</div>
