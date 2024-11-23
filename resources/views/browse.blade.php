<x-app-layout>
  <div class="max-w-7xl mx-auto px-4">
    <div class="hidden lg:flex justify-center my-8 gap-4 text-sm">
      <div class="relative min-w-full lg:min-w-[160px] w-full">
        <input type="text" placeholder="Search..." x-model="searchTerm" class="w-full h-full bg-slate-800 text-gray-200 px-2 py-1 border-slate-800 rounded-md border-transparent focus:border-transparent focus:ring-0"/>
      </div>
      <div class="relative min-w-full lg:min-w-[160px] w-full">
        <x-dropdown-modal :items="$studios->toArray()" label="Studio"/>
      </div>
      <div class="relative min-w-full lg:min-w-[160px] w-full">
        <x-dropdown-modal :items="$years->toArray()" label="Year"/>
      </div>
      <div class="relative min-w-full lg:min-w-[160px] w-full">
        <x-dropdown-modal :items="$tags->toArray()" label="Tags"/>
      </div>
      <div class="relative min-w-full lg:min-w-[160px] w-full">
        <x-dropdown-modal :items="['TV Series', 'OVA', 'ONA', 'Movie']" label="Status"/>
      </div>
      <div class="relative min-w-full lg:min-w-[80px] w-[160px]">
        <button class="bg-slate-600 w-full h-full rounded-md px-4 py-2 text-gray-200">
          Search
        </button>
      </div>
    </div>

    <div class="flex lg:hidden w-full justify-between iems-end mt-8 gap-4 text-sm relative">
      <div class="grow">
        <input type="text" placeholder="Search..." x-model="searchTerm" class="w-full h-full bg-slate-800 text-gray-200 px-2 py-1 border-slate-800 rounded-md border-transparent focus:border-transparent focus:ring-0"/>
      </div>
      <div class="flex-none w-[80px]">
        <button class="bg-slate-600 w-full h-full rounded-md px-4 py-2 text-gray-200">
          Search
        </button>
      </div>
    </div>
    <div class="flex lg:hidden w-full justify-center my-4 gap-4 text-sm">
      <div class="grid grid-cols-2 grid-rows-2 w-full gap-4">
        <div class="relative min-w-full lg:min-w-[160px] w-full">
          <x-dropdown-modal :items="$studios->toArray()" label="Studio"/>
        </div>
        <div class="relative min-w-full lg:min-w-[160px] w-full">
          <x-dropdown-modal :items="$years->toArray()" label="Year"/>
        </div>
        <div class="relative min-w-full lg:min-w-[160px] w-full">
          <x-dropdown-modal :items="$tags->toArray()" label="Tags"/>
        </div>
        <div class="relative min-w-full lg:min-w-[160px] w-full">
          <x-dropdown-modal :items="['TV Series', 'OVA', 'ONA', 'Movie']" label="Status"/>
        </div>
      </div>
    </div>

    <div>
      <x-card-browse :animes="$animes"/>
    </div>
  </div>


<script>
  console.log($years);
  
</script>

</x-app-layout>
