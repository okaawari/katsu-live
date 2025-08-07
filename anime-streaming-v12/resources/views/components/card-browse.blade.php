<div class="grid grid-cols-2 xs:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-6">
        <div class="group bg-slate-900 overflow-hidden transition relative">
            <div class="relative overflow-hidden rounded-lg">
                <img class="object-cover w-full h-[] opacity-80 transition duration-500 group-hover:opacity-100 group-hover:scale-105" 
                    src="{{ $anime->poster }}" 
                    alt="{{ $anime->name }} poster"
                    loading="lazy"
                    aria-label="Anime poster: {{ $anime->name }}" />
                <div class="absolute bg-gray-800 bottom-0 w-full h-6 items-center">
                    <p class="text-gray-400 px-4 text-sm">{{ $anime->current_episode}}</p>
                </div>
            </div>
            <div class="px-3 pt-3">
                <p class="text-center text-gray-400 text-[15px] line-clamp-2 transition duration-500 group-hover:text-gray-200" aria-label="Anime name: {{ $anime->name }}">
                    {{ $anime->name }}
                </p>
            </div>
        </div>
</div>