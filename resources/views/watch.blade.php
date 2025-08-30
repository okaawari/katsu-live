<x-app-layout>
    <div class="flex flex-wrap max-w-[1400px] mx-auto px-4">
        <div class="relative w-full lg:basis-2/3 pt-4">
        <!-- Vidstack Player -->
        <media-player id="myPlayer"
            title="{{ $episode->anime->title ?? $episode->anime->name }} - Episode {{ $episode->episode_number }}" 
            playsinline
            style="width: 100%; height: 480px;">

            <media-player id="myPlayer"
            title="{{ $episode->name }}" 
            src="https://fukkatsu.club/storage/video/{{ $episode->video_720p }}" 
            playsinline>

            <media-provider>
                <track
                    src="https://fukkatsu.club/storage/subs/{{ $episode->subtitle_mongolian }}"
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

            <media-video-layout thumbnails="{{ url('images/sprites/' . $anime->id . '.vtt') }}"></media-video-layout>
        </media-player>

            @if($episode->sprite_vtt && $episode->sprite_image)
            <media-video-layout thumbnails="{{ $episode->sprite_vtt }}"></media-video-layout>
            @endif
        </media-player>


            <div class="mt-1 mb-3"> 
                <h1 class="text-gray-300 text-xl font-semibold">{{ $episode->anime->title ?? $episode->anime->name }} - Episode {{ $episode->episode_number }}</h1>
                @if($episode->title)
                <p class="text-gray-400 text-sm mt-1">{{ $episode->title }}</p>
                @endif
            </div>
            <div class="border-b border-slate-800"></div>
            <div class="flex w-full rounded-lg mt-3">
                <div class="">
                    <img
                        class="sm:w-[180px] sm:min-w-[180px] sm:h-[270px] min-w-[120px] h-[190px] object-cover rounded-lg opacity-90"
                        src="{{ $episode->poster_image ?? $episode->anime->cover_image ?? $episode->anime->poster ?? '/images/poster.jpg' }}"
                        alt="{{ $episode->anime->title ?? $episode->anime->name }} Episode {{ $episode->episode_number }}"
                    />
                </div>
                <div class="grid justify-between text-gray-300 ml-4">
                    <div>
                        <p class="font-thin text-gray-400">Episode</p>
                        <p class="">{{ $episode->episode_number }}</p>
                    </div>
                    <div>
                        <p class="font-thin text-gray-400">Duration</p>
                        <p>{{ $episode->formatted_duration ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="font-thin text-gray-400">Published</p>
                        <p>{{ $episode->published_at ? $episode->published_at->format('M d, Y') : 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="font-thin text-gray-400">Anime</p>
                        <div class="block sm:flex overflow-y-hidden">
                            <h2 class="">{{ $episode->anime->title ?? $episode->anime->name }}</h2>
                            @if($episode->anime->title_english)
                            <h3 class="pl-0 sm:pl-5">{{ $episode->anime->title_english }}</h3>
                            @endif
                        </div>
                    </div>
                    @if($episode->anime->category)
                    <div>
                        <p class="font-thin text-gray-400">Category</p>
                        <p>{{ $episode->anime->category->name }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @if ($episode->tags && $episode->tags->count() > 0)
            <div class="flex flex-wrap gap-2 my-4">
                @foreach($episode->tags as $tag)
                    <div class="relative">
                        <a href="#" 
                        class="py-1.5 px-3 text-sm bg-slate-800 text-gray-300 rounded-full leading-loose font-semibold hover:bg-slate-300 hover:text-slate-800 transition duration-400">
                            {{ $tag->name_mn ?? $tag->name }}
                        </a>
                    </div>
                @endforeach
            </div>
            @endif
            <div class="bg-slate-800 rounded-lg p-3 mb-4">
                <p class="text-gray-300">{{ $episode->synopsis ?? $episode->anime->description ?? 'No description available' }}</p>
            </div>
            
        </div>
        <div class="w-full lg:basis-1/3 lg:p-4 sm:p-1">
            <h3 class="text-gray-300 text-lg font-semibold mb-4">More Episodes</h3>
            @foreach ($random as $rand)
            <div class="border-b border-white/10 pb-5 mb-4">
                <a class="flex w-full h-[110px] rounded-lg scale-100 transition-all duration-300 ease-out cursor-pointer hover:scale-[1.02] ring-0 hover:ring-1 hover:shadow-lg ring-slate-700 bg-slate-800" href="{{ url('watch/'.$rand->slug) }}">
                    <div class="w-[43%] lg:w-[42%] h-[110px] aspect-video relative rounded-lg z-40 shrink-0 overflow-hidden shadow-[4px_0px_5px_0px_rgba(0,0,0,0.3)] transition-all duration-300 ease-out">
                        <img alt="episode thumbnail" loading="lazy" width="200" height="200" decoding="async" data-nimg="1" class="w-full h-full object-cover" src="{{ $rand->poster_image ?? $rand->anime->cover_image ?? $rand->anime->poster ?? '/images/poster.jpg' }}" style="color: transparent;">
                    <div style="width: 0%; height: 2px; background-color: red; position: absolute; bottom: 0px; left: 0px;"></div>
                    <span class="absolute bottom-2 left-2 font-karla font-semibold text-sm bg-black/70 p-1 rounded text-gray-300">Ep {{ $rand->episode_number }}</span>
                    </div>
                    <div class="w-full h-full overflow-x-hidden select-none px-4 py-2 flex flex-col justify-evenly text-gray-300">
                        <h1 class="font-karla font-bold line-clamp-1">{{ $rand->anime->title ?? $rand->anime->name }}</h1>
                        <p class="line-clamp-2 text-xs italic font-outfit font-extralight">{{ $rand->synopsis ?? $rand->anime->description ?? 'No description' }}</p>
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
        
        #myPlayer {
            width: 100% !important;
            height: 400px !important;
            min-height: 400px !important;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        #myPlayer video {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain;
        }
        

    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.vidstack.io/player" type="module"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, looking for player...');
            const player = document.getElementById('myPlayer');
            
            console.log('Player element:', player);
            
            if (!player) {
                console.error('Player element not found!');
                return;
            }
            
            let savedProgress = 0;

            // From your Blade/Laravel variable
            const episodeId = {{ $episode->id }};
            const animeId = {{ $episode->anime->id }};
            const userToken = localStorage.getItem('user_token');
            const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

            // 1) Load saved progress from server
            const loadSavedProgress = async () => {
                try {
                    const response = await axios.get(`/get-progress/${animeId}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    savedProgress = parseFloat(response.data.current_time || 0);
                    console.log(`Loaded progress: ${savedProgress}`);

                    // Wait for the video to be ready before setting the time
                    if (player.readyState >= 2) {
                        setVideoTime();
                    } else {
                        player.addEventListener('vds-media-ready', setVideoTime, { once: true });
                    }
                } catch (error) {
                    console.error('Error loading progress:', error.response?.data || error.message);
                }
            };

            // Function to set the video time
            const setVideoTime = () => {
                if (savedProgress > 0) {
                    console.log('Setting video time to:', savedProgress);
                    player.currentTime = savedProgress;
                    
                    // Double-check if the time was set correctly
                    setTimeout(() => {
                        if (Math.abs(player.currentTime - savedProgress) > 1) {
                            console.log('Retrying to set video time...');
                            player.currentTime = savedProgress;
                        }
                    }, 1000);
                }
            };

            // Call load on page load (optional - won't break if it fails)
            try {
                loadSavedProgress();
            } catch (error) {
                console.log('Progress loading failed, continuing without saved progress');
            }

            // 2) Save progress function
            const saveProgress = async (currentTime) => {
                try {
                    await axios.post(
                        '/save-progress',
                        {
                            animes_id: animeId,
                            episode_id: episodeId,
                            current_time: currentTime
                        },
                        {
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                Authorization: `Bearer ${userToken}`,
                            },
                        }
                    );
                    console.log('Progress saved successfully:', currentTime);
                } catch (error) {
                    console.error('Error saving progress:', error.response?.data || error.message);
                }
            };

            // 3) Listen for Vidstack events to save progress
            player.addEventListener('vds-time-update', () => {
                // Save every 5 seconds (when integer time is multiple of 5)
                if (Math.floor(player.currentTime) % 5 === 0) {
                    saveProgress(player.currentTime);
                    localStorage.setItem(`video_${episodeId}_progress`, player.currentTime);
                }
            });

            player.addEventListener('vds-pause', () => {
                console.log('Video paused, saving progress...');
                saveProgress(player.currentTime);
                localStorage.setItem(`video_${episodeId}_progress`, player.currentTime);
            });

            // 4) Save progress on page unload
            window.addEventListener('beforeunload', () => {
                console.log('Page unloading, saving progress...');
                saveProgress(player.currentTime);
                localStorage.setItem(`video_${episodeId}_progress`, player.currentTime);
            });

            // Debug events
            player.addEventListener('vds-media-ready', () => {
                console.log('Media is ready');
            });

            player.addEventListener('vds-time-update', () => {
                console.log('Current time:', player.currentTime);
            });
        });
    </script>
@endsection

</x-app-layout>