<x-app-layout>
    <div class="flex flex-wrap max-w-[1400px] mx-auto px-4">
        <div class="relative w-full lg:basis-2/3 pt-4">
        <media-player 
            title="{{ $anime->name }}" 
            src="https://fukkatsu.club/storage/video/{{ $anime->stream_720 }}" 
            playsinline>

            <media-provider>
                <track
                    src="{{ url('videos/test.vtt') }}"
                    kind="subtitles"
                    label="Mongolia"
                    srclang="mn-MN"
                    default
                    data-type="vtt"
                />
                <media-poster
                    class="vds-poster"
                    src="https://files.vidstack.io/sprite-fight/poster.webp"
                    alt="Girl walks into campfire with gnomes surrounding her friend ready for their next meal!"
                ></media-poster>
            </media-provider>

            <media-video-layout thumbnails="https://files.vidstack.io/sprite-fight/thumbnails.vtt"></media-video-layout>
        </media-player>
            <div class="mt-1 mb-3"> 
                <h1 class="text-gray-300 text-xl font-semibold">{{ $anime->name }}</h1>
            </div>
            <div class="border-b border-slate-800"></div>
            <div class="flex w-full rounded-lg mt-3">
                <div class="">
                    <img
                        class="sm:w-[180px] sm:min-w-[180px] sm:h-[270px] min-w-[120px] h-[190px] object-cover rounded-lg opacity-90"
                        src="{{ $anime->poster }}"/>
                </div>
                <div class="grid justify-between text-gray-300 ml-4">
                    <div>
                        <p class="font-thin text-gray-400">Студи</p>
                        <a href="#" class="">{{ $anime->studio }}</a>
                    </div>
                    <div>
                        <p class="font-thin text-gray-400">Статус</p>
                        <p>
                            @if($anime->status == '1')
                                Гарч байгаа
                            @else
                                Гарч дууссан
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="font-thin text-gray-400">Гарсан өдөр</p>
                        <p>{{ $anime->aired_at }}</p>
                    </div>
                    <div>
                        <p class="font-thin text-gray-400">Нэр</p>
                        <div class="block sm:flex overflow-y-hidden">
                            <h2 class="">{{ $anime->name_second }}</h2>
                            <h3 class="pl-0 sm:pl-5">{{ $anime->name_japanese }}</h3>
                        </div>
                        
                    </div>
                </div>
            </div>
            @if ($anime->tags != null)
            <div class="flex flex-wrap gap-2 my-4">
                @foreach($anime->tags as $tag)
                    <div class="relative">
                        <a href="#" 
                        class="py-1.5 px-3 text-sm bg-slate-800 text-gray-300 rounded-full leading-loose font-semibold hover:bg-slate-300 hover:text-slate-800 transition duration-400">
                            {{ $tag->name }}
                        </a>
                    </div>
                @endforeach
            </div>
            
            @endif
            <div class="bg-slate-800 rounded-lg p-3">
                <p class="text-gray-300">{{ $anime->synopsis}}</p>
            </div>
            
        </div>
        <div class="w-full lg:basis-1/3 lg:p-4 sm:p-1">
            @foreach ($random as $rand)
            <div class="border-b border-white/10 pb-5">
                <a class="flex w-full h-[110px] rounded-lg scale-100 transition-all duration-300 ease-out cursor-pointer hover:scale-[1.02] ring-0 hover:ring-1 hover:shadow-lg ring-slate-700 bg-slate-800" href="{{ url('watch/'.$rand->id) }}">
                    <div class="w-[43%] lg:w-[42%] h-[110px] aspect-video relative rounded-lg z-40 shrink-0 overflow-hidden shadow-[4px_0px_5px_0px_rgba(0,0,0,0.3)] transition-all duration-300 ease-out">
                        <img alt="episode thumbnail" loading="lazy" width="200" height="200" decoding="async" data-nimg="1" class="w-full h-full object-cover" src="https://image.tmdb.org/t/p/w500/aIgNMI3bAR1yauVFlozRzsRdmxj.jpg" style="color: transparent;">
                    <div style="width: 0%; height: 2px; background-color: red; position: absolute; bottom: 0px; left: 0px;"></div>
                    <span class="absolute bottom-2 left-2 font-karla font-semibold text-sm bg-black/70 p-1 rounded text-gray-300">Ан {{ $rand->current_episode}}</span>
                    </div>
                    <div class="w-full h-full overflow-x-hidden select-none px-4 py-2 flex flex-col justify-evenly text-gray-300">
                        <h1 class="font-karla font-bold line-clamp-1">{{ $rand->name }}</h1>
                        <p class="line-clamp-2 text-xs italic font-outfit font-extralight">{{ $rand->synopsis }}</p>
                    <div class="flex">
                        <div class="grid grid-cols-2 place-content-center place-items-center gap-1 capitalize text-sm text-white/50 shrink-0">
                            <div title="Sub Available" class="w-full h-full flex-center">
                                <svg viewBox="0 0 32 32" class="w-5 h-5 inline text-white vds-icon" fill="none" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.6661 6.66699C4.29791 6.66699 3.99943 6.96547 3.99943 7.33366V24.667C3.99943 25.0352 4.29791 25.3337 4.6661 25.3337H27.3328C27.701 25.3337 27.9994 25.0352 27.9994 24.667V7.33366C27.9994 6.96547 27.701 6.66699 27.3328 6.66699H4.6661ZM8.66667 21.3333C8.29848 21.3333 8 21.0349 8 20.6667V11.3333C8 10.9651 8.29848 10.6667 8.66667 10.6667H14C14.3682 10.6667 14.6667 10.9651 14.6667 11.3333V12.6667C14.6667 13.0349 14.3682 13.3333 14 13.3333H10.8C10.7264 13.3333 10.6667 13.393 10.6667 13.4667V18.5333C10.6667 18.607 10.7264 18.6667 10.8 18.6667H14C14.3682 18.6667 14.6667 18.9651 14.6667 19.3333V20.6667C14.6667 21.0349 14.3682 21.3333 14 21.3333H8.66667ZM18 21.3333C17.6318 21.3333 17.3333 21.0349 17.3333 20.6667V11.3333C17.3333 10.9651 17.6318 10.6667 18 10.6667H23.3333C23.7015 10.6667 24 10.9651 24 11.3333V12.6667C24 13.0349 23.7015 13.3333 23.3333 13.3333H20.1333C20.0597 13.3333 20 13.393 20 13.4667V18.5333C20 18.607 20.0597 18.6667 20.1333 18.6667H23.3333C23.7015 18.6667 24 18.9651 24 19.3333V20.6667C24 21.0349 23.7015 21.3333 23.3333 21.3333H18Z" fill="currentColor"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

@section('styles')
    <link rel="stylesheet" href="https://cdn.vidstack.io/player/theme.css" />
    <link rel="stylesheet" href="https://cdn.vidstack.io/player/video.css" />
    <style>
        ::cue {
            background: transparent;
            color: white;
            font-size: 16px;
            text-shadow: 1px 1px 2px black;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.vidstack.io/player" type="module"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const player = document.querySelector('media-player');
        const animeId = {{ $anime->id }}; // Video ID passed from Laravel Blade

        const userToken = localStorage.getItem('user_token'); // Ensure the token is stored in localStorage
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        // Function to load saved progress
        const loadSavedProgress = async () => {
            try {
                const response = await axios.get(`/get-progress/${animeId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const savedProgress = parseFloat(response.data.current_time || 0);
                console.log(`Loaded progress: ${savedProgress}`);

                // Set progress when media is ready
                const applySavedProgress = () => {
                    console.log('Media loaded. Applying saved progress...');
                    player.currentTime = savedProgress; // Set progress
                    console.log(`Player time set to: ${player.currentTime}`);
                };

                if (player.readyState >= 1) {
                    // Media is already ready
                    applySavedProgress();
                } else {
                    // Wait for media to load
                    player.addEventListener('media-loaded', applySavedProgress, { once: true });
                }
            } catch (error) {
                console.error('Error loading progress:', error.response?.data || error.message);
            }
        };

        // Call load progress on page load
        loadSavedProgress();

        // Function to save progress
        const saveProgress = async (currentTime) => {
            try {
                await axios.post(
                    'http://localhost:8000/save-progress',
                    {
                        animes_id: animeId, // Anime ID
                        current_time: currentTime, // Current playback time
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${userToken}`, // Add the user's token here
                        },
                    }
                );
                console.log('Progress saved successfully:', currentTime);
            } catch (error) {
                console.error('Error saving progress:', error.response?.data || error.message);
            }
        };

        // Save progress every 5 seconds
        player.addEventListener('timeupdate', () => {
            if (Math.floor(player.currentTime) % 5 === 0) {
                saveProgress(player.currentTime);
                localStorage.setItem(`video_${animeId}_progress`, player.currentTime); // Save to localStorage
            }
        });

        // Save progress on pause
        player.addEventListener('media-paused', () => {
            console.log('Video paused, saving progress...');
            saveProgress(player.currentTime);
            localStorage.setItem(`video_${animeId}_progress`, player.currentTime); // Save to localStorage
        });

        // Save progress on page unload (before navigating away)
        window.addEventListener('beforeunload', () => {
            console.log('Page unloading, saving progress...');
            saveProgress(player.currentTime);
            localStorage.setItem(`video_${animeId}_progress`, player.currentTime); // Save to localStorage
        });

        // Debug: Log when the media is ready
        player.addEventListener('media-ready', () => {
            console.log('Media is ready');
        });
    });

    </script>
@endsection

</x-app-layout>