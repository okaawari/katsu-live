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
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile picture.") }}
        </p>
    </header>

    <form wire:submit="updateProfilePicture" class="mt-6 space-y-6" enctype="multipart/form-data">
        <div class="flex items-center">
            @if ($profilePicture)
                <div class="mt-2">
                    <img src="{{ $profilePicture->temporaryUrl() }}" alt="Profile Picture Preview" class="rounded-full w-32 object-cover">
                </div>
            @elseif(auth()->user()->avatar)
                <div class="mt-2">
                    <img src="{{ auth()->user()->avatar() }}" alt="Current Profile Picture" class="rounded-full w-32 object-cover">
                </div>
            @endif

            <input wire:model="profilePicture" id="profilePicture" name="profilePicture" type="file" class="ml-4 block w-full bg-gray-800 text-sm text-gray-300 rounded-lg border border-gray-700 cursor-pointer  focus:outline-none" required accept="image/*" />

            <x-input-error class="mt-2" :messages="$errors->get('profilePicture')" />

            
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="mr-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
