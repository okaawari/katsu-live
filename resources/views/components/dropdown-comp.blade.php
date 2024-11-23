
@props(['label' => 'Options', 'options' => []])

<div x-data="dropdownComponent({{ json_encode($options) }}, '{{ $label }}')" class="relative text-gray-300" x-init="console.log(options)">
    <button
        x-ref="button"
        x-on:click="toggle()"
        :aria-expanded="open"
        :aria-controls="$id('dropdown-button')"
        type="button"
        class="flex items-center gap-2 bg-slate-800 px-5 py-2.5 rounded-md shadow"
    >
        <span x-text="buttonLabel || '{{ $label }}'"></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    <div 
        x-ref="panel"
        x-show="open"
        x-transition.origin.top.left
        x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-button')"
        style="display: none;"
        class="absolute left-0 mt-2 rounded-md overflow-y-auto bg-slate-800 shadow-md"
    >
        <div class="grid p-2">
            <template x-for="option in options" :key="option">
                <label class="flex items-center gap-2 w-[120px] p-1 text-left text-sm hover:bg-gray-slate-700">
                    <input type="checkbox" @change="updateSelectedOptions(option)" class="form-checkbox rounded-sm bg-slate-800">
                    <span x-text="option"></span>
                </label>
            </template>
        </div>
    </div>
</div>