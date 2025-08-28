<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

new class extends Component
{
    use WithFileUploads;

    public $coverImage;

    public function updateCoverImage(): void
    {
        $this->validate([
            'coverImage' => ['required', 'image', 'max:5120'], // 5MB max
        ]);

        $user = auth()->user();
        $file = $this->coverImage;

        $ext = $file->getClientOriginalExtension();
        $filename = Auth::id() . '_cover.' . $ext;

        $manager = new ImageManager(Driver::class);

        $cover = $manager->read($file->getRealPath());

        // Resize to optimal cover image dimensions (1200x400) while maintaining aspect ratio
        $cover->resize(1200, 400, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Create a canvas with the target dimensions and center the image
        $canvas = $manager->create(1200, 400);
        $canvas->place($cover, 'center');
        $canvas->save(storage_path('app/public/user/cover/').$filename);

        $user->cover_image = $filename;
        $user->save();

        $this->dispatch('cover-updated', cover: $user->cover_image);
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Cover Image') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your profile's cover image.") }}
        </p>
    </header>

    <form wire:submit="updateCoverImage" class="mt-6 space-y-6" enctype="multipart/form-data">
        <div class="flex flex-col items-center space-y-4">
            <!-- Current Cover Image -->
            <div class="relative w-full max-w-2xl">
                @if ($coverImage)
                    <img src="{{ $coverImage->temporaryUrl() }}" alt="Cover Image Preview" class="w-full h-48 md:h-64 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                @elseif(auth()->user()->cover_image)
                    <img src="{{ auth()->user()->coverImage() }}" alt="Current Cover Image" class="w-full h-48 md:h-64 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                @else
                    <div class="w-full h-48 md:h-64 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-700 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No cover image set</p>
                        </div>
                    </div>
                @endif
                
                <!-- Upload Button Overlay -->
                <label for="coverImage" class="absolute bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full cursor-pointer transition-colors shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </label>
            </div>

            <!-- File Input (Hidden) -->
            <input wire:model="coverImage" id="coverImage" name="coverImage" type="file" class="hidden" accept="image/*" />

            <!-- File Info -->
            @if ($coverImage)
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Selected: {{ $coverImage->getClientOriginalName() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ number_format($coverImage->getSize() / 1024, 1) }} KB</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Recommended: 1200x400 pixels, max 5MB</p>
                </div>
            @else
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-500">Recommended: 1200x400 pixels, max 5MB</p>
                </div>
            @endif

            <x-input-error class="mt-2" :messages="$errors->get('coverImage')" />
        </div>

        <div class="flex items-center justify-center gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                {{ __('Save Cover Image') }}
            </button>

            <x-action-message class="mr-3" on="cover-updated">
                {{ __('Cover image updated successfully!') }}
            </x-action-message>
        </div>
    </form>
</section>
