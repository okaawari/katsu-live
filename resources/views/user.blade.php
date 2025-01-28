<x-app-layout>
    <!-- <div class="">
        <img class="w-full h-72 object-cover opacity-50" src="images/cover.jpg"/>
    </div> -->
    <div class="max-w-7xl sm:mx-auto text-gray-300 mx-4">
        <div class="flex mt-8 items-center">
            <div class="">
                <img class="w-36 h-36 rounded-full" src="{{ auth()->user()->avatar() }}"/>
            </div>
            <div class="ml-4">
                <p class="text-2xl font-extrabold">{{ auth()->user()->name }}</p>
                <p> {{ auth()->user()->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
        <!-- <div class="mt-2">
            <p>Бидэнтэй нэгдээд {{ intval(abs(now()->diffInDays(auth()->user()->created_at))) }} өдөр өнгөрч ээ.</p>
        </div> -->
        <div class="mt-4 grid gap-4">
            @foreach (auth()->user()->animelist as $list)
                <div class="flex bg-slate-800 rounded-md p-2">
                    <img class="w-12 object-cover" src="images/poster.jpg"/>
                    <p class="text-sm text-gray-500">{{ $list->created_at->diffForHumans() }}</p>
                    <p class="">{{ $list->anime->name_second }}</p>
                </div>
            @endforeach
        </div>
        
    </div>
</x-app-layout>