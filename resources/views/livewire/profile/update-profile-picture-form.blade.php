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

    public $profilePicture;

    public function updateProfilePicture(): void
    {
        $this->validate([
            'profilePicture' => ['required', 'image', 'max:2048'], // Validate image file
        ]);

        $user = auth()->user();
        $file = $this->profilePicture;

        $ext = $file->getClientOriginalExtension();
        $filename = Auth::id() . '.' . $ext;

        $manager = new ImageManager(Driver::class);

        $avatar = $manager->read($file->getRealPath());

        $avatar->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/public/user/avatar/').$filename);

        $user->avatar = $filename;
        $user->save();

        $this->dispatch('profile-updated', name: $user->name, avatar: $user->avatar);
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Picture') }}
        </h2>

    </header>

    <form wire:submit="updateProfilePicture" class="mt-6 space-y-6" enctype="multipart/form-data">
        <div class="flex flex-col items-center space-y-4">
            <!-- Current Profile Picture -->
            <div class="relative">
                @if ($profilePicture)
                    <img src="{{ $profilePicture->temporaryUrl() }}" alt="Profile Picture Preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700">
                @elseif(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar() }}" alt="Current Profile Picture" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-4 border-gray-200 dark:border-gray-700">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
                
                <!-- Upload Button Overlay -->
                <label for="profilePicture" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full cursor-pointer transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </label>
            </div>

            <!-- File Input (Hidden) -->
            <input wire:model="profilePicture" id="profilePicture" name="profilePicture" type="file" class="hidden" accept="image/*" />

            <!-- File Info -->
            @if ($profilePicture)
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Selected: {{ $profilePicture->getClientOriginalName() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ number_format($profilePicture->getSize() / 1024, 1) }} KB</p>
                </div>
            @endif

            <x-input-error class="mt-2" :messages="$errors->get('profilePicture')" />
        </div>

        <div class="flex items-center justify-center gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                {{ __('Save Changes') }}
            </button>

            <x-action-message class="mr-3" on="profile-updated">
                {{ __('Profile picture updated successfully!') }}
            </x-action-message>
        </div>
    </form>
</section>
