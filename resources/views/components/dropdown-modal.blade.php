@props(['label' => 'Options', 'items' => []])

<div 
    x-data="{
        open: false,
        selected: null,
        search: '',
        filteredItems: @js($items),
        toggle() {
            if (this.open) {
                return this.close();
            }
            this.$refs.button.focus();
            this.open = true;
        },
        close(focusAfter) {
            if (!this.open) return;
            this.open = false;
            focusAfter && focusAfter.focus();
        },
        selectItem(item) {
            this.selected = item;
            this.close(this.$refs.button);
        },
        clearSelection() {
            this.selected = null;
        },
        filterItems() {
            const search = this.search.toString().toLowerCase();
            this.filteredItems = @js($items).filter(item =>
                item.toString().toLowerCase().includes(search)
            );
        },
        init() {
            // Ensure all items are shown initially
            this.filteredItems = @js($items);
        }
    }"
    x-init="init()"
    x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
    x-id="['dropdown-button']"
    class="relative text-gray-300"
>
    <!-- Button -->
    <button
        x-ref="button"
        x-on:click="toggle()"
        :aria-expanded="open"
        :aria-controls="$id('dropdown-panel')"
        type="button"
        class="flex items-center w-full justify-between bg-slate-800 px-5 py-2.5 rounded-lg shadow"
    >
        <!-- Selected Label -->
        <span x-text="selected || '{{ $label }}'"></span>
        
        <div class="flex items-center gap-2">
            <!-- X button to clear selection -->
            <template x-if="selected">
                <button
                    x-on:click.stop="clearSelection()"
                    class="flex items-center justify-center w-5 h-5 text-sm bg-slate-700 text-white rounded-full"
                    aria-label="Clear selection"
                    type="button"
                >
                    &times;
                </button>
            </template>

            <!-- Heroicon: chevron-down -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </button>


    <!-- Panel -->
    <div
        x-ref="panel"
        x-show="open"
        x-transition.origin.top.left
        x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-panel')"
        style="display: none;"
        class="absolute left-0 mt-2 min-w-full lg:min-w-[160px] w-full max-h-60 overflow-y-auto rounded-lg bg-slate-800 shadow-md
        [&::-webkit-scrollbar]:w-2
        [&::-webkit-scrollbar-track]:rounded-full
        [&::-webkit-scrollbar-track]:bg-slate-100
        [&::-webkit-scrollbar-thumb]:rounded-full
        [&::-webkit-scrollbar-thumb]:bg-slate-300
        dark:[&::-webkit-scrollbar-track]:bg-slate-700
        dark:[&::-webkit-scrollbar-thumb]:bg-slate-500"
        
    >
        <!-- Search Bar -->
        <div class="p-2">
            <input 
                type="text"
                x-model="search"
                x-on:input="filterItems()"
                placeholder="Search..."
                class="w-full px-2 py-1 text-sm bg-slate-700 text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
        </div>

        <!-- Items -->
        <div>
            <template x-for="item in filteredItems" :key="item">
                <div 
                    x-on:click="selectItem(item)"
                    class="flex items-center justify-between px-4 py-2 cursor-pointer text-sm rounded-lg hover:bg-gray-700 hover:text-white"
                    :class="{ 'bg-slate-700': selected === item }"
                >
                    <span x-text="item"></span>
                </div>
            </template>

            <!-- No Items Found -->
            <template x-if="filteredItems.length === 0">
                <div class="px-4 py-2 text-sm text-gray-500">
                    No items found.
                </div>
            </template>
        </div>
    </div>
</div>
